<?php

/**
* @file   Start.php
* @brief  Engine's start in procedural style.
* @date   2013-09-08 17:10:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-03 19:28:00
*
* Git: https://github.com/tipui/Tipui-PHP
*/



/**
* Namespace alias for builtin Libraries
*/
use Tipui\Builtin\Libs as Libs;

// Run once time
if( !defined( 'TIPUI_PATH' ) )
{

	/**
	* Defining initial timestamp for benchmark
	*/
	define( 'TIPUI_TIME_INI', microtime() );

	/**
	* Defining constants
	*/
	// Framework version
	define( 'TIPUI_VERSION', '1.0' );

	// Framework base path
	define( 'TIPUI_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

	// public path, where the index.php is located
	define( 'TIPUI_APP_PUBLIC_PATH', str_replace( '/', DIRECTORY_SEPARATOR, dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . DIRECTORY_SEPARATOR );

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
		* The [code]realpath()[/code] function is used to canonicalize the path instead of using [code]str_replace()[/code] to convert the slashes.
		* The app folder, by default, must be 1 folder above the public folder.
		* See examples for other cases:
		* case 1. The app folder below the public folder: app/../public/app
		* case 2. The app folder, 2 folders above the public folder: ../app
		* case 3. The app folder, 1 folder above the public folder, but inside other folder: ../other/app
		*/
		//$app_path = str_replace( '/', DIRECTORY_SEPARATOR, dirname( $_SERVER['SCRIPT_FILENAME'] ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . TIPUI_APP_FOLDER_NAME . DIRECTORY_SEPARATOR );
		//$app_path = str_replace( '/', DIRECTORY_SEPARATOR, dirname( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . DIRECTORY_SEPARATOR . TIPUI_APP_FOLDER_NAME . DIRECTORY_SEPARATOR );
		$app_path = realpath( dirname( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . DIRECTORY_SEPARATOR . TIPUI_APP_FOLDER_NAME ) . DIRECTORY_SEPARATOR;

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
	* Starts autoloader
	*/
	$c -> Autoloader();

	/**
	* Loads settings and saves methods Core::IsCliMode() and Core::Routing() results to cache
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
	* From this point, all methods of Core class requires Core::GetENV, Core::IsCliMode and Autoloader.
	*/

	/**
	* Retrieves Core routing cached data.
	* @see Core::LoadSettings(), Core::SaveToCache()
	*/
	$module = $c -> GetMethodDataCache( 'Routing' );

	/**
	* Debug purposes
	* Displays module or routing (alias) settings.
	*/
	//print_r( $module ); exit;

	/**
	* Loading Model Class
	*/
	$clss = '\Tipui\\' . TIPUI_APP_FOLDER_NAME . '\Model\\' . $module['class'];
	$m = new $clss;

	unset( $clss );

	/**
	* Call module Form method, if exists
	*/
	if( method_exists( $m, 'Form' ) )
	{
		$m -> form = $m -> Form();
		
		/**
		* Sanitizing parameters (get, post, etc)
		*/
		Libs\DataValidation::Sanitize();

		/**
		* Debug purposes
		*/
		//$m -> parameters = Libs\DataValidation::Sanitize();
		//print_r( $m -> parameters ); exit;
	}

	/**
	* Call module Prepare method, if exists
	*/
	method_exists( $m, 'Prepare' ) ? $m -> prepare = $m -> Prepare() : '';

	/**
	* Handles data for model cache
	*/
	$model_cache = null;

	/**
	* Module/Model class name
	*/
	$model_cache['name'] = $module['class'];

	/**
	* Retrieving Model Header method, if exists
	*/
	if( method_exists( $m, 'Header' ) )
	{
		$model_cache['Header'] = $m -> Header();

		/**
		* Output's custom header HTTP Status
		*/
		Libs\Header::HTTPStatus( $model_cache['Header']['http_status'] );
	}

	/**
	* Retrieving template settings overriding, if exists
	*/
	if( method_exists( $m, 'Template' ) )
	{
		$model_cache['Template'] = $m -> Template();

		/**
		* Output's header content-type and charset and content-type
		*/
		Libs\Header::ContentType( 
				( !isset( $model_cache['Template']['content_type'] ) ? $env_templates['DEFAULT_CONTENT_TYPE'] : $model_cache['Template']['content_type'] ),
				( !isset( $model_cache['Template']['charset'] ) ? $env_bootstrap['CHARSET'] : $model_cache['Template']['charset'] )
		);
	}



	/**
	* Creates new instance of Cache library.
	*/
	Libs\Cache::Set( array( $c::MODEL_CACHE_SESSION_NAME => Libs\Encryption::Auto() -> Encode( $model_cache ) ) );



	/**
	* Rendering template if exists View() method
	*/
	if( method_exists( $m, 'View' ) )
	{

		// Call Template library
		/**
		* [review]
		* Defined in Routing Modules file, however, not implemented
		* Must decide witch will have priority. The Routing alias module settings or the Template method of module.
		$module['force_language']
		$module['default_language']
		*/

		/**
		* Defining the folder name of template files.
		* The parameter LANGUAGES_IN_FOLDER must be enabled to use this feature.
		* @see: app/config/env/TEMPLATES
		*/
		$lang_code = null;

		if( $env_templates['LANGUAGES_IN_FOLDER'] )
		{

			if( !$lang_code = $c -> GetMethodDataCache( 'LanguageCodeFromParameters' ) )
			{
				$lang_code = ( !isset( $model_cache['Template']['language'] ) ? $env_templates['DEFAULT_LANGUAGE'] : $model_cache['Template']['language'] ) . DIRECTORY_SEPARATOR;
			}

		}

		/**
		* Sets PHP include_path
		*/
		$base_path = TIPUI_APP_PATH . $env_templates['FOLDER'] . DIRECTORY_SEPARATOR . $lang_code . $c::APP_FOLDER_MODEL . DIRECTORY_SEPARATOR;
		set_include_path( $base_path );
		//echo $base_path; exit;

		/**
		* Call as instance
		*/
		$t = new Libs\Template;
		$t -> Init( $base_path, $env_templates['TAG'], $env_templates['OUTPUT'] );

		/**
		* Rendering and dispatching the template
		*
		* Call as instance
		*/
		$t -> Compile( $m -> View(), ( !isset( $model_cache['Template']['dir'] ) ? false : $model_cache['Template']['dir'] ), ( !isset( $model_cache['Template']['file'] ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $module['class'] ) . $env_templates['DEFAULT_FILE_EXTENSION'] : $model_cache['Template']['file'] ) );

		/**
		* Clear used variables.
		*/
		unset( $base_path, $model_cache, $t, $lang_code );
	}

	/**
	* Clear the variables and Core instance.
	*/
	unset( $c, $module, $m, $env_bootstrap, $env_templates );


	/**
	* Benchmark debugging
	*/
	//echo '<!--' , number_format( microtime() - TIPUI_TIME_INI, 3 ) , '-->';
	//echo '<!-- files: ' . count( get_included_files() ) . ' memory: '. memory_get_usage() . ' memory peak: '. memory_get_peak_usage() . '-->';

	/**
	* Apache Benchmark
	* > C:\Apache\httpd-2.2.25-win32-x86-openssl-0.9.8y\bin>ab -n 1000 -c 5 http://dev-php.tipui.com/
	*/
}