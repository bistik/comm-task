<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

use Bianes\CommissionTask\Service\Repository;

final class CashinOperation extends Operation
{
    /**
     * CashinOperation constructor.
     *
     * @param string $amount
     * @param string $date
     * @param string $currency
     */
    public function __construct(string $amount, string $date, string $currency)
    {
        parent::__construct($amount, $date, $currency);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Repository::CASH_IN;
    }
}
