<?php

/**
* @class  FW
* @file   Tipui.php
* @brief  Engine's start.
* @date   2013-09-08 17:10:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-08 17:35:00
*/

// Run once time
if( !defined( 'TIPUI_PATH' ) )
{

	/**
	* Defining constants
	*/
	// Framework base path
	define( 'TIPUI_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

	// Builtin base path [[deprecated]
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
		* /app/ (default recommended location and folder name)
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

	/**
	* Tipui Core
	*/

	/**
	* Path to overriding Core file.
	*/
	$file = TIPUI_APP_PATH . 'Override' . DIRECTORY_SEPARATOR . 'Core.php';

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
		require_once( TIPUI_PATH . 'Core.php' );
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
	* Retrieves Core request session cached data.
	*/
	//$is_cli_mode = $c -> GetMethodDataCache( 'IsCliMode' );
	//var_dump( $is_cli_mode ); exit;

	/**
	* From this point, all methods of Core class requires Core::GetENV, Core::IsCliMode and Autoloader::.
	*
	* Stores Core Request method result to cache.
	*/
	//$c -> Request();
	$c -> SetMethodDataCache( 'Request' );

	/**
	* Debug purposes
	* Retrieves Core request session cached data.
	*/
	//$request = $c -> GetMethodDataCache( 'Request' );
	//print_r( $request ); exit;

	/**
	* Stores Core routing result to cache.
	*/
	//$c -> Routing();
	$c -> SetMethodDataCache( 'Routing' );

	/**
	* Retrieves Core routing session cached data.
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
	$clss = '\Tipui\App\Model\\' . $module['class'];
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

		// Call Template library
		/**
		* Defined in Routing Modules file, however, not implemented
		* Must decide where will have priority. The Routing alias module settings or the Template method of module.
		$module['force_language']
		$module['default_language']
		*/
		$t = new \Tipui\Builtin\Libs\Template( TIPUI_APP_PATH . $env_templates['FOLDER'] . DIRECTORY_SEPARATOR . ( !isset( $module_template['language'] ) ? $env_templates['DEFAULT_LANGUAGE'] : $module_template['language'] ) . DIRECTORY_SEPARATOR, $env_templates['TAG'], $env_templates['OUTPUT'] );

		//Output's content-type
		header( 'Content-Type: ' . ( !isset( $module_template['content_type'] ) ? $env_templates['DEFAULT_CONTENT_TYPE'] : $module_template['content_type'] ) . '; charset=' . ( !isset( $module_template['charset'] ) ? $env_bootstrap['CHARSET'] : $module_template['charset'] ) );

		// Rendering and dispatching the template
		$t -> Compile( $m -> View(), ( !isset( $module_template['dir'] ) ? false : $module_template['dir'] ), ( !isset( $module_template['file'] ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $module['class'] ) . $env_templates['DEFAULT_FILE_EXTENSION'] : $module_template['file'] ) );
		unset( $t );
	}

	/**
	* Clear the variables and Core instance.
	*/
	unset( $c, $module, $clss, $m, $env_bootstrap, $env_templates );

}