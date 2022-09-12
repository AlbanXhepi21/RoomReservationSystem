<?php

namespace App\Factory;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Room>
 *
 * @method static Room|Proxy createOne(array $attributes = [])
 * @method static Room[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Room|Proxy find(object|array|mixed $criteria)
 * @method static Room|Proxy findOrCreate(array $attributes)
 * @method static Room|Proxy first(string $sortedField = 'id')
 * @method static Room|Proxy last(string $sortedField = 'id')
 * @method static Room|Proxy random(array $attributes = [])
 * @method static Room|Proxy randomOrCreate(array $attributes = [])
 * @method static Room[]|Proxy[] all()
 * @method static Room[]|Proxy[] findBy(array $attributes)
 * @method static Room[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Room[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RoomRepository|RepositoryProxy repository()
 * @method Room|Proxy create(array|callable $attributes = [])
 */
final class RoomFactory extends ModelFactory
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
            'capacity' => self::faker()->randomNumber(),
            'status' => [],
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Room $room): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Room::class;
    }
}
