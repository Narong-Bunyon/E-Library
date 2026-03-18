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
    public function boot(): void
    {
        // Register global helper function for category colors
        Blade::directive('categoryColor', function ($categoryName) {
            return "<?php echo App\Helpers\ViewHelpers::getCategoryColor({$categoryName}); ?>";
        });
    }
}
