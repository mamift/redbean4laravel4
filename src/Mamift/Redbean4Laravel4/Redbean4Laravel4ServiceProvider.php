<?php 

namespace Mamift\Redbean4Laravel4;

// include 'rb.php';

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades as Laravel;

class Redbean4Laravel4ServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('mamift/redbean4-laravel4');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//Get DB configs from app/config/database.php
		$default = Laravel\Config::get('database.default');
		$connections = Laravel\Config::get('database.connections');

		$db_name = $connections[$default]['database'];
		$db_driver = $connections[$default]['driver'];

		//Run the R::setup command based on db_type
		if ($default != 'sqlite') {
			$db_host = $connections[$default]['host'];
			$db_user = $connections[$default]['username']; 
			$db_pass = $connections[$default]['password'];
			$conn_string = $db_driver.':host='.$db_host.';dbname='.$db_name;

			try {
				\R::setup($conn_string, $db_user, $db_pass);
			} catch (\RedbeanPHP\RedException Exception $e) {
				return;
			}			
		} else {
			$conn_string = $db_driver.':'.$db_name;
            
            try {
				\R::setup($conn_string);
            } catch (\RedbeanPHP\RedException Exception $e) {
            	return;
            }            
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
