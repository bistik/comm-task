<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Service;

use Bianes\CommissionTask\Entity\Commission;
use Bianes\CommissionTask\Entity\Operation;
use Bianes\CommissionTask\Entity\User;

final class CashinCalculator implements Calculator
{
    /**
     * Compute commission fee.
     *
     * @param \Bianes\CommissionTask\Entity\Commission $commission
     * @param \Bianes\CommissionTask\Entity\Operation $operation
     * @param \Bianes\CommissionTask\Entity\User $user
     *
     * @return string
     */
    public function computeFee(Commission $commission, Operation $operation, User $user = null): string
    {
        $percentage = $commission->getPercentage() / 100;
        $commissionFee = bcmul((string)$percentage, (string)$operation->getAmount(), 2);

        if ($commission->getMaximum() > 0 && $commission->getMaximum() < $commissionFee) {
            return sprintf("%.2f", $commission->getMaximum());
        }

        return $commissionFee;
    }
}
