<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    

    public function boot()
    {
        Blade::directive('formatNumber', function ($number) {
            return "<?php echo number_format(($number >= 1000000) ? $number / 1000000 : (($number >= 1000) ? $number / 1000 : $number), 1) . (($number >= 1000000) ? 'M' : (($number >= 1000) ? 'K' : '')); ?>";
        });
    }

}
 