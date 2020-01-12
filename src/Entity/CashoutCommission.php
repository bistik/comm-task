<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

final class CashoutCommission extends Commission
{
    /**
     * CashoutCommission constructor.
     *
     * @param float $percentage
     * @param float $minimum
     * @param float $maximum
     */
    public function __construct(float $percentage, float $minimum = 0, float $maximum = 0)
    {
        parent::__construct($minimum, $percentage, $maximum);
    }
}
