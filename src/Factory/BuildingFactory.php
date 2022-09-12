<?php

namespace App\Factory;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Building>
 *
 * @method static Building|Proxy createOne(array $attributes = [])
 * @method static Building[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Building|Proxy find(object|array|mixed $criteria)
 * @method static Building|Proxy findOrCreate(array $attributes)
 * @method static Building|Proxy first(string $sortedField = 'id')
 * @method static Building|Proxy last(string $sortedField = 'id')
 * @method static Building|Proxy random(array $attributes = [])
 * @method static Building|Proxy randomOrCreate(array $attributes = [])
 * @method static Building[]|Proxy[] all()
 * @method static Building[]|Proxy[] findBy(array $attributes)
 * @method static Building[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Building[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static BuildingRepository|RepositoryProxy repository()
 * @method Building|Proxy create(array|callable $attributes = [])
 */
final class BuildingFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->text(),
            'address' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Building $building): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Building::class;
    }
}
