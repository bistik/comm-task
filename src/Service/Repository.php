<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Service;

use Bianes\CommissionTask\Entity\CashinOperation;
use Bianes\CommissionTask\Entity\CashoutOperation;
use Bianes\CommissionTask\Entity\Commission;
use Bianes\CommissionTask\Entity\LegalUser;
use Bianes\CommissionTask\Entity\NaturalUser;
use Bianes\CommissionTask\Entity\Operation;
use Bianes\CommissionTask\Entity\User;
use Bianes\CommissionTask\Exception\InvalidOperationTypeException;
use Bianes\CommissionTask\Exception\InvalidUserTypeException;

final class Repository
{
    const CASH_IN = 'cash_in';
    const CASH_OUT = 'cash_out';
    const LEGAL = 'legal';
    const NATURAL = 'natural';

    private static $operationTypes = [
        self::CASH_IN => CashinOperation::class,
        self::CASH_OUT => CashoutOperation::class
    ];

    private static $userTypes = [
        self::LEGAL => LegalUser::class,
        self::NATURAL => NaturalUser::class
    ];

    /** @var mixed[] */
    private $commissions;

    /** @var \Bianes\CommissionTask\Entity\Operation[] */
    private $operations;

    /** @var \Bianes\CommissionTask\Entity\User[] */
    private $users;

    public function __construct()
    {
        $this->commissions = [];
        $this->operations = [];
        $this->users = [];
    }

    /**
     * @param string $operationType
     * @param string $userType
     * @param \Bianes\CommissionTask\Entity\Commission $commission
     *
     * @return void
     *
     * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
     * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
     */
    public function addCommission(string $operationType, string $userType, Commission $commission)
    {
        $this->validateOperationType($operationType);
        $this->validateUserType($userType);

        $this->commissions[$operationType][$userType] = $commission;
    }

    /**
     * @param \Bianes\CommissionTask\Entity\User $user
     * @param string $type
     * @param string $amount
     * @param string $date
     * @param string $currency
     *
     * @return \Bianes\CommissionTask\Entity\Operation
     * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
     */
    public function createOperation(User $user, string $type, string $amount, string $date, string $currency): Operation
    {
        $this->validateOperationType($type);

        /** @var \Bianes\CommissionTask\Entity\Operation $operation */
        $operation = new self::$operationTypes[$type]($amount, $date, $currency);
        $this->operations[$user->getId()][] = $operation;

        return $operation;
    }

    /**
     * @param string $type
     * @param int $id
     *
     * @return \Bianes\CommissionTask\Entity\User
     *
     * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
     */
    public function createUser(string $type, int $id): User
    {
        $this->validateUserType($type);

        if ($this->findUser($id) === null) {
            $this->users[$id] = new self::$userTypes[$type]($id);
            $this->operations[$id] = [];
        }

        return $this->users[$id];
    }

    /**
     * @param \Bianes\CommissionTask\Entity\Operation $operation
     * @param \Bianes\CommissionTask\Entity\User $user
     *
     * @return \Bianes\CommissionTask\Entity\Commission|null
     */
    public function getCommission(Operation $operation, User $user): ?Commission
    {
        $operationCommissions = $this->commissions[$operation->getType()] ?? null;

        if ($operationCommissions === null) {
            return null;
        }

        return $operationCommissions[$user->getType()] ?? null;
    }

    /**
     * @param int $id
     *
     * @return \Bianes\CommissionTask\Entity\User|null
     */
    private function findUser(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    /**
     * @param string $type
     *
     * @return bool
     * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
     */
    private function validateOperationType(string $type): bool
    {
        if (!array_key_exists($type, self::$operationTypes)) {
            throw new InvalidOperationTypeException(sprintf("Invalid operation type '%s", $type));
        }

        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
     */
    private function validateUserType(string $type): bool
    {
        if (!array_key_exists($type, self::$userTypes)) {
            throw new InvalidUserTypeException(sprintf("Invalid user type '%s'", $type));
        }

        return true;
    }
}
