<?php

/**
* @file   Start.php
* @brief  Engine's start in procedural style.
* @date   2013-09-08 17:10:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-11-16 00:13:00
*
* Git: https://github.com/tipui/Tipui-PHP
*/

/**
* [important and insights]
 - Form validation lib
 - [optional] Module and Template file with same name of existing folder will search folder/index.php and folder/index.html respectivelly if files not exists in the indicated path.
 - DB lib
 - Mail lib
 - Benchmark lib
 - Factory classes: Test instances performance.
 - [x]Check if user cookies are enabled. If not enabled or fails, then use session.
 - Cookie data version. Will be useful for cases when cookie or session structures was modified.
 - sys_getloadavg php function implementation
 - Deny access to Core instance from not allowed scripts.
*/

use Tipui\Builtin\Libs as Libs;

// Run once time
if( !defined( 'TIPUI_PATH' ) )
{

	/**
	* Defining constants
	*/
	// Framework version
	define( 'TIPUI_VERSION', '1.0' );

	// Framework base path
	define( 'TIPUI_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

	// public path, where the index.php is located
	define( 'TIPUI_APP_PUBLIC_PATH', dirname( $_SERVER['SCRIPT_FILENAME'] ) . DIRECTORY_SEPARATOR );

	// PHP scripts files name extension
	define( 'TIPUI_CORE_ENV_FILE_EXTENSION', '.php' );

	// App folder name
	define( 'TIPUI_APP_FOLDER_NAME', 'app' );

	// Override folder name
	define( 'TIPUI_FOLDER_OVERRIDE', 'Override' );

	/**
	* @brief config files, models, templates, plugins, helpers, extra libs
	* Can be defined on public/index.php file
	* Example: [code]define( 'TIPUI_APP_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . TIPUI_APP_FOLDER_NAME . DIRECTORY_SEPARATOR );[/code]
	*/
	if( !defined( 'TIPUI_APP_PATH' ) )
	{
		/**
		* @brief Auto check if exists the path, generaly 1 folder above
		* Example: 
		* /public/index.php
		* /app/ (default recommended location and folder name)
		*/
		$app_path = dirname( $_SERVER['SCRIPT_FILENAME'] ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . TIPUI_APP_FOLDER_NAME . DIRECTORY_SEPARATOR;

		if( is_dir( $app_path ) )
		{
			//echo time();
			define( 'TIPUI_APP_PATH', $app_path );
		}else{
			throw new \Exception('TIPUI_APP_PATH not exists.');
		}

		unset( $app_path );
	}

	/**
	* Tipui Core
	*/

	/**
	* Path to overriding Core file.
	*/
	$file = TIPUI_APP_PATH . TIPUI_FOLDER_OVERRIDE . DIRECTORY_SEPARATOR . 'Core' . TIPUI_CORE_ENV_FILE_EXTENSION;

	/**
	* Check if override file exists.
	*/
	if( file_exists( $file ) )
	{
		require_once( $file );
	}else{
		/**
		* Use default builtin class.
		*/
		require_once( TIPUI_PATH . 'Core' . TIPUI_CORE_ENV_FILE_EXTENSION );
	}

	unset( $file );


	/**
	* Instantiates the Tipui Core class.
	*/
	$c = new \Tipui\Core;

	/**
	* Check if running from CLI (command line) or HTTP
	* For retrieve, run the method IsCliMode()
	*/
	$c -> CheckCliMode();

	/**
	* Starts autoloader
	*/
	$c -> Autoloader();

	/**
	* Loads settings
	*/
	$c -> LoadSettings();

	/**
	* Get environment settings
	*/
	$env_bootstrap = $c -> GetEnv( 'BOOTSTRAP' );
	$env_templates = $c -> GetEnv( 'TEMPLATES' );

	/**
	* Debug purposes
	*/
	//print_r($env_bootstrap); exit;
	//print_r($env_templates); exit;

	/**
	* Stores the SAPI mode (CLI or HTTP)
	*/
	//$c -> IsCliMode();
	$c -> SetMethodDataCache( 'IsCliMode' );

	/**
	* Debug purposes
	* Retrieves Core request cached data.
	*/
	//$is_cli_mode = $c -> GetMethodDataCache( 'IsCliMode' );
	//var_dump( $is_cli_mode ); exit;

	/**
	* From this point, all methods of Core class requires Core::GetENV, Core::IsCliMode and Autoloader.
	*/

	/**
	* Debug purposes
	* Retrieves Core request cached data.
	*/
	//$request = $c -> GetMethodDataCache( 'Request' );
	//print_r( $request ); exit;

	/**
	* Stores Core routing result to cache.
	*/
	//$c -> Routing();
	$c -> SetMethodDataCache( 'Routing' );

	/**
	* Retrieves Core routing cached data.
	*/
	$module = $c -> GetMethodDataCache( 'Routing' );

	/**
	* Debug purposes
	* Displays module or routing (alias) settings.
	*/
	//print_r( $module ); exit;

	/**
	* Loading Model layer
	*/
	$clss = '\Tipui\\' . TIPUI_APP_FOLDER_NAME . '\Model\\' . $module['class'];
	$m = new $clss;

	/**
	* Call module methods if exists (Form and Prepare)
	*/
	method_exists( $clss, 'Form' )     ? $m -> Form()     : '';
	method_exists( $clss, 'Prepare' )  ? $m -> Prepare()  : '';

	/**
	* Rendering template if exists View method
	*/
	if( method_exists( $clss, 'View' ) )
	{

		/**
		* Handles data for model cache
		*/
		$model_cache = null;

		/**
		* Retrieving template settings overriding, if exists
		*/
		if( method_exists( $clss, 'Template' ) )
		{
			$model_cache['Template'] = $m -> Template();
		}

		/**
		* Retrieving header settings method, if exists
		*/
		if( method_exists( $clss, 'Header' ) )
		{
			$model_cache['Header'] = $m -> Header();
			Libs\Header::HTTPStatus( $model_cache['Header']['http_status'] );
		}

		/**
		* Dwebug purposes
		*/
		//print_r( $model_cache ); exit;

		/**
		* Stores cache if $model_cache is not empty
		*/

		/**
		* Get environment settings
		*/
		$env_modules = $c -> GetEnv( 'MODULES' );

		/**
		* Debug purposes
		*/
		//echo $env_modules['METHODS_CACHE_STORAGE_MODE']; exit;

		/**
		* Module/Model class name
		*/
		$model_cache['name'] = $module['class'];

		/**
		* Creates new instance of Cache library.
		*/
		$storage_cache = new Libs\Cache;

		switch( $env_modules['METHODS_CACHE_STORAGE_MODE'] )
		{
			case $c::STORAGE_CACHE_MODE_SESSION:

				/**
				* Stores data to Session
				*/
				$storage_cache -> Set( 
					array( $c::STORAGE_CACHE_MODE_SESSION => array(
							'key' => $c::MODEL_CACHE_SESSION_NAME,
							'val' => Libs\Encryption::Auto() -> Encode( $model_cache )
						)
					)
				);

			break;
			default:
			case $c::STORAGE_CACHE_MODE_COOKIE:

				/**
				* Get cookies default settings
				*/
				$cookies = $c -> GetEnv( 'COOKIES' );

				/**
				* Debug purposes
				*/
				//print_r( $cookies ); exit;

				/**
				* Stores data to cookie
				*/
				$storage_cache -> Set( 
					array( $c::STORAGE_CACHE_MODE_COOKIE => array(
							'key'       => $c::MODEL_CACHE_SESSION_NAME,
							'val'       => Libs\Encryption::Auto() -> Encode( $model_cache ),
							'time'      => $cookies['COOKIE_TIME'],
							'time_mode' => $cookies['COOKIE_TIME_MODE'],
							'path'      => $env_bootstrap['PUBLIC_FOLDER'],
							'domain'    => $env_bootstrap['DOMAIN'],
							'subdomain' => $env_bootstrap['SUBDOMAIN'],
						)
					)
				);

			break;
			case $c::STORAGE_CACHE_MODE_SQLITE:
				throw new \Exception('Core method cache storage in sqlite not available.');
			break;
		}



		/**
		* Clear variables.
		*/
		unset( $env_modules, $storage_cache );



		// Call Template library
		/**
		* [review]
		* Defined in Routing Modules file, however, not implemented
		* Must decide witch will have priority. The Routing alias module settings or the Template method of module.
		$module['force_language']
		$module['default_language']
		*/

		/**
		* Sets PHP include_path
		*/
		$base_path = TIPUI_APP_PATH . $env_templates['FOLDER'] . DIRECTORY_SEPARATOR . ( !isset( $model_cache['Template']['language'] ) ? $env_templates['DEFAULT_LANGUAGE'] : $model_cache['Template']['language'] ) . DIRECTORY_SEPARATOR . $c::APP_FOLDER_MODEL . DIRECTORY_SEPARATOR;
		set_include_path( $base_path );
		//ini_set( 'include_path', $base_path );

		/**
		* Call as instance
		*/
		$t = new Libs\Template;
		$t -> Init( $base_path, $env_templates['TAG'], $env_templates['OUTPUT'] );

		/**
		* Call statically
		*/
		//Libs\Template::Init( TIPUI_APP_PATH . $env_templates['FOLDER'] . DIRECTORY_SEPARATOR . ( !isset( $model_cache['Template']['language'] ) ? $env_templates['DEFAULT_LANGUAGE'] : $model_cache['Template']['language'] ) . DIRECTORY_SEPARATOR . $c::APP_FOLDER_MODEL . DIRECTORY_SEPARATOR, $env_templates['TAG'], $env_templates['OUTPUT'] );

		//Output's content-type
		header( 'Content-Type: ' . ( !isset( $model_cache['Template']['content_type'] ) ? $env_templates['DEFAULT_CONTENT_TYPE'] : $model_cache['Template']['content_type'] ) . '; charset=' . ( !isset( $model_cache['Template']['charset'] ) ? $env_bootstrap['CHARSET'] : $model_cache['Template']['charset'] ) );

		/**
		* Rendering and dispatching the template
		*/

		/**
		* Call as instance
		*/
		$t -> Compile( $m -> View(), ( !isset( $model_cache['Template']['dir'] ) ? false : $model_cache['Template']['dir'] ), ( !isset( $model_cache['Template']['file'] ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $module['class'] ) . $env_templates['DEFAULT_FILE_EXTENSION'] : $model_cache['Template']['file'] ) );

		/**
		* Call statically
		*/
		//Libs\Template::Compile( $m -> View(), ( !isset( $model_cache['Template']['dir'] ) ? false : $model_cache['Template']['dir'] ), ( !isset( $model_cache['Template']['file'] ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $module['class'] ) . $env_templates['DEFAULT_FILE_EXTENSION'] : $model_cache['Template']['file'] ) );

		/**
		* Clear $model_cache variable.
		*/
		unset( $base_path, $model_cache, $t );
	}

	/**
	* Clear the variables and Core instance.
	*/
	unset( $c, $module, $clss, $m, $env_bootstrap, $env_templates );

}