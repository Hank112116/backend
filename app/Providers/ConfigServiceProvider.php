<?php namespace Backend\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * Overwrite any vendor / package configuration.
	 *
	 * This service provider is intended to provide a convenient location for you
	 * to overwrite any "vendor" or package configuration that you may want to
	 * modify before the application handles the incoming request / command.
	 *
	 * @return void
	 */
	public function register()
	{
		config([
			//
		]);


        Blade::extend(function ($view, $compiler) {
            $pattern = $compiler->createMatcher('cssLoader');
            return preg_replace($pattern, '$1<?php echo \'<link rel="stylesheet" href="/css/\' . ($2)  . \'.css">\' ?>', $view);
        });

        Blade::extend(function ($view, $compiler) {
            $pattern = $compiler->createMatcher('jsLoader');
            return preg_replace($pattern, '$1<?php echo \'<script src="/js/\' . ($2)  . \'.js"></script>\' ?>', $view);
        });

    }

}
