<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager;

use Khakimjanovich\BaytApiManager\Commands\MigrateCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class BaytApiManagerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bayt-api-manager')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_bayt_api_manager_table')
            ->hasCommand(MigrateCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->copyAndRegisterServiceProviderInApp();
            });

        $this->app->singleton('bayt-api-manager', fn () => new BaytApiManager());

    }
}
