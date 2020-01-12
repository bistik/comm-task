<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

abstract class Operation
{
    /** @var float */
    protected $amount;

    /** @var string */
    protected $currency;

    /** @var string */
    protected $date;

    /**
     * Operation constructor.
     *
     * @param string $amount
     * @param string $date
     * @param string $currency
     */
    public function __construct(string $amount, string $date, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;
}
