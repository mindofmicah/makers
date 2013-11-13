<?php namespace mindofmicah\Makers;

use mindofmicah\Makers\Commands;

use Illuminate\Support\ServiceProvider;

class MakersServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['wd.validate'] = new Commands\ValidatorCommand();
        $this->commands('wd.validate');
	}
}
