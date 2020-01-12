<?php

require __DIR__ . '/vendor/autoload.php';

use Bianes\CommissionTask\Entity\CashinCommission;
use Bianes\CommissionTask\Entity\CashoutCommission;
use Bianes\CommissionTask\Service\CashinCalculator;
use Bianes\CommissionTask\Service\CashoutCalculator;
use Bianes\CommissionTask\Service\Repository;
use Bianes\Entity\Operation;

$data = [
    ['2014-12-31', 4, 'natural', 'cash_out', 1200.00, 'EUR'],
    ['2015-01-01', 4, 'natural', 'cash_out', 1000.00, 'EUR'],
    ['2016-01-05', 4, 'natural', 'cash_out', 1000.00, 'EUR'],
    ['2016-01-05', 1, 'natural', 'cash_in', 200.00, 'EUR'],
    ['2016-01-06', 2, 'legal', 'cash_out', 300.00, 'EUR'],
    ['2016-01-07', 1, 'natural', 'cash_out', 1000.00, 'EUR'],
    ['2016-01-10', 1, 'natural', 'cash_out', 100.00, 'EUR'],
    ['2016-01-10', 2, 'legal', 'cash_in', 1000000.00, 'EUR'],
    ['2016-01-10', 3, 'natural', 'cash_out', 1000.00, 'EUR'],
    ['2016-02-15', 1, 'natural', 'cash_out', 300.00, 'EUR'],
    ['2016-01-07', 1, 'natural', 'cash_out', 100.00, 'USD'],
    ['2016-02-19', 5, 'natural', 'cash_out', 3000000, 'JPY']
];

computeCommission($data);

/**
 * @param array $rows
 *
 * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
 * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
 */
function computeCommission(array $rows) {
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
        $user = $repository->createUser($row[2], $row[1]);
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
