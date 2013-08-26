<?php

/**
* @class  FW
* @file   Tipui.php
* @brief  Engine's start.
* @date   2013-06-21 02:00:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-07-08 02:09:00
*/

namespace Tipui;

class FW
{

	public static $core_cached_data;
	public static $core_data_storage_mode;

    public function __construct()
    {
		// load once time
		if( !defined( 'TIPUI_PATH' ) )
		{

			/**
			* Defining constants
			*/
			$this -> DefineConstants();

			/**
			* Starting the Core
			*/
			require_once( TIPUI_PATH . 'Core.php' );
			$c = new Core;

			/**
			* Returns method of core data storage
			* array, session, sqlite, etc
			*/

			/**
			* Core data storage mode
			* array, session, sqlite, etc
			*/
			$this -> SetCoreDataCache( $c, 'GetCoreStorageMode' );

			/**
			* Storing general enviroment settings
			*/
			$this -> SetCoreDataCache( $c, 'GetENV' );

			/**
			* Stores the SAPI mode (CLI or HTTP)
			*/
			$this -> SetCoreDataCache( $c, 'IsCliMode' );

			/**
			* Loads autoloading and start it
			*/
			$c -> Autoloader();

			/**
			* Stores Core Request method result to cache
			* Requires: GetENV, IsCliMode Core methods and Autoloader class
			*/
			$this -> SetCoreDataCache( $c, 'Request' );

			/**
			* Stores Core Request method result to cache
			* Requires: GetENV, IsCliMode Core methods and Autoloader class
			*/
			$this -> SetCoreDataCache( $c, 'Browse' );

			/**
			* Debug purposes
			*/
			//print_r( $this -> GetCoreDataCache( 'Request' ) ); exit;

			/**
			* Stores Core routing result to cache
			*/
			$this -> SetCoreDataCache( $c, 'Routing' );
			$module = $this -> GetCoreDataCache( 'Routing' );

			/**
			* Debug purposes
			* Displays module or routing (alias) settings.
			*/
			//print_r( $module ); exit;

			/**
			* Loading the module
			*/
			$clss = '\Tipui\App\Model\\' . $module['class'];
			$m = new $clss;

			/**
			* Executing module methods if exists (Form and Prepare)
			*/
			method_exists( $clss, 'Form' )     ? $m -> Form()     : '';
			method_exists( $clss, 'Prepare' )  ? $m -> Prepare()  : '';

			/**
			* Rendering template if exists View method
			*/
			if( method_exists( $clss, 'View' ) )
			{

				// Get all core config settings
				$core_conf = $c -> GetEnv();

				/**
				* Retrieving template settings overriding, if exists
				*/
				if( method_exists( $clss, 'Template' ) )
				{
					$module_template = $m -> Template();
				}

				/**
				* Retrieving header settings method, if exists
				*/
				if( method_exists( $clss, 'Header' ) )
				{
					$module_header = $m -> Header();
					\Tipui\Builtin\Libs\Header::HTTPStatus( $module_header['http_status'] );
					unset( $module_header );
				}

				// Alternative for [code]$c -> GetEnv()[/code]
				//$core_conf = $this -> GetCoreDataCache( 'GetENV' );

				// Call Template library
				/**
				* Defined in Routing Modules file, however, not implemented
				* Must decide where will have priority. The Routing alias module settings or the Template method of module.
				$module['force_language']
				$module['default_language']
				*/
				$t = new \Tipui\Builtin\Libs\Template( TIPUI_APP_PATH . $core_conf['TEMPLATES']['FOLDER'] . DIRECTORY_SEPARATOR . ( !isset( $module_template['language'] ) ? $core_conf['INTERFACE']['DEFAULT_LANGUAGE'] : $module_template['language'] ) . DIRECTORY_SEPARATOR, $core_conf['TEMPLATES']['TAG'], $core_conf['TEMPLATES']['OUTPUT'] );

				//Output's content-type
				header( 'Content-Type: ' . ( !isset( $module_template['content_type'] ) ? $core_conf['TEMPLATES']['DEFAULT_CONTENT_TYPE'] : $module_template['content_type'] ) . '; charset=' . ( !isset( $module_template['charset'] ) ? $core_conf['BOOTSTRAP']['CHARSET'] : $module_template['charset'] ) );

				// Rendering and dispatching the template
				$t -> Compile( $m -> View(), ( !isset( $module_template['dir'] ) ? false : $module_template['dir'] ), ( !isset( $module_template['file'] ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $module['class'] ) . $core_conf['TEMPLATES']['DEFAULT_FILE_EXTENSION'] : $module_template['file'] ) );
				unset( $t, $core_conf );
			}

			unset( $module, $m, $c );

			/**
			* Debug purposes
			* Texting usage of library
			*/
			//$c = new Builtin\Libs\Strings;
			//echo $c -> RandomChar(); exit;
			//echo Builtin\Libs\Strings::StrLen( 'foo' ); exit;

		}

		return null;
	}

	/**
	* initiates general constants
	*/
    private function DefineConstants()
    {

		// Framework base path
		define( 'TIPUI_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

		// Builtin base path
		//define( 'TIPUI_BUILTIN_PATH', TIPUI_PATH . 'Builtin' . DIRECTORY_SEPARATOR );

		// public path, where the index.php is located
		define( 'TIPUI_APP_PUBLIC_PATH', dirname( $_SERVER['SCRIPT_FILENAME'] ) . DIRECTORY_SEPARATOR );

		/**
		* @brief config files, models, templates, plugins, helpers, extra libs
		* Can be defined on public/index.php file
		* Example: [code]define( 'TIPUI_APP_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR );[/code]
		*/
		if( !defined( 'TIPUI_APP_PATH' ) )
		{

			/**
			* @brief Auto check if exists the path, generaly 1 folder above
			* Example: 
			* /public/index.php
			* /app/ (this is the default recommended location and folder name)
			*/
			$app_path = dirname( $_SERVER['SCRIPT_FILENAME'] ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;

			if( is_dir( $app_path ) )
			{
				//echo time();
				define( 'TIPUI_APP_PATH', $app_path );
			}else{
				throw new \Exception('TIPUI_APP_PATH not exists.');
			}

			unset( $app_path );

		}

		return null;
    }

	/**
	* Retrieves the cached Core data
	*/
	public static function GetCoreDataCache( $method = false, $instance = false )
	{

		if( $method )
		{

			if( isset( self::$core_cached_data[$method] ) )
			{

				if( !$instance )
				{

					return self::$core_cached_data[$method];

				}else{

					if( isset( self::$core_cached_data[$method][$instance] ) )
					{

						return self::$core_cached_data[$method][$instance];

					}else{

						throw new \Exception('Instance "' . $instance . '" not found for method "' . $method . '".');

					}

				}

			}else{

				throw new \Exception('Method "' . $method . '" not found.');

			}

		}else{

			return self::$core_cached_data;

		}

	}

	/**
	* Caching Core data
	*/
	private function SetCoreDataCache( $core, $method )
	{
		if( $method == 'GetCoreStorageMode' ){
		{
			
		}
		self::$core_cached_data[$method] = $core -> $method();
		return null;

	}

}
?>