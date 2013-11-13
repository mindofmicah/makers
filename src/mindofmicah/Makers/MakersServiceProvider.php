<?php namespace mindofmicah\Makers;

use mindofmicah\Makers\Commands;

use Way\Generators\Cache;
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
        new Commands\ValidatorCommand();

        /*$this->commands(
			'generate.model',
			'generate.controller',
			'generate.test',
			'generate.scaffold',
			'generate.resource',
			'generate.view',
			'generate.migration',
			'generate.seed',
			'generate.form',
			'generate.pivot'
		);*/
	}


}
