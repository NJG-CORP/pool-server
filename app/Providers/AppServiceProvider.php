<?php

namespace App\Providers;

use App\Http\Responder;
use App\Services\CityService;
use App\Services\ClubsService;
use App\Services\FavouriteService;
use App\Services\ImageService;
use App\Services\PlayerService;
use App\Services\RatingService;
use App\Services\TaxonomyService;
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
        $this->app->singleton(PlayerService::class, function(){
            return new PlayerService();
        });
        $this->app->singleton(ClubsService::class, function(){
            return new ClubsService();
        });
        $this->app->singleton(FavouriteService::class, function(){
            return new FavouriteService();
        });
        $this->app->singleton(RatingService::class, function(){
            return new RatingService();
        });
        $this->app->singleton(CityService::class, function(){
            return new CityService();
        });
        $this->app->singleton(ImageService::class, function(){
           return new ImageService();
        });
        $this->app->singleton(TaxonomyService::class, function(){
            return new TaxonomyService();
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
