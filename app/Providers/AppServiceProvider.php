<?php

namespace App\Providers;

use App\Http\Responder;
use App\Services\PlayersService;
use App\Services\UserService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->app->singleton(Responder::class, function(){
            return new Responder();
        });
        $this->app->singleton(UserService::class, function(){
            return new UserService();
        });
        $this->app->singleton(PlayersService::class, function(){
            return new PlayersService();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
