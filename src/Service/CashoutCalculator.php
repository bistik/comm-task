<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Service;

use Bianes\CommissionTask\Entity\Commission;
use Bianes\CommissionTask\Entity\LegalUser;
use Bianes\CommissionTask\Entity\Operation;
use Bianes\CommissionTask\Entity\User;

final class CashoutCalculator implements Calculator
{
    private static $ROUND_SCALE = [
        'EUR' => 2,
        'JPY' => 0,
        'USD' => 2
    ];

    /**
     * @param \Bianes\CommissionTask\Entity\Commission $commission
     * @param \Bianes\CommissionTask\Entity\Operation $operation
     * @param \Bianes\CommissionTask\Entity\User|null $user
     *
     * @return string
     */
    public function computeFee(Commission $commission, Operation $operation, User $user = null): string
    {
        $percentage = $commission->getPercentage() / 100;
        $commissionFee = bcmul(
            (string)$percentage,
            (string)$operation->getAmount(),
            self::$ROUND_SCALE[$operation->getCurrency()]
        );

        // computed fee is less than minimum. but only if there is a minimum set (> 0)
        if ($commission->getMinimum() > 0 && $commissionFee < $commission->getMinimum()) {
            return sprintf("%.2f", $commission->getMinimum());
        }

        return $commissionFee;
    }
}
