<?php
/**
 * User: Mani Wang
 * Date: 8/13/15
 * Time: 9:16 PM
 * Email: mani@forone.co
 */

namespace Forone\Providers;

use Illuminate\Support\ServiceProvider;
use Zizaco\Entrust\EntrustServiceProvider;

class ForoneServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!config('forone.disable_routes')) {
            require __DIR__ . '/../routes.php';
        }
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'forone');
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'forone');
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'forone');
        $this->publishResources();
        $this->publishMigrations();
        $this->setLocale();
        $this->app['events']->fire('admin.ready');
        $this->registerProvider();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerAlias();
        $this->registerMiddleware();
        $this->app->bind(\Illuminate\Contracts\Auth\Registrar::class, \Forone\Services\Registrar::class);
    }

    private function registerCommands()
    {
        $this->commands([
            \Forone\Console\ClearDatabase::class,
            \Forone\Console\InitCommand::class,
            \Forone\Console\Upgrade::class,
            \Forone\Console\Backup::class,
            \Forone\Console\CopyForone::class
        ]);
    }

    private function registerProvider()
    {
        $this->app->register(\Illuminate\Translation\TranslationServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Orangehill\Iseed\IseedServiceProvider::class);

        $this->app->register(EntrustServiceProvider::class);
        $this->app->register(ForoneFormServiceProvider::class);
        $this->app->register(ForoneHtmlServiceProvider::class);
        $this->app->register(ForoneValidatorProvider::class);
        $this->app->register(QiniuUploadProvider::class);
    }

    private function registerAlias()
    {
        $this->app->alias('Form',\Illuminate\Html\FormFacade::class);
        $this->app->alias('Html',\Illuminate\Html\HtmlFacade::class);
        $this->app->alias('Entrust', \Zizaco\Entrust\EntrustFacade::class);
    }

    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        // publish config
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('forone.php'),]);

        // publish assets
        $this->publishes([__DIR__ . '/../../../public' => public_path('vendor/forone'),], 'public');
    }

    /**
     * Publish migration file.
     */
    private function publishMigrations()
    {
        $this->publishes([__DIR__ . '/../../migrations/' => base_path('database/migrations')], 'migrations');
    }

    /**
     * Sets the locale if it exists in the session and also exists in the locales option
     *
     * @return void
     */
    public function setLocale()
    {
        if ($locale = $this->app->session->get('admin_locale'))
        {
            $this->app->setLocale($locale);
        }
    }

    private function registerMiddleware()
    {
        $this->app['router']->middleware('role', \Forone\Middleware\EntrustRole::class);
        $this->app['router']->middleware('ability', \Forone\Middleware\EntrustAbility::class);
        $this->app['router']->middleware('permission', \Forone\Middleware\EntrustPermission::class);
        $this->app['router']->middleware('admin.auth', \Forone\Middleware\Authenticate::class);
        $this->app['router']->middleware('admin.guest', \Forone\Middleware\RedirectIfAuthenticated::class);
    }
}
