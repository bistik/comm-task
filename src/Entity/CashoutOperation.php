<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

use Bianes\CommissionTask\Service\Repository;

final class CashoutOperation extends Operation
{
    /**
     * Possible be used for tracking weekly operations
     * @var int
     */
    private $weekNumber;

    /**
     * CashoutOperation constructor.
     *
     * @param string $amount
     * @param string $date
     * @param string $currency
     */
    public function __construct(string $amount, string $date, string $currency)
    {
        parent::__construct($amount, $date, $currency);

        $this->setWeekNumber();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Repository::CASH_OUT;
    }

    /**
     * @return int
     */
    public function getWeekNumber(): int
    {
        return $this->weekNumber;
    }

    /**
     * Set the operations week_number by its date.
     *
     * @return void
     */
    public function setWeekNumber()
    {
        $this->weekNumber = date('W', strtotime($this->date));
    }
}
