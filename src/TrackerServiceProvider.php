<?php

namespace Anon\Seat\Tracker;

use Anon\Seat\Tracker\Commands\Tracker\Poll\HighFrequency;
use Anon\Seat\Tracker\Commands\Tracker\Poll\Normal;
use Anon\Seat\Tracker\database\seeders\TrackerScheduleSeeder;
use Anon\Seat\Tracker\Http\DataTables\Tracker\CharacterDataTableOverride;
use Anon\Seat\Tracker\Jobs\LocationWithModified;
use Anon\Seat\Tracker\Jobs\ShipWithModified;
use Anon\Seat\Tracker\Models\CharacterLocationWithModified;
use Anon\Seat\Tracker\Models\CharacterShipWithModified;
use Illuminate\Foundation\AliasLoader;
use Seat\Eveapi\Jobs\Location\Character\Location;
use Seat\Eveapi\Jobs\Location\Character\Ship;
use Seat\Eveapi\Models\Location\CharacterLocation;
use Seat\Eveapi\Models\Location\CharacterShip;
use Seat\Services\AbstractSeatPlugin;
use Seat\Web\Http\DataTables\Character\CharacterDataTable;

class TrackerServiceProvider extends AbstractSeatPlugin
{
    public function getName(): string
    {
        return "Tracker";
    }

    public function getPackageRepositoryUrl(): string
    {
        return "https://github.com/example/example";
    }

    public function getPackagistPackageName(): string
    {
        return "tracker";
    }

    public function getPackagistVendorName(): string
    {
        return "anon";
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
        $this->registerDatabaseSeeders(TrackerScheduleSeeder::class);

        $loader = AliasLoader::getInstance();
        $loader->alias(CharacterDataTable::class, CharacterDataTableOverride::class);
        $loader->alias(Location::class, LocationWithModified::class);
        $loader->alias(CharacterLocation::class, CharacterLocationWithModified::class);
        $loader->alias(Ship::class, ShipWithModified::class);
        $loader->alias(CharacterShip::class, CharacterShipWithModified::class);
    }

    public function boot(): void
    {
        $this->addRoutes();
        $this->addViews();
        $this->addMigrations();
        $this->addCommands();
    }

    private function addRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    private function addViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'tracker');
    }

    private function addMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    private function addCommands()
    {
        $this->commands([
            HighFrequency::class,
            Normal::class,
        ]);
    }
}
