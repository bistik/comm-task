<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Tests\Service;

use Bianes\CommissionTask\Entity\CashoutCommission;
use Bianes\CommissionTask\Entity\CashoutOperation;
use Bianes\CommissionTask\Entity\Commission;
use Bianes\CommissionTask\Entity\LegalUser;
use Bianes\CommissionTask\Entity\NaturalUser;
use Bianes\CommissionTask\Entity\Operation;
use Bianes\CommissionTask\Entity\User;
use Bianes\CommissionTask\Service\CashoutCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bianes\CommissionTask\Service\CashoutCalculator
 */
final class CashoutCalculatorTest extends TestCase
{
    /** @var \Bianes\CommissionTask\Service\Calculator */
    protected $calculator;

    protected function setUp()
    {
        $this->calculator = new CashoutCalculator();
    }

    /**
     * @dataProvider dataProviderForCommission
     *
     * @param \Bianes\CommissionTask\Entity\Commission $commission
     * @param \Bianes\CommissionTask\Entity\Operation $operation
     * @param \Bianes\CommissionTask\Entity\User $user
     * @param string $expected
     */
    public function testComputeCommission(
        Commission $commission,
        Operation $operation,
        User $user,
        string $expected
    ) {
        $fee = $this->calculator->computeFee($commission, $operation, $user);
        $this->assertEquals($expected, $fee);
    }

    public function dataProviderForCommission(): array
    {
        $naturalCommission = new CashoutCommission(0.3);
        $legalCommission = new CashoutCommission(0.3, 0.5);

        return [
            // natural 1200 -> 3.60
            'natural cashout' => [
                $naturalCommission,
                new CashoutOperation('1200', '2014-12-31', 'EUR'),
                new NaturalUser(4),
                '3.60'
            ],
            // natural 100 -> 0.3
            'natural cashout no minimum' => [
                $naturalCommission,
                new CashoutOperation('100', '2015-01-01', 'EUR'),
                new NaturalUser(1),
                '0.30'
            ],
            // legal 300 -> 0.9
            'legal cashout more than minimum' => [
                $legalCommission,
                new CashoutOperation('300', '2015-01-02', 'EUR'),
                new LegalUser(2),
                '0.90'
            ],
            // legal 100 -> 0.5
            'legal cashout less than minimum' => [
                $legalCommission,
                new CashoutOperation('100', '2015-01-02', 'EUR'),
                new LegalUser(2),
                '0.50'
            ],
        ];
    }
}
