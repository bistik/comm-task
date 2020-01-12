<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Bianes\CommissionTask\Entity\CashinCommission;
use Bianes\CommissionTask\Entity\CashoutCommission;
use Bianes\CommissionTask\Service\CashinCalculator;
use Bianes\CommissionTask\Service\CashoutCalculator;
use Bianes\CommissionTask\Service\Repository;

$csvFile = $argv[1] ?? null;
if ($csvFile === null) {
    die('Csv file not found');
}
$rows = getCsvRows($csvFile);
computeCommission($rows);

/**
 * @param array $rows
 *
 * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
 * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
 */
function computeCommission(array $rows)
{
    $repository = new Repository();
    $cashinCommission = new CashinCommission(0.03, 5.0);
    $repository->addCommission(Repository::CASH_IN, Repository::NATURAL, $cashinCommission);
    $repository->addCommission(Repository::CASH_IN, Repository::LEGAL, $cashinCommission);
    $repository->addCommission(
        Repository::CASH_OUT,
        Repository::NATURAL,
        new CashoutCommission(0.3)
    );
    $repository->addCommission(
        Repository::CASH_OUT,
        Repository::LEGAL,
        new CashoutCommission(0.3, 0.5)
    );

    $cashinCalculator = new CashinCalculator();
    $cashoutCalculator = new CashoutCalculator();

    foreach ($rows as $row) {
        $user = $repository->createUser($row[2], (int)$row[1]);
        $operation = $repository->createOperation($user, $row[3], $row[4], $row[0],$row[5]);
        $commission = $repository->getCommission($operation, $user);

        if ($operation->getType() === Repository::CASH_IN) {
            echo $cashinCalculator->computeFee($commission, $operation, $user) . "\n";
        }

        if ($operation->getType() === Repository::CASH_OUT) {
            echo $cashoutCalculator->computeFee($commission, $operation, $user) . "\n";
        }
    }
}

function getCsvRows(string $filename = null): array
{
    if ($filename === null) {
        return [];
    }

    /** @var mixed[] */
    $rows = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);
    }

    return $rows;
}
