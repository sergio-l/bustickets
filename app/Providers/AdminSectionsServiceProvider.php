<?php

namespace App\Providers;

//use App\Form\Elements\MyElement;
use SleepingOwl\Admin\Contracts\Navigation\NavigationInterface;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use PackageManager;
use SleepingOwl\Admin\Contracts\Template\MetaInterface;
use SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface;

class AdminSectionsServiceProvider extends ServiceProvider
{

    protected $widgets = [
        \App\Admin\Widgets\NavigationUserBlock::class
    ];

    /**
     * @var array
     */
    protected $sections = [
        \App\Model\Bus::class => 'App\Admin\Http\Sections\Buses',
        \App\Model\Station::class => 'App\Admin\Http\Sections\Stations',
        \App\Model\Flight::class  => 'App\Admin\Http\Sections\Flights',
        \App\Model\FlightPrice::class  => 'App\Admin\Http\Sections\Prices',
        \App\Model\Role::class    => 'App\Admin\Http\Sections\Roles',
        \App\Model\User::class    => 'App\Admin\Http\Sections\Users',
        \App\Model\Permission::class => 'App\Admin\Http\Sections\Permission',
        \App\Model\Order::class  => 'App\Admin\Http\Sections\Orders',
        \App\Model\Page::class  => 'App\Admin\Http\Sections\Pages',
        \App\Model\Setting::class  => 'App\Admin\Http\Sections\Settings',
        \App\Model\UserHistory::class  => 'App\Admin\Http\Sections\UsersHistory',
        \App\Model\Statistic::class  => 'App\Admin\Http\Sections\Statistic',
        \App\Model\Driver::class  => 'App\Admin\Http\Sections\Drivers',
    ];

    /**
     * Register sections.
     *
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {

        $this->loadViewsFrom(base_path("app/admin/resources/views"), 'admin');
        $this->registerPolicies('App\\Admin\\Policies\\');

        $this->app->call([$this, 'registerRoutes']);
        $this->app->call([$this, 'registerNavigation']);
        parent::boot($admin);
        $this->app->call([$this, 'registerViews']);
        $this->app->call([$this, 'registerMediaPackages']);
    }

    /**
     * @param NavigationInterface $navigation
     */
    public function registerNavigation(NavigationInterface $navigation)
    {
        require base_path('app/Admin/navigation.php');
    }

    /**
     * @param Router $router
     */
    public function registerRoutes(Router $router)
    {
        $router->group(['prefix' => config('sleeping_owl.url_prefix'), 'middleware' => config('sleeping_owl.middleware')], function ($router) {
            require base_path('app/Admin/Http/routes.php');
        });
    }

    /**
     * @param WidgetsRegistryInterface $widgetsRegistry
     */
    public function registerViews(WidgetsRegistryInterface $widgetsRegistry)
    {
        foreach ($this->widgets as $widget) {
            $widgetsRegistry->registerWidget($widget);
        }
    }

    public function registerMediaPackages(MetaInterface $meta)
    {
        $packages = $meta->assets()->packageManager();
        require base_path('app/Admin/assets.php');
    }
}
