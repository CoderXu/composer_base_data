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
    protected $namespace = 'App\Http\Controllers';

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
            'prefix' => 'api',
            'namespace' => $this->namespace,
            'middleware' => 'api',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }
}