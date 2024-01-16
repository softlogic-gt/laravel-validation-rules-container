<?php
namespace SoftlogicGT\ValidationRulesContainer;

use Illuminate\Support\ServiceProvider;

class ValidationRulesContainerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/validationRulesContainer'),
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/', 'validationRulesContainer');
    }
}
