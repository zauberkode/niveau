<?php

namespace App\Factory;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Employee>
 *
 * @method        Employee|Proxy                     create(array|callable $attributes = [])
 * @method static Employee|Proxy                     createOne(array $attributes = [])
 * @method static Employee|Proxy                     find(object|array|mixed $criteria)
 * @method static Employee|Proxy                     findOrCreate(array $attributes)
 * @method static Employee|Proxy                     first(string $sortedField = 'id')
 * @method static Employee|Proxy                     last(string $sortedField = 'id')
 * @method static Employee|Proxy                     random(array $attributes = [])
 * @method static Employee|Proxy                     randomOrCreate(array $attributes = [])
 * @method static EmployeeRepository|RepositoryProxy repository()
 * @method static Employee[]|Proxy[]                 all()
 * @method static Employee[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Employee[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Employee[]|Proxy[]                 findBy(array $attributes)
 * @method static Employee[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Employee[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class EmployeeFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->email(),
            'firstName' => self::faker()->firstName(),
            'joinedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'lastName' => self::faker()->lastName(),
            'salary' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Employee $employee): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Employee::class;
    }
}
