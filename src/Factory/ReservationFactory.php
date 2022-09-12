<?php

namespace App\Factory;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Reservation>
 *
 * @method static Reservation|Proxy createOne(array $attributes = [])
 * @method static Reservation[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reservation|Proxy find(object|array|mixed $criteria)
 * @method static Reservation|Proxy findOrCreate(array $attributes)
 * @method static Reservation|Proxy first(string $sortedField = 'id')
 * @method static Reservation|Proxy last(string $sortedField = 'id')
 * @method static Reservation|Proxy random(array $attributes = [])
 * @method static Reservation|Proxy randomOrCreate(array $attributes = [])
 * @method static Reservation[]|Proxy[] all()
 * @method static Reservation[]|Proxy[] findBy(array $attributes)
 * @method static Reservation[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Reservation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ReservationRepository|RepositoryProxy repository()
 * @method Reservation|Proxy create(array|callable $attributes = [])
 */
final class ReservationFactory extends ModelFactory
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
            'date' => self::faker()->datetime(),
            'status' => [],
            'reservationStatus' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Reservation $reservation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reservation::class;
    }
}
