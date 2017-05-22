<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/22/17
 * Time: 3:14 PM
 */
namespace wan\DisID;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application as LaravelApp;

Class CDisIDOpServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->setupConfig();
	}

	public function setupConfig()
	{
		$config = realpath( __DIR__ . '/../config/config.php' );
		if ( $this->app instanceof LaravelApp && $this->app->runningInConsole() )
		{
			$this->publishes( [
				$config => config_path( 'wan-disid.php' ),
			] );
		}

		$this->mergeConfigFrom( $config, 'wan-disid' );
	}

	public function register()
	{
		$this->app->bind( 'DisID', function( $app ) {
			$oDisID = new CDisID();

			$oDisID->setMNMSBaseTime( $app->config->get( 'wan-disid.ms_base_time' ) )
				->setMNMSTimeBitLen( $app->config->get( 'wan-disid.ms_time_bit_len' ) )
				->setMNOpIDBitLen( $app->config->get( 'wan-disid.op_id_bit_len' ) )
				->setMNOpID( $app->config->get( 'wan-disid.op_id' ) )
				->setMNMrIDBitLen( $app->config->get( 'wan-disid.mr_id_bit_len' ) )
				->setMNMrID( $app->config->get( 'wan-disid.mr_id' ) )
				->setMNServerIDBitLen( $app->config->get( 'wan-disid.server_id_bit_len' ) )
				->setMNServerID( $app->config->get( 'wan-disid.server_id' ) )
				->setMNLeftBitLen( $app->config->get( 'wan-disid.left_bit_len' ) )
				->setMNLeft( $app->config->get( 'wan-disid.left' ) )
				->setMNInMSSNBitLen( $app->config->get( 'wan-disid.in_ms_sn_bit_len' ) );

			$oDisIDOp = new CDisIDOp( $oDisID );

			return $oDisIDOp;
		} );
	}

	public function provides()
	{
		return [
			'wan.DisID'
		];
	}
}