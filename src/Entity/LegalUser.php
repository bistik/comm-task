<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

use Bianes\CommissionTask\Service\Repository;

final class LegalUser extends User
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Repository::LEGAL;
    }
}
