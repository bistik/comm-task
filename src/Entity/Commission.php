<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

abstract class Commission
{
    /** @var float */
    private $maximum;

    /** @var float */
    private $minimum;

    /** @var float */
    private $percentage;

    /**
     * Commission constructor.
     *
     * @param float $minimum
     * @param float $percentage
     * @param float $maximum
     */
    public function __construct(float $minimum, float $percentage, float $maximum)
    {
        $this->maximum = $maximum;
        $this->minimum = $minimum;
        $this->percentage = $percentage;
    }

    /**
     * @return float
     */
    public function getMaximum(): float
    {
        return $this->maximum;
    }

    /**
     * @return float
     */
    public function getMinimum(): float
    {
        return $this->minimum;
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
