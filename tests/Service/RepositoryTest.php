<?php
declare(strict_types=1);

namespace Bianes\CommissionTask\Tests\Service;

use Bianes\CommissionTask\Entity\CashinCommission;
use Bianes\CommissionTask\Entity\CashinOperation;
use Bianes\CommissionTask\Entity\CashoutCommission;
use Bianes\CommissionTask\Entity\CashoutOperation;
use Bianes\CommissionTask\Entity\LegalUser;
use Bianes\CommissionTask\Entity\NaturalUser;
use Bianes\CommissionTask\Exception\InvalidUserTypeException;
use Bianes\CommissionTask\Service\Repository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bianes\CommissionTask\Service\Repository
 */
final class RepositoryTest extends TestCase
{
    /** @var \Bianes\CommissionTask\Service\Repository */
    protected $repository;

    protected function setUp()
    {
        $this->repository = new Repository();
    }

    /**
     * Should add a new commission object.
     *
     * @covers \Bianes\CommissionTask\Service\Repository::addCommission
     *
     * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
     * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
     * @throws \ReflectionException
     */
    public function testAddCommission()
    {
        $this->repository->addCommission(
            Repository::CASH_OUT,
            Repository::NATURAL,
            new CashinCommission(0.2)
        );

        $reflection = new \ReflectionObject($this->repository);
        $property = $reflection->getProperty('commissions');
        $property->setAccessible(true);
        $commission = $property->getValue($this->repository);
        $this->assertArrayHasKey(Repository::CASH_OUT, $commission);
        $this->assertArrayHasKey(Repository::NATURAL, $commission[Repository::CASH_OUT]);
        $this->assertInstanceOf(
            CashinCommission::class,
            $commission[Repository::CASH_OUT][Repository::NATURAL]
        );
    }

    /**
     * Should return correct user-type instance
     *
     * @covers \Bianes\CommissionTask\Service\Repository::createUser
     *
     * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
     */
    public function testCreateUser()
    {
        $this->assertInstanceOf(
            NaturalUser::class,
            $this->repository->createUser('natural', 1)
        );

        $this->assertInstanceOf(
            LegalUser::class,
            $this->repository->createUser('legal', 2)
        );
    }

    /**
     * @covers \Bianes\CommissionTask\Service\Repository::createOperation
     *
     * @throws \Bianes\CommissionTask\Exception\InvalidOperationTypeException
     */
    public function testCreateOperation()
    {
        $user = new NaturalUser(1);

        $this->assertInstanceOf(
            CashinOperation::class,
            $this->repository->createOperation($user, 'cash_in', '100', '2020-01-01', 'EUR')
        );
        $this->assertInstanceOf(
            CashoutOperation::class,
            $this->repository->createOperation($user,'cash_out', '100', '2020-01-01', 'EUR')
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetCommission()
    {
        $reflection = new \ReflectionObject($this->repository);
        $property = $reflection->getProperty('commissions');
        $property->setAccessible(true);
        $property->setValue(
            $this->repository,
            [Repository::CASH_OUT => [Repository::NATURAL => new CashoutCommission(1)]]
        );

        $this->assertInstanceOf(
            CashoutCommission::class,
            $this->repository->getCommission(
                new CashoutOperation('100', '2010-01-01', 'EUR'),
                new NaturalUser(1)
            )
        );

        $this->assertNull(
            $this->repository->getCommission(
                new CashoutOperation('100', '2010-01-01', 'EUR'),
                new LegalUser(1)
            )
        );
    }

    /**
     * @covers \Bianes\CommissionTask\Service\Repository::validateUserType
     *
     * @throws \Bianes\CommissionTask\Exception\InvalidUserTypeException
     */
    public function testInvalidUser()
    {
        $this->expectException(InvalidUserTypeException::class);

        $this->repository->createUser('unknown', 1);
    }
}
