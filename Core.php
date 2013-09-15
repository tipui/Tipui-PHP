<?php

/**
* @class  Core
* @file   Core.php
* @brief  Engine's core.
* @date   2013-06-21 02:00:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 18:01:00
*/

namespace Tipui;

use \Tipui\Builtin\Libs as Libs;

class Core
{
	/**
	* Handles the environment settings
	*/
	private $env;

	/**
	* (boolean) true: CLI, false: HTTP
	*/
	private $sapi_is_cli;

	/**
	* Handles FileSystem instance
	*/
	private $fs;

	/**
	* Handles the request results
	*/
	private $request;

	/**
	* Handles Lib Session instance.
	*/
	private $session;

	/**
	* Path base to store core data cache files.
	*/
	private $cache_storage_env_dir;

	/**
	* Handle core data cache file content.
	*/
	private $core_cached_data;

	/**
	* Handle methods results.
	*/
	private $core_methods_cached_data;

	/**
	* Core data cache file name extension.
	*/
	const CORE_CACHE_FILE_EXTENSION = '.json';

	/**
	* PHP scripts files names extension.
	*/
	const CORE_ENV_FILE_EXTENSION = '.php';

	/**
	* The static method that calls Core->GetEnv()
	*/
	const CONF_CALLER = 'Tipui\Core::GetConf';

	/**
	* Stores the current object that is calling the Core.
	*/
	private $called_from;

	/**
	* Handles the parameters for [code]GetEnv[/code] method called from [code]self::CONF_CALLER[/code].
	*/
	private $called_getenv;

	/**
	* Initiates properties
	*/
    public function __construct()
    {

		/**
		* Debug purposes
		*/
		//echo '[' . time() . ']' . PHP_EOL;

		/**
		* Clear properties
		*/
		$this -> ResetProperties();

		/**
		* Defines Storage path for environment data cache files.
		*/
		$this -> cache_storage_env_dir = TIPUI_APP_PATH . 'Storage' . DIRECTORY_SEPARATOR . 'env' . DIRECTORY_SEPARATOR;


		/**
		* Retrieves the internal backtrace.
		*/
		$trace = debug_backtrace();

		/**
		* Debug purposes
		*/
		//print_r( $trace ); exit;

		/**
		* Identifies who called the Core instance.
		*/
		if( isset( $trace[1]['class'] ) and isset( $trace[1]['function'] ) )
		{
			/**
			* Resetting the parameters for [code]GetEnv[/code] method.
			*/
			$this -> called_getenv = null;

			/**
			* [review] Implement something to disable instantiates from disallowed places. Directly from templates, for example.
			* Stores the current object that is calling the Core.
			*/
			$this -> called_from = $trace[1]['class'] . '::' . $trace[1]['function'];
		}

		/**
		* Clear used var.
		*/
		unset( $trace );


		return null;

	}

	/**
	* Initiates general settings
	*/
    public function __destruct()
    {

		/**
		* Clear properties
		*/
		$this -> ResetProperties();

		return null;

	}

	/**
	* Clear properties.
	*/
    public function ResetProperties()
    {
		$this -> env     = null;
		$this -> fs      = null;
		$this -> core_methods_cached_data = null;
		$this -> core_cached_data         = null;
		$this -> session = null;

		return null;
	}

	/**
	* PHP Magic method. Get property if exists.
	* Undeclared property can be called without cause PHP fatal errors.
	* This allow us to do some "tricks", like PHP fluent access.
	*/
	public function __get($key)
	{
		/**
		* Debug purposes
		*/
		//echo __NAMESPACE__ ; exit;
		//echo $this -> called_from . '->' . $key; exit;

		/**
		* Execute only if is called from static "alias" method [code]self::CONF_CALLER[/code].
		*/
		if( $this -> called_from == self::CONF_CALLER )
		{
			/**
			* Debug purposes
			*/
			//echo $key;

			/**
			* Registering the parameters for [code]GetEnv[/code] method.
			*/
			$this -> called_getenv[] = $key;

			/**
			* If array have only 1 index, means that the second parameter for [code]GetEnv[/code] is not present.
			*/
			if( count( $this -> called_getenv ) == 1 )
			{
				return $this -> GetEnv( $key );
			}else{
				/**
				* Detected the second parameter for [code]GetEnv[/code] method.
				*/
				return $this -> GetEnv( $this -> called_getenv[0], ( isset( $this -> called_getenv[1] ) ? $key : false ) );
			}
		}

	}

	/**
	* Calls Core as static chained in fluent way.
	*
	* Alternative way:
	* [code]
	* $c = new Core;
	* $c -> GetEnv( 'URL','FORM_ACTION' );
	* [code]
	* @see Core::GetEnv()
	*
	* [usage sample]
	* Returns entire array
	* [code]print_r( Core::GetConf()->URL->_all );[/code]
	* Returns array index
	* [code]echo PHP_EOL . Core::GetConf()->URL->FORM_ACTION;[/code]
	* Returns array index
	* [code]echo PHP_EOL . Core::GetConf()->BOOTSTRAP->DOMAIN;[/code]
	* Returns array index
	* [code]echo PHP_EOL . Core::GetConf()->TEMPLATES->FOLDER;[/code]
	*/
	public static function GetConf()
	{
		return new Core;
	}

	/**
	* Registered environment settings files
	*/
    private function GetRegisteredSettingsFiles()
    {
		return array( 'BOOTSTRAP', 'PHP', 'URL', 'TIME_ZONE', 'COOKIES', 'MODULES', 'TEMPLATES' );
	}

	/**
	* Loads bootstrap and general files for environment settings
	*/
    public function LoadSettings()
    {

		/**
		* Path of public/index file
		*/
		$script_path   = $_SERVER['SCRIPT_FILENAME'];

		/**
		* Directory of app configuration files. (independent, but required by the core)
		*/
		$app_conf_path = TIPUI_APP_PATH . 'config' . DIRECTORY_SEPARATOR;

		/**
		* Gets the environment settings from the main file ENV.json
		*/
		$env_json = json_decode( file_get_contents( $app_conf_path . 'env' . DIRECTORY_SEPARATOR . 'ENV.json' ), true );

		/**
		* Debug purposes
		*/
		//print_r( $env_json ); exit;

		/**
		* Instantiates Library FileSystem
		*/
		$this -> fs = new Libs\FileSystem;

		/**
		* Check if Core ENV data cache file exists.
		* If exists, interrupt execution of this method.
		* 
		*/		
		if( !$env_json['CACHE_REGENERATE'] and $this -> fs -> FileExists( $this -> cache_storage_env_dir . 'ENV' . self::CORE_CACHE_FILE_EXTENSION ) )
		{
	
			$this -> env['URL']     = $this -> GetEnv( 'URL' );
			$this -> env['MODULES'] = $this -> GetEnv( 'MODULES' );
	
			$this -> fs = null;
			return null;
		}

		/**
		* Gets the environment name
		* Example:
		* Production: (http://tipui.com) /www/tipui.com/...
		* Development: (http://dev-tipui.com/) /www/dev-tipui.com/...
		* The logic is, if contains [code]$env_json['DEV'] . '-'[/code] then, means that is running under development environment.
		*/
		$env_name = strpos( str_replace( '/', DIRECTORY_SEPARATOR, $script_path ), DIRECTORY_SEPARATOR . $env_json['DEV'] . '-' ) ? $env_json['DEV'] : $env_json['PRODUCTION'] ;

		/**
		* Prepares the ENV index for Core cache file.
		*/
		$env = array( 
				'CONF_PATH'        => $app_conf_path,
				'PRODUCTION'       => $env_json['PRODUCTION'],
				'DEV'              => $env_json['DEV'],
				'NAME'             => $env_name,
				'PATH'             => $app_conf_path . 'env' . DIRECTORY_SEPARATOR . $env_name . DIRECTORY_SEPARATOR,
		);

		/**
		* Saves index ENV to Core cache file.
		*/
		$this -> SetENV( 'ENV', $env );

		/**
		* Clean up used variables.
		*/
		unset( $script_path, $env_json, $env_name, $app_conf_path );

		/**
		* Gets all registered index for Core cache files.
		*/
		$env_files = $this -> GetRegisteredSettingsFiles();

		/**
		* Iterates array above ([code]$env_files[/code]) to create all Core cache files.
		*/
		foreach( $env_files as $v )
		{
			require_once( $env['PATH'] . $v . self::CORE_ENV_FILE_EXTENSION );
			$this -> SetENV( $v, $array );
		}
		
		/**
		* Clean up used variables. 
		* The [code]$array[/code] variable is declared into included files above
		*/
		unset( $env, $env_files, $v, $array );

		/**
		* Clear FileSystem instance
		*/
		$this -> fs = null;

		return null;
	}

	/**
	* Loads and starts autoloading.
	*/
	// [review] Test on Start file instead of here.
	// benchmark and research about issues or benefits
    public function Autoloader()
    {

		/**
		* Subfolder and name of Autoloader file.
		*/
		$file = 'Builtin' .  DIRECTORY_SEPARATOR . 'Autoloader' . self::CORE_ENV_FILE_EXTENSION;

		/**
		* Path of override file.
		*/
		$path = TIPUI_APP_PATH . 'Override' . DIRECTORY_SEPARATOR . $file;

		/**
		* Check if override path exists.
		*/
		if( file_exists( $path ) )
		{
			require_once( $path );
		}else{
			/**
			* Use default builtin class.
			*/
			require_once( TIPUI_PATH . $file );
		}

		/**
		* Instantiates Autoloader class.
		*/
		$a = new Builtin\Autoloader();

		/**
		* Clear used variables.
		*/
		unset( $a, $path, $file );

		return null;

	}

	/**
	* Stores Core data in cache files (json format)
	*/
    private function SetENV( $index, $value )
    {
		$this -> env[ $index ] = $value;
		$this -> fs -> WriteFile( $this -> cache_storage_env_dir . $index . self::CORE_CACHE_FILE_EXTENSION, json_encode( $value ) );
		return null;
	}

	/**
	* Gets Core data in cache files (json format)
	*
	* [Usage sample]
	* [code]
	* $c = new Core;
	* $c -> GetEnv( 'URL','FORM_ACTION' );
	* [code]
	*
	* Alternative way:
	* [code]echo Core::GetConf()->URL->FORM_ACTION;[/code]
	* @see Core::GetConf()
	*/
    public function GetENV( $index, $subindex = false )
    {

		if( $this -> fs == null )
		{
			/**
			* Instantiates FileSystem class
			*/
			$this -> fs = new Libs\FileSystem;
		}

		/**
		* Check if cache file exists.
		*/
		if( $this -> fs -> FileExists( $this -> cache_storage_env_dir . $index . self::CORE_CACHE_FILE_EXTENSION ) )
		{

			/**
			* Loads from cached file.
			*/
			$this -> core_cached_data[$index] = json_decode( $this -> fs -> ReadFile( $this -> cache_storage_env_dir . $index . self::CORE_CACHE_FILE_EXTENSION ), true );


			/**
			* PHP fluent
			* Execute only if is called from static "alias" method [code]self::CONF_CALLER[/code]
			*/
			if( $this -> called_from == self::CONF_CALLER )
			{
				/**
				* The subindex [code]$subindex == '_all'[/code] is reserved, used to return entire array of [code]$index[/code]
				*/
				if( $subindex == '_all' )
				{
					return $this -> core_cached_data[$index];
				}else if( !$subindex ){
					/**
					* the [code]$subindex[/code] if false and is not equal to '_all', then, return the object context.
					*/
					return $this;
				}else{
					/**
					* Returns index and subindex value
					*/
					return $this -> core_cached_data[$index][$subindex];
				}

				return null;
			}

			/**
			* Non fluent
			*/
			if( !$subindex )
			{
				/**
				* Returns entire index data
				*/
				return $this -> core_cached_data[$index];

			}else{

				if( isset( $this -> core_cached_data[$index][$subindex] ) )
				{
					/**
					* Returns subindex if exists.
					*/
					return $this -> core_cached_data[$index][$subindex];

				}else{

					throw new \Exception('Core cache file data subindex "' . $subindex . '" not found for index "' . $index . '".');

				}

			}

		}else{

			throw new \Exception('Core cache file for index "' . $index . '" not found.');

		}

		return false;
	}

	/**
	* Detect if script is running over HTTP or CLI
	*/
    public function CheckCliMode()
    {
		$this -> sapi_is_cli = ( php_sapi_name() == 'cli' ) ? true : false;
	}

	/**
	* Retrieves the cli mode
	* (boolean)
	*/
    public function IsCliMode()
    {
		return $this -> sapi_is_cli;
	}

	/**
	* Abstracts parameters from HTTP or CLI
	*/
    public function Request()
    {

		/**
		* Debug purposes
		*/
		//var_dump( $this -> IsCliMode() ); exit;

		/**
		* URL samples:
		* /?p=Foo/Bar&id=2
		* /?p=Foo/Bar/2
		*
		* CMD samples:
		* > C:\php\php5.3.26ts\php.exe E:\_w\vhosts\dev-fw.tipui.com\public\index.php -p p=Foo/Bar%26id=2
		* > C:\php\php5.3.26ts\php.exe E:\_w\vhosts\dev-fw.tipui.com\public\index.php -p Foo/Bar/2
		*/

		$c = new Builtin\Libs\Request;
		$c -> SetParameter( $this -> env['URL']['PARAM_NAME'] );
		$c -> SetSapiMode( $this -> IsCliMode() );
		$c -> SetURLParts( $this -> env['URL']['HREF_BASE'] );

		/**
		* Debug purposes
		*/
		//echo $c -> GetMethod(); exit;
		//print_r( $c -> Extract() ); exit;

		/**
		* Stores to core data cache
		*
		* Applying urldecode() to support multibyte strings from URL
		* example http://localhost/ダミー
		*/
		$this -> request['URI'] = urldecode( $c -> Extract() );

		return $this -> request;

	}

	/**
	* Prepare routing
	*/
    public function Routing()
    {

		$clss = str_replace( $this -> env['URL']['PFS'], DIRECTORY_SEPARATOR, $this -> request['URI'] );

		if( empty( $clss ) )
		{
			/**
			* If parameter is empty, loads Front Module (default module)
			*/
			if( !$rs = $this -> RoutingPathScanner( 'Model', $this -> env['MODULES']['Front'] ) )
			{
				throw new \Exception('Front Module is invalid or not found. Check MODULES.php in /app/config/env/');
			}

		}else{

			/**
			* Debug purposes
			*/
			//echo $clss; exit;
			//echo urldecode( $clss ); exit;
			//echo mb_detect_encoding( urldecode( $clss ) ); exit;

			/**
			* Scanning into main Modules folder.
			*/
			if( !$rs = $this -> RoutingPathScanner( 'Model', $clss ) )
			{

				/**
				* Debug purposes
				*/
				//echo $this -> request['URI']; exit;

				/**
				* Scanning routing module.
				*/
				if( !$rs = $this -> RoutingPathScanner( 'Routing', $this -> request['URI'] ) )
				{
					/**
					* Debug purposes
					*/
					// Load 404 not found
					//echo 23; print_r( $rs ); exit;
					//echo '404'; exit;
				}
				
			}

			/**
			* If model or routing not found, loads 404 (not found) Module
			*/
			if( !$rs and !$rs = $this -> RoutingPathScanner( 'Model', $this -> env['MODULES']['404'] ) )
			{
				throw new \Exception('404/Notfound Module is invalid or not found. Check MODULES.php in /app/config/env/');
			}

		}

		/**
		* Debug purposes
		*/
		//var_dump( $rs ); exit;
		//print_r( $rs ); exit;

		/**
		* Include the module file
		*/
		require_once( $rs['path'] );

		/**
		* Debug purposes
		* Sample to load a module
		$clss = '\Tipui\App\Model\\' . str_replace( DIRECTORY_SEPARATOR, '\\', $rs['class'] );
		$c = new $clss;
		$c -> View(); exit;
		*/

		/**
		* Return the results
		*/
		return $rs;
	}

	/**
	* Routing paths scanner
	*/
    private function RoutingPathScanner( $path_base, $clss )
    {

		/**
		* folders levels
		*/
		$i       = $this -> env['URL']['FOLDER_LEVELS'];

		/**
		* (boolean) true: path found, false: path not found
		*/
		$goal    = false;

		/**
		* Handles the Routing/Module class
		*/
		$routing = false;

		while( $i > 0 and !$goal and !empty( $clss ) )
		{
			if( $path_base == 'Model' )
			{

				$path = TIPUI_APP_PATH . $path_base . DIRECTORY_SEPARATOR . $clss . self::CORE_ENV_FILE_EXTENSION;

				/**
				* Debug purposes
				*/
				//echo $path . ': ';

				if( file_exists( $path ) )
				{
					/**
					* Debug purposes
					*/
					//echo 'ok';
					//echo $clss; exit;
					$goal = true;
					$rs   = array( 'path' => $path, 'class' => $clss );
				}else{
					$clss = substr( $clss, 0, strrpos( $clss, DIRECTORY_SEPARATOR ) );
					/**
					* Debug purposes
					*/
					//echo 'ng [next] ' . $clss;
				}

			}else{

				if( !$routing )
				{
					$routing = new \Tipui\App\Routing\Modules;
				}

				if( $rs = $routing -> Get( $clss ) )
				{
	
					/**
					* Debug purposes
					*/
					//echo 'ok';
					//print_r( $rs ); exit;
					$goal       = true;
					$rs['path'] = TIPUI_APP_PATH . 'Model' . DIRECTORY_SEPARATOR . str_replace( $this -> env['URL']['PFS'], DIRECTORY_SEPARATOR, $rs['class'] ) . self::CORE_ENV_FILE_EXTENSION;

					if( !file_exists( $rs['path'] ) )
					{
						throw new \Exception('[' . $path_base . '] Alias class not found. Check \Tipui\App\Routing\Modules');
					}

				}else{
					$clss = substr( $clss, 0, strrpos( $clss, $this -> env['URL']['PFS'] ) );
					/**
					* Debug purposes
					*/
					//echo 'ng [next] ' . $clss;
				}

			}

			/**
			* Debug purposes
			*/
			//echo PHP_EOL;

			$i--;
		}

		/**
		* Debug purposes
		*/
		//echo $path;
		//exit;

		unset( $routing, $i );

		return $goal ? $rs : false;

	}

	/**
	* Retrieves the cached Core data on storage cache files
	*/
	public function GetMethodDataCache( $method = false, $instance = false )
	{

		if( $this -> session == null )
		{
			$this -> session = new Libs\Session;
		}

		/**
		* Get from session.
		*/
		$this -> core_methods_cached_data[$method] = $this -> session -> Get( 'Tipui::Core::' . $method );

		if( isset( $this -> core_methods_cached_data[$method] ) )
		{

			if( !$instance )
			{

				return $this -> core_methods_cached_data[$method];

			}else{

				if( isset( $this -> core_methods_cached_data[$method][$instance] ) )
				{

					return $this -> core_methods_cached_data[$method][$instance];

				}else{

					throw new \Exception('Core method cache data instance "' . $instance . '" not found for method "' . $method . '".');

				}

			}

		}else{

			throw new \Exception('Core method cache "' . $method . '" not found.');

		}

	}

	/**
	* Stores methods results to cache (session)
	*/
	public function SetMethodDataCache( $method )
	{

		if( $this -> session == null )
		{
			$this -> session = new Libs\Session;
		}

		$this -> session -> Set( 'Tipui::Core::' . $method, $this -> $method() );

		return null;

	}

}