<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App;
use Blade;
class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {

        Blade::directive('widget', function ($name) {
            return "<?php echo app('widget')->show($name); ?>";
        });

        $this->loadViewsFrom(app_path() .'/Widgets/view', 'Widgets');
    }

    public function register()
    {
        App::singleton('widget', function(){
            return new \App\Widgets\Widget();
        });
    }
}