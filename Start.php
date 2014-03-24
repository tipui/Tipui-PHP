<?php

/**
* @file   Start.php
* @brief  Engine's start in procedural style.
* @date   2013-09-08 17:10:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-24 21:19:00
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
	* Flag for "module change".
	* Example, if want to load different module from what as requested, regardless if is found or not.
	*/
	$module['changed_from'] = null;

	/**
	* Debug purposes
	* Displays module or routing (alias) settings.
	*/
	//print_r( $module ); exit;

	/**
	* Loading Model Class
	*/
	$clss = '\Tipui\\' . TIPUI_APP_FOLDER_NAME . '\\' . $c::APP_FOLDER_MODEL . '\\' . $module['class'];
	$module['class_namespace'] = $clss;
	$m = new $clss;

	/**
	* Clear used variable
	*/
	unset( $clss );

	/**
	* Handles the HTTP Status code generaly for exceptions or errors events.
	* @see [code]$model_cache['Header']['http_status'][/code]
	*/
	$http_status_code = null;

	/**
	* Debug purposes
	*/
	//$c::ContextSet( 'foo', 'bar' );
	//print_r( $c -> context ); exit;
	//echo 'start: '; print_r( $c -> context -> foo ); exit;

	/**
	* Call module Form method, if exists
	*/
	if( method_exists( $m, 'Form' ) )
	{

		//$m -> form = $m -> Form();
		$m -> Form();

		/**
		* Sanitizing parameters (get, post, etc)
		*/
		$m -> form_data = Libs\DataValidation::Sanitize();

		/**
		* Checking if method have error.
		*/
		//echo __FILE__ . PHP_EOL;
		if( $m -> form_data['method_error'] )
		{

			/**
			* Request method is not what the module was expecting.
			*/
			$http_status_code = 405;

			/**
			* Debug purposes
			*/
			//echo 'form_data:method_error'; exit;

		}else if( $m -> form_data['error'] ){

			/**
			* Errors was found on parameters.
			*/
			$http_status_code = 400;

			/**
			* Debug purposes
			*/
			//echo 'form_data:error'; exit;

		}

		/**
		* Holding the context for resturned results from Model::Form() method
		*/
		$c::ContextSet( $module['class_namespace'], array( 'form_data' => $m -> form_data ) );

		/**
		* Debug purposes
		*/
		//print_r( $c -> context -> {$module['class_namespace']} -> form_data ); exit;
		//var_dump( $m -> form_data ); exit;
		//print_r( $m -> form_data ); exit;
		//echo current( $module['params'] ) . PHP_EOL;
		//print_r( $module ); exit;

	}else{

		/**
		* Debug purposes
		*/
		//print_r( $module ); exit;

		/**
		* The requested module is not expecting parameters.
		* If receiving parameters, probably, is an wrong URL
		* By default, must result in 404 "not found page".
		*/
		if( isset( $module['params'] ) && is_array( $module['params'] ) && current( $module['params'] ) !== false )
		{

			/**
			* Changing to module "not found".
			* ie: http://dev-php.tipui.com/pt/Docs/aaaa is a invalid page, but displays valid page http://dev-php.tipui.com/pt/Docs
			* To avoid SEO duplicated URL for same content, must return error or 404 HTTP status.
			*/
			$module_change = $c -> ChangeModuleAndLoad( $module, $c -> LoadNotFoundModule() );

			/**
			* The information of new target module.
			*/
			$module = $module_change['module_info'];

			/**
			* The instance of module loaded.
			*/
			$m      = $module_change['module_object'];

			/**
			* Clear used variable.
			*/
			unset( $module_change );

		}

	}

	/**
	* Call module Prepare method, if exists
	*/
	method_exists( $m, 'Prepare' ) ? $m -> prepare = $m -> Prepare() : '';

	/**
	* Handles data for model cache
	* [review]
	* original was (2014-03-24 19:57):
	* $model_cache = $module['changed_from'];
	*/
	$model_cache = isset( $module['changed_from'] ) ? $module['changed_from'] : array();

	/**
	* Module/Model class name
	*/
	$model_cache['name'] = !isset( $module['change']['class'] ) ? $module['class'] : $module['change']['class'];

	/**
	* Retrieving Model Header method, if exists
	*/
	if( method_exists( $m, 'Header' ) )
	{

		$model_cache['Header'] = $m -> Header();

	}else{

		if( !empty( $http_status_code ) )
		{
			$model_cache['Header']['http_status'] = $http_status_code;
		}

	}

	/**
	* Dispatching the HTTP Status, if defined on model or if [code]$http_status_code[/code] is not empty
	*/
	if( isset( $model_cache['Header']['http_status'] ) )
	{

		/**
		* Output's custom header HTTP Status
		*/
		Libs\Header::HTTPStatus( $model_cache['Header']['http_status'] );
	}

	/**
	* Retrieving template settings overriding, if exists
	* The Template method inside Model class have precedence, regardless of overridings caused by warnings or errors from Module.
	*/
	if( method_exists( $m, 'Template' ) )
	{
		$model_cache['Template'] = $m -> Template();

		/**
		* Outputs the header content-type and charset
		*/
		Libs\Header::ContentType( 
				( !isset( $model_cache['Template']['content_type'] ) ? $env_templates['DEFAULT_CONTENT_TYPE'] : $model_cache['Template']['content_type'] ),
				( !isset( $model_cache['Template']['charset'] ) ? $env_bootstrap['CHARSET'] : $model_cache['Template']['charset'] )
		);
	}



	/**
	* Creates new instance of Cache library.
	* [review]
	* Mark as deprecated for use context or keep both?
	* $c::ContextSet( $module['class_namespace'], array( 'model_cache' => $model_cache ) );
	*/
	Libs\Cache::Set( array( $c::MODEL_CACHE_SESSION_NAME => Libs\Encryption::Auto() -> Encode( $model_cache ) ) );

	/**
	* Stores the [code]$model_cache[/code] to context.
	*/
	$c::ContextSet( $module['class_namespace'], array( 'model_cache' => $model_cache ) );

	/**
	* Debug purposes
	*/
	//print_r( $c -> context -> {$module['class_namespace']} -> model_cache ); exit;

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
		* [review]
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
		unset( $base_path, $t, $lang_code );

	}

	/**
	* Clear the variables and Core instance.
	*/
	unset( $c, $module, $m, $env_bootstrap, $env_templates, $model_cache );

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