<?php

namespace Code95\MediaLibrary;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Code95\MediaLibrary\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->addDynamciRelation();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'media-library');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    protected function addDynamciRelation()
    {
        $path = app_path();
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $modelName = substr($result, 0, -4); //User
            $pathName =  "App\\" . $modelName; //App\Models\User
            $finalName = \Str::plural(strtolower($modelName)); //users

            CustomMedia::addDynamicRelation($finalName, function (CustomMedia $model) use ($pathName) {
                return $model->morphedByMany($pathName, 'attachedmediable', 'attachedmediables', 'attached_media_id')->withTimestamps();
            });
        }
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/media-library')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
