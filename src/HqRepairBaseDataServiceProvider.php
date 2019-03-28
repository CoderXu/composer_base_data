<?php
/**
 * Created by PhpStorm.
 * User: coderxu
 * Date: 2019/3/27
 * Time: 6:08 PM
 */

namespace HqRepair\BaseData;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HqRepairBaseDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
    }

    /**
     * Register the Horizon routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('horizon.uri', 'horizon'),
            'namespace' => 'Laravel\Horizon\Http\Controllers',
            'middleware' => config('horizon.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }
}