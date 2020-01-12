<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Entity;

use Bianes\CommissionTask\Service\Repository;

final class NaturalUser extends User
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Repository::NATURAL;
    }
}
