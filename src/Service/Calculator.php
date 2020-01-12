<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Service;

use Bianes\CommissionTask\Entity\Commission;
use Bianes\CommissionTask\Entity\Operation;
use Bianes\CommissionTask\Entity\User;

interface Calculator
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
    public function computeFee(Commission $commission, Operation $operation, User $user = null): string;
}
