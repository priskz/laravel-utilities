<?php namespace Priskz\LaravelUtilities\Alert;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register service(s).
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('laravel-utilities.alert', function($app)
		{
			return new Alert($app['session.store']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('laravel-utilities.alert');
	}
}