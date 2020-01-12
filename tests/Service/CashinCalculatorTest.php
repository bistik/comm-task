<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Tests\Service;

use Bianes\CommissionTask\Entity\CashinCommission;
use Bianes\CommissionTask\Entity\CashoutOperation;
use Bianes\CommissionTask\Entity\LegalUser;
use Bianes\CommissionTask\Entity\NaturalUser;
use Bianes\CommissionTask\Entity\Operation;
use Bianes\CommissionTask\Entity\User;
use Bianes\CommissionTask\Service\CashinCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bianes\CommissionTask\Service\CashinCalculator
 */
final class CashinCalculatorTest extends TestCase
{
    /** @var \Bianes\CommissionTask\Service\Calculator */
    protected $calculator;

    protected function setUp()
    {
        $this->calculator = new CashinCalculator();
    }

    /**
     *
     * @covers \Bianes\CommissionTask\Service\CashinCalculator::computeFee
     * @dataProvider dataProviderForCommission
     *
     * @param \Bianes\CommissionTask\Entity\Operation $operation
     * @param \Bianes\CommissionTask\Entity\User $user
     * @param string $expected
     */
    public function testComputeCommission(Operation $operation, User $user, string $expected)
    {
        $commission = new CashinCommission(0.03, 5.0);
        $this->assertEquals(
            $expected,
            $this->calculator->computeFee($commission, $operation, $user)
        );
    }

    /**
     * @return array
     */
    public function dataProviderForCommission(): array
    {
        return [
            'cash in below maximum' => [
                new CashoutOperation('200', '2015-01-02', 'EUR'),
                new NaturalUser(1),
                '0.06'
            ],
            'cash in above maximum' => [
                new CashoutOperation('1000000.00', '2015-01-02', 'EUR'),
                new LegalUser(2),
                '5.00'
            ]
        ];
    }
}
