<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

final class CashinCommission extends Commission
{
    /**
     * CashinCommission constructor.
     *
     * @param float $percentage
     * @param float $maximum
     */
    public function __construct(float $percentage, float $maximum = 5.0)
    {
        parent::__construct(0, $percentage, $maximum);
    }
}
