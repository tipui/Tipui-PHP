<?php

/**
* @class  Core
* @file   Core.php
* @brief  Engine's core.
* @date   2013-06-21 02:00:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-29 19:09:00
*/

namespace Tipui;

use \Tipui\Builtin\Libs as Libs;

class Core
{

	/**
	* Flag for [code]self::$context[/code] calling 
	*
	* @see self::$context_holder
	* @see self::__get()
	* @see self::ContextSet()
	*/
	protected static $called_context = null;

	/**
	* Handles the context that provides access to objects in the current state.
	* @see self::$called_context
	* @see self::__get()
	* @see self::ContextSet()
	*/
	protected static $context_holder = null;

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
	* Handles Lib Cache instance.
	*/
	private $cache;

	/**
	* Path base to store core data cache files.
	*/
	private $cache_storage_env_dir;

	/**
	* Handle core data cache file content.
	* [deprecated]
	*/
	private $core_cached_data;

	/**
	* Builtin language code
	*/
	const BUILTIN_LANG_CODE = 'en';

	/**
	* Builtin folder name
	*/
	const ENV_FOLDER_BUILTIN = 'Builtin';

	/**
	* Configuration environment files folder name
	*/
	const CONF_FOLDER_ENV = 'env';

	/**
	* DataRules folder name
	*/
	const ENV_FOLDER_DATARULES = 'DataRules';

	/**
	* App models folder name
	*/
	const APP_FOLDER_MODEL = 'Model';

	/**
	* App routing folder name
	*/
	const APP_FOLDER_ROUTING = 'Routing';

	/**
	* App config folder name
	*/
	const APP_FOLDER_CONFIG = 'config';

	/**
	* Storage folder name
	*/
	const STORAGE_FOLDER = 'Storage';

	/**
	* Language folder name
	* For translations files
	*/
	const LANGUAGE_FOLDER = 'Languages';

	/**
	* Vendor folder name
	* For third party libraries
	*/
	const VENDORS_FOLDER = 'Vendors';

	/**
	* Helpers folder name
	*/
	const ENV_FOLDER_HELPERS = 'Helpers';

	/**
	* Core data cache file name extension.
	*/
	const CACHE_FILE_EXTENSION = '.json';

	/**
	* The static method that return the object self::$context_holder
	*/
	const CONTEXT_CALLER = 'Tipui\Core::GetContext';

	/**
	* The static method that calls Core->GetEnv()
	*/
	const CONF_CALLER = 'Tipui\Core::GetConf';
	//const CONF_CALLER = 'Tipui\Core::GetConf->context';

	/**
	* Model/Module session/cookie array index (id)
	* [deprecated]
	*/
	const MODEL_CACHE_SESSION_NAME = 'Tipui::App::Model';

	/**
	* Storage mode option name as Session $_SESSION
	* [deprecated]
	*/
	const STORAGE_CACHE_MODE_SESSION = 'session';

	/**
	* Storage mode option name as Cookie $_COOKIE
	* [deprecated]
	*/
	const STORAGE_CACHE_MODE_COOKIE = 'cookie';

	/**
	* Storage mode option name as SQLite (DB)
	* [important] not available
	*/
	const STORAGE_CACHE_MODE_SQLITE = 'sqlite';

	/**
	* Stores the current object that is calling the Core.
	*/
	private $called_from;

	/**
	* Handles the parameters for [code]GetEnv[/code] method called from [code]self::CONF_CALLER[/code].
	*/
	private $called_getenv;

	/**
	* Handles the interface language code.
	*/
	private $lang_code;

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
		$this -> cache_storage_env_dir = $this -> CacheStorageEnvPath();


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
	* Builds the path of cache files of environment settings.
	*/
    public function CacheStorageEnvPath()
    {
		return TIPUI_APP_PATH . Core::STORAGE_FOLDER . DIRECTORY_SEPARATOR . Core::CONF_FOLDER_ENV . DIRECTORY_SEPARATOR;
	}

	/**
	* Clear properties.
	*/
    public function ResetProperties()
    {

		$this -> fs               = null;
		$this -> core_cached_data = null;

		return null;
	}

	/**
	* PHP Magic method. Get property if exists.
	* Undeclared property can be called without cause PHP fatal errors.
	* This allow us to do some "tricks", like PHP fluent access.
	*/
	public function __get( $key )
	{
		/**
		* Debug purposes
		*/
		//echo __NAMESPACE__ ; exit;
		//echo 'called_from: ' . $this -> called_from . '->' . $key  . PHP_EOL; // exit;

		//if( $this -> called_from == 'Tipui\Core::__get' && ( $key == 'LanguageCodeFromParameters' ) )
		//{
			//echo time(); exit;
		//}

		/**
		* Execute only if is called from static "alias" method [code]self::CONTEXT_CALLER[/code].
		*/
		if( $this -> called_from == self::CONTEXT_CALLER )
		{
		
			if( property_exists( self::$context_holder, $key ) )
			{
				return self::$context_holder -> {$key};
			}

		}else if( $this -> called_from == self::CONF_CALLER ){

			/**
			* Execute only if is called from static "alias" method [code]self::CONF_CALLER[/code].
			*/

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

		}else if( !empty( self::$called_context ) ){

			/**
			* Debug purposes
			*/
			//echo PHP_EOL . __FILE__ . PHP_EOL;
			//var_dump( self::$called_context ); echo PHP_EOL;
			//echo 'bb: ' . $this -> called_from . ' :->' . $key . PHP_EOL; //exit;
			//echo PHP_EOL . 'array: '; print_r( self::$context_holder ); exit;
			//self::$context_holder['foo'] = 'bar2';

			/**
			* Checking if requested index exists into [code]$context_holder[/code]
			*/
			if( property_exists( self::$context_holder, $key ) )
			{
				return self::$context_holder -> {$key};
			}else{
				/**
				* Dispatching fatal error
				*/
				throw new \Exception('Core context index "' . $index . '" was not found.');
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
		return new self(); // new Core()
		//return self::$context_holder -> config;
		//$c = new self();
		//return $c -> context;
	}

	/**
	* Calls Core::context as static chained in fluent way.
	*/
	public static function GetContext()
	{
		return self::$context_holder;
	}

	/**
	* Registered environment settings files
	* @see self::LoadSettings(). There are 2 points, one for existing cache and other when is cache files is created.
	*/
    private function GetRegisteredSettingsFiles()
    {
		return array( 'BOOTSTRAP', 'PHP', 'URL', 'TIME_ZONE', 'COOKIES',  'LANGUAGES', 'MODULES', 'TEMPLATES' );
	}

	/**
	* Sets objects for current context.
	*
	* Example for setting:
	* self::ContextSet( 'form_data_error', 'error_code_test' );
	*
	* Example for getting:
	* \Tipui\Core -> context -> form_data_error;
	* displays: error_code_test
	*
	* @param $key: (string) key name.
	* @param $value: object or array.
	*
	* @see self::__get()
	* @see self::$called_context
	*/
	public static function ContextSet( $key, $value )
	{

		/**
		* If empty, must created anonymous stdClass.
		*/
		if( empty( self::$context_holder ) )
		{
			self::$context_holder = (object) array(); // new stdClass();
		}

		/**
		* Assigning new or replacing existing property.
		*/
		if( is_array( $value ) )
		{

			foreach( $value as $k => $v )
			{

				/**
				* If not exists, must initialize as stdClass or array casted as object to avoid the strict warning "creating default object from empty value".
				*/
				if( !property_exists( self::$context_holder, $key ) )
				{
					self::$context_holder -> {$key} = (object) array(); // new stdClass();
				}

				self::$context_holder -> {$key} -> {$k} = $v;

			}

			/**
			* Debug purposes
			*/
			/*
			if( $key == 'Routing' )
			{
				print_r( self::$context_holder ); exit;
			}
			*/

		}else{

			self::$context_holder -> {$key} = $value;

		}

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
		$app_conf_path = TIPUI_APP_PATH . self::APP_FOLDER_CONFIG . DIRECTORY_SEPARATOR;

		/**
		* Gets the environment settings from the main file ENV.json
		*/
		$env_json = json_decode( file_get_contents( $app_conf_path . self::CONF_FOLDER_ENV . DIRECTORY_SEPARATOR . 'ENV' . self::CACHE_FILE_EXTENSION ), true );

		/**
		* Debug purposes
		*/
		//print_r( $env_json ); exit;

		/**
		* Declaring the stdClass instance.
		*/
		$this -> ContextSet( 'config', array( 'env' => (object) array() ) );

		/**
		* Instantiates Library FileSystem
		*/
		$this -> fs = new Libs\FileSystem;

		/**
		* Check if Core ENV data cache file exists.
		* If exists, interrupts execution of this method inside the conditional.
		* 
		*/
		if( !$env_json['CACHE_REGENERATE'] && $this -> fs -> FileExists( $this -> cache_storage_env_dir . 'ENV' . self::CACHE_FILE_EXTENSION ) )
		{

			/**
			* Executes PHP runtime ini settings
			* Dependencies: ENV, BOOTSTRAP and PHP config/env files
			*/
			$env       = $this -> GetEnv( 'ENV' );
			$bootstrap = $this -> GetEnv( 'BOOTSTRAP' );
			$array     = $this -> GetEnv( 'PHP' ); // The variable name must be generic "array" because the self::SetEnv() method.
			require_once( $env['PATH'] . 'PHP_INI_SET' . TIPUI_CORE_ENV_FILE_EXTENSION );

			/**
			* Loads URL and MODULES configuration from cache files to context.
			* Access sample:
			* print_r( Core -> context -> config -> env -> URL );
			*/

			/**
			* Adding the objects for the new instance.
			*/
			self::$context_holder -> {'config'} -> {'env'} -> {'URL'} = $this -> GetEnv( 'URL' );
			self::$context_holder -> {'config'} -> {'env'} -> {'MODULES'} = $this -> GetEnv( 'MODULES' );
			self::$context_holder -> {'config'} -> {'env'} -> {'LANGUAGES'} = $this -> GetEnv( 'LANGUAGES' );

			/**
			* Clear FileSystem instance handler.
			*/
			$this -> fs = null;

			/**
			* Clean up used variables.
			*/
			unset( $script_path, $env_json, $app_conf_path, $env );

			/**
			* Saves methods results to context
			*/
			$this -> SaveToContext();

			/**
			* Data was retrieve from cache.
			* Force exit this method execution.
			*/
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
				'PATH'             => $app_conf_path . self::CONF_FOLDER_ENV . DIRECTORY_SEPARATOR . $env_name . DIRECTORY_SEPARATOR,
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
			require_once( $env['PATH'] . $v . TIPUI_CORE_ENV_FILE_EXTENSION );
			$this -> SetENV( $v, $array );
		}
		
		/**
		* Clean up used variables. 
		* The [code]$array[/code] variable is declared into included files above
		*/
		unset( $env_files, $v, $array, $env );

		/**
		* Clear FileSystem instance
		*/
		$this -> fs = null;

		/**
		* Saves methods results to cache
		*/
		$this -> SaveToContext();


		return null;
	}



	/**
	* Executes and saves Core required initialization methods to cache.
	*/
    private function SaveToContext()
    {

		/**
		* Storing Core methods results to context object
		*/

		/**
		* Saves the Interface type information.
		* If true, is running under CLI (Command Line Interface)
		*/
		self::ContextSet( 'IsCliMode', $this -> IsCliMode() );

		/**
		* Saves data extracted from URL (parameters and routing).
		*/
		self::ContextSet( 'Routing', $this -> Routing() );

		/**
		* Stores the interface language code.
		*/
		self::ContextSet( 'LanguageCodeFromParameters', $this -> LanguageCodeFromParameters() );

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
		$file = self::ENV_FOLDER_BUILTIN .  DIRECTORY_SEPARATOR . 'Autoloader' . TIPUI_CORE_ENV_FILE_EXTENSION;

		/**
		* Path of override file.
		*/
		$path = TIPUI_APP_PATH . TIPUI_FOLDER_OVERRIDE . DIRECTORY_SEPARATOR . $file;

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

		/**
		* Stores to context.
		*/
		self::$context_holder -> {'config'} -> {'env'} -> {$index} = $value;

		/**
		* Stores to cache files.
		*/
		$this -> fs -> WriteFile( $this -> cache_storage_env_dir . $index . self::CACHE_FILE_EXTENSION, json_encode( $value ) );
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
		if( $this -> fs -> FileExists( $this -> cache_storage_env_dir . $index . self::CACHE_FILE_EXTENSION ) )
		{

			/**
			* Loads from cached file.
			*/
			$this -> core_cached_data[$index] = json_decode( $this -> fs -> ReadFile( $this -> cache_storage_env_dir . $index . self::CACHE_FILE_EXTENSION ), true );


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
					if( isset( $this -> core_cached_data[$index][$subindex] ) )
					{
						return $this -> core_cached_data[$index][$subindex];
					}
				}

				return false;
			}

			/**
			* Non fluent
			* [deprecated] use context instead
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
	* Retrieves the cli mode
	* (boolean)
	* @see self::SaveToContext()
	*/
    private function IsCliMode()
    {
		return $this -> sapi_is_cli = ( php_sapi_name() == 'cli' ) ? true : false;
	}

	/**
	* Abstracts parameters from HTTP or CLI
	*/
    private function Request()
    {

		/**
		* Debug purposes
		*/
		//var_dump( $this -> IsCliMode() ); exit;

		/**
		* [URL samples]
		*
		* Normal mode
		* /?p=Foo/Bar&id=2
		*
		* URL Rewrite mode:
		* /Foo/Bar/2
		*
		* Command Line Interface (CLI)
		* > C:\php\5.3.5-ts\php.exe C:\_w\vhosts\dev-php.tipui.com\public\index.php
		* > C:\php\php5.3.26ts\php.exe E:\_w\vhosts\dev-php.tipui.com\public\index.php -p Foo/Bar/2
		*/

		/**
		* Creates new instance for Request Library and set it to defaults
		*/
		$c = new Builtin\Libs\Request;
		$c -> SetDefaults();

		/**
		* Necessary to enable getopt() functionality (CLI mode)
		* If not in CLI mode, calling the [code]$c -> SetParameter[/code] may conflicts with POST method.
		*/
		if( $this -> sapi_is_cli )
		{
			$c -> SetParameter( self::$context_holder -> config -> env -> URL['PARAM_NAME'] );
		}

		/**
		* Set the mode that is running the current script
		*/
		$c -> SetSapiMode( $this -> sapi_is_cli );

		/**
		* Set the URL basic parts
		*/
		$c -> SetURLParts( self::$context_holder -> config -> env -> URL['HREF_BASE'] );

		/**
		* Debug purposes
		*/
		//var_dump( $this -> sapi_is_cli ); exit;
		//echo time(); exit;
		//echo $c -> GetMethod(); exit;
		//print_r( $c -> Extract() ); exit;

		/**
		* Retrieving the results
		*/
		$this -> request['method'] = $c -> GetMethod();
		$this -> request['params'] = $c -> Extract();
		$this -> request['mode_rewrite'] = $c -> IsModeRewrite();

		/**
		* Debug purposes
		*/
		//echo 'lang_code: ' . $lang_code;
		//print_r( $this -> request ); exit;
		//$this -> request['URI'] = urldecode( $c -> Extract() );

		return $this -> request;

	}

	/**
	* Extracts the language code from parameters, if exists.
	* If found, the code is validated with array of languages defined in app/config/env/LANGUAGES
	* To read the value [deprecated]: [code]\Tipui\Core::GetConf()->GetMethodDataCache('LanguageCodeFromParameters')[/code]
	* To read the value: 
	* [code]\Tipui\Core::GetContext()->LanguageCodeFromParameters[/code]
	* or 
	* [code]$c = new \Tipui\Core(); $c -> context -> LanguageCodeFromParameters[/code]
	* @see self::SaveToContext()
	*/
    private function LanguageCodeFromParameters()
    {

		if( empty( $this -> lang_code ) )
		{
			return false;
		}

		return $this -> lang_code;

	}

	/**
	* Extracts the language code from parameters, if exists.
	* If found, the code is validated with array of languages defined in app/config/env/LANGUAGES
	*/
    private function SetLanguageCodeFromParameters()
    {

		/**
		* Checking for language code
		* For URL in mode rewrite, the parameter must be the first 2 characters + / (slash)
		* For normal URL, must check the parameter name define in config/env/URL
		* The parameter name: self::GetConf()->URL->PARAM_LANG
		*/

		$this -> lang_code = null;

		if( is_array( $this -> request['params'] ) )
		{

			if( isset( $this -> request['params'][self::$context_holder -> config -> env -> URL['PARAM_LANG']] ) )
			{
				/**
				* For normal requests by GET or POST, not for URL rewrite format.
				*/
				$this -> lang_code = strtolower( $this -> request['params'][self::$context_holder -> config -> env -> URL['PARAM_LANG']] );
			}

		}else{

			/**
			* Probably, came from mode rewrite URL.
			*
			* Extracts the language code + slash ( ie: en/ )
			*/
			$this -> lang_code = substr( $this -> request['params'], 0, 3 );
			//echo $this -> lang_code; exit;

			/**
			* Extracts the last character of extracted data. The target is find the parameters folder separator (slash).
			*/
			$slash_check = substr( $this -> lang_code, 2, 1 );
			//echo $slash_check; exit;

			/**
			* If parameters folder separator was found or, if found empty character, then, validates as possible language code.
			*/
			if( empty( $slash_check ) || $slash_check == self::$context_holder -> config -> env -> URL['PFS'] )
			{
				/**
				* Formatting the string according to ISO 639-1 for languages codes.
				*/
				$this -> lang_code = strtolower( substr( $this -> lang_code, 0, 2 ) );
			}else{
				/**
				* If the expected format is invalid, must assign null value.
				*/
				$this -> lang_code = null;
			}

			/**
			* Clear used variable
			*/
			unset( $slash_check );

		}

		/**
		* Validating the language code
		* If not exists, will be ignored and setted to null.
		* The language code is case insensitive.
		*/
		if( !empty( $this -> lang_code ) && !isset( self::$context_holder -> config -> env -> LANGUAGES[$this -> lang_code] ) )
		{
			$this -> lang_code = null;
		}

		/**
		* Debug purposes
		*/
		//echo 'lang_code (' . __LINE__ . '): ' . $this -> lang_code; exit;

	}

	/**
	* Prepare routing
	* @see self::SaveToContext()
	*/
    private function Routing()
    {

		/**
		* Extracts the URL data. (get, post, method, url_rewrite, etc)
		*/
		$this -> Request();

		/**
		* Handles the main parameter value.
		*/
		$module_uri = null;

		/**
		* Handles module class name.
		*/
		$clss = null;

		/**
		* If $this -> request['params'] is array, means that is comming from normal URL.
		* For this case, must check if ['PARAM_NAME'] exists in the array indexes.
		*/
		if( !$this -> request['mode_rewrite'] )
		{

			if( isset( $this -> request['params'][self::$context_holder -> config -> env -> URL['PARAM_NAME']] ) )
			{
				$module_uri = $this -> request['params'][self::$context_holder -> config -> env -> URL['PARAM_NAME']];
			}

		}else{
			/**
			* From mode rewrite URL.
			*/
			$module_uri = $this -> request['params'];
		}

		//print_r( $this -> request['params'] ); exit;

		/**
		* Retrieving the language code, if exists.
		*/
		//$lang_code = $this -> SetLanguageCodeFromParameters();
		$this -> SetLanguageCodeFromParameters();

		/**
		* Checking the language code on main parameter.
		*/
		if( !empty( $module_uri ) )
		{

			/**
			* Checking the language code on main parameter.
			*/
			if( !empty( $this -> lang_code ) )
			{

				/**
				* Debug purposes.
				*/
				//echo $this -> lang_code; exit;

				/**
				* For mode rewrite parameter, must remove the part the identifies the language code.
				*/
				if( $this -> request['mode_rewrite'] )
				{

					/**
					* Removes the first 2 characters that represents the language code.
					*/
					$module_uri = substr( $module_uri, 2 );

					/**
					* Removes the parameter folder separator character, if exists.
					*/
					if( substr( $module_uri, 0, 1 ) == self::$context_holder -> config -> env -> URL['PFS'] )
					{
						$module_uri = substr( $module_uri, 1 );
					}

				}

			}

			/**
			* Debug purposes
			*/
			//print_r( $module_uri ); exit;

			/**
			* Compatibility for multibyte strings
			*/
			$module_uri = urldecode( $module_uri );

			/**
			* Building the class name
			*/
			$clss = str_replace( self::$context_holder -> config -> env -> URL['PFS'], DIRECTORY_SEPARATOR, $module_uri );

		}


		/**
		* Debug purposes
		*/
		//echo 'Routing:clss ' . $clss; exit;

		if( empty( $clss ) )
		{
			/**
			* If parameter is empty, loads Front Module (default module)
			*/
			if( !$rs = $this -> RoutingPathScanner( self::APP_FOLDER_MODEL, self::$context_holder -> config -> env -> MODULES['Front'] ) )
			{
				throw new \Exception('Front Module is invalid or not found. Check MODULES.php in /app/config/env/');
			}

		}else{

			/**
			* Debug purposes
			*/
			//echo $clss; exit;
			//echo urldecode( $clss ); //exit;
			//echo PHP_EOL . $module_uri; exit;
			//echo mb_detect_encoding( urldecode( $clss ) ); exit;

			/**
			* Scanning into main Modules folder.
			*/
			if( !$rs = $this -> RoutingPathScanner( self::APP_FOLDER_MODEL, $clss ) )
			{

				/**
				* Debug purposes
				*/
				//echo $module_uri; //exit;
				//print_r( $rs );

				/**
				* Scanning routing module.
				*/
				//if( !$rs = $this -> RoutingPathScanner( self::APP_FOLDER_ROUTING, $module_uri ) )
				if( !$rs = $this -> RoutingPathScanner( self::APP_FOLDER_ROUTING, $clss ) )
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
			* If model or routing is not found, loads 404 (not found) Module
			* However, if 404 module not exists, throws exception.
			*/
			if( !$rs )
			{
				$rs = $this -> LoadNotFoundModule();
			}

		}

		/**
		* Debug purposes
		*/
		//echo $rs -> class . PHP_EOL . self::$context_holder -> config -> env -> MODULES['404']; exit;


		/**
		* Check if the class name is valid.
		* The occurrency of duplicated slash may cause fatal errors.
		* If class name is not the default and valid 404 not found module, check the name against the filtered name.
		*/
		if( $rs -> class != self::$context_holder -> config -> env -> MODULES['404'] )
		{
			/**
			* If DIRECTORY_SEPARATOR is backslash, this prevents conflict with forward slash.
			*/
			$cl_name = str_replace( DIRECTORY_SEPARATOR, '/', $rs -> class );

			/**
			* Debug purposes
			*/
			//echo $cl_name . PHP_EOL;
			//echo 'replace: ' . preg_replace( '!\/+!', '/', $str ); exit;

			/**
			* Removing the duplicated occurrences of forward slash, if exists.
			*/
			$str = preg_replace( '!\/+!', '/', $cl_name );

			/**
			* Removing the forward slash from the beginning of string, if exists.
			* Don't need remove on the end because is removed previously.
			*/
			$str = ltrim( $str, '\/$@' );
			//echo $str; exit;

			/**
			* Checking the name against the filtered name.
			* If different, means that the module name contains duplicated slashes.
			* For this case, the default strict mode will load the 404 not found module.
			*/
			if( $cl_name != $str )
			{
				$rs = $this -> LoadNotFoundModule();
			}

			/**
			* Clear used variables.
			*/
			unset( $cl_name, $str );

		}



		/**
		* Retrieves method and mode_rewrite parameters from $request property to Routing() output result.
		*/
		$rs -> method       = $this -> request['method'];
		$rs -> mode_rewrite = $this -> request['mode_rewrite'];

		/**
		* Clear $request property
		*/
		$this -> request = null;

		/**
		* Debug purposes
		*/
		//var_dump( $rs ); exit;
		//echo PHP_EOL . PHP_EOL; print_r( $rs ); echo PHP_EOL . __FILE__ . exit;

		/**
		* Debug purposes
		* Include the module file
		*/
		//require_once( $rs -> path );

		/**
		* Debug purposes
		* Sample to load a module
		$clss = '\Tipui\App\Model\\' . str_replace( DIRECTORY_SEPARATOR, '\\', $rs -> class );
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
		* Folders levels limit
		* @see Docs/Core/GetConf/URL
		*/
		$i           = self::$context_holder -> config -> env -> URL['FOLDER_LEVELS'];

		/**
		* (boolean) true: path found, false: path not found
		*/
		$goal        = false;

		/**
		* Holds the results, if exists
		*/
		$rs          = (object) array();

		/**
		* Handles the Routing/Module class
		*/
		$routing     = false;

		/**
		* Handles the URL parameters
		*/
		$path_params = false;

		/**
		* Builds the base path
		*/
		$path = TIPUI_APP_PATH . $path_base . DIRECTORY_SEPARATOR . $clss . TIPUI_CORE_ENV_FILE_EXTENSION;

		while( $i > 0 and !$goal and !empty( $clss ) )
		{

			if( $path_base == self::APP_FOLDER_MODEL )
			{

				/**
				* Debug purposes
				*/
				//echo PHP_EOL . PHP_EOL . $path . ': ';

				if( file_exists( $path ) )
				{
					/**
					* Debug purposes
					*/
					//echo 'ok';
					//echo $clss; exit;

					/**
					* The 'class' parameter value must be backslash, because linux systems (or environment where DIRECTORY_SEPARATOR is normal slash /)
					* The reason is that the namespaces uses backslash \
					*/
					$goal = true;
					$rs -> path  = $path;
					$rs -> class = str_replace( '/', '\\', $clss );
				}else{
					/**
					* Debug purposes
					*/
					//$clss = substr( $clss, 0, strrpos( $clss, DIRECTORY_SEPARATOR ) );
					//echo PHP_EOL . 'ng [next] ' . $clss;
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
					$rs -> path = TIPUI_APP_PATH . self::APP_FOLDER_MODEL . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $rs -> class ) . TIPUI_CORE_ENV_FILE_EXTENSION;

					if( !file_exists( $rs -> path ) )
					{
						throw new \Exception('[' . $path_base . '] Alias class not found. Check \Tipui\App\Routing\Modules');
					}

				}else{
					/*
					$clss = substr( $clss, 0, strrpos( $clss, self::$context_holder -> config -> env -> URL['PFS'] ) );
					//echo PHP_EOL . 'ng [next] ' . $clss;
					*/
				}

			}

			if( !$goal )
			{
				/**
				* Debug purposes
				*/
				//echo strrev( $clss ); exit;

				/**
				* Breaks each subpath into array $path_params.
				* Rename $clss, removing the subpath until find the proper file path.
				*/
				$path_params[] = substr( $clss, strrpos( $clss, DIRECTORY_SEPARATOR ) + 1 );
				$clss = substr( $clss, 0, strrpos( $clss, DIRECTORY_SEPARATOR ) );

				/**
				* Rebuilding new path
				*/
				unset( $path );
				$path = TIPUI_APP_PATH . $path_base . DIRECTORY_SEPARATOR . $clss . TIPUI_CORE_ENV_FILE_EXTENSION;

			}else{

				/**
				* Debug purposes
				*/
				//echo PHP_EOL . 'path found! ' . $path . PHP_EOL;

				/**
				* If $path_params is array, must reverse the indexes order.
				*/
				$rs -> params = ( $path_params ) ? array_reverse( $path_params ) : $path_params;

				/**
				* Debug purposes
				*/
				//$rs -> params = $path_params;

			}

			/**
			* Debug purposes
			*/
			//echo PHP_EOL;

			/**
			* Decrementing the folders level limit
			* @see Docs/Core/GetConf/URL
			*/
			$i--;

		}

		/**
		* Clear used variables
		*/
		unset( $path, $routing, $i );

		/**
		* If $path_params is array, must reverse the indexes order.
		*/
		$rs -> params = ( $path_params ) ? array_reverse( $path_params ) : $path_params;

		/**
		* If parameters are not in mode rewrite format (friendly url), must receive 'params' from $this -> request property.
		*/
		if( !$this -> request['mode_rewrite'] )
		{
			$rs -> params = $this -> request['params'];
		}

		/**
		* Clear used variables
		*/
		unset( $path_params );

		/**
		* Debug purposes
		*/
		//echo PHP_EOL . PHP_EOL;
		//print_r( $rs ); //exit;
		//print_r( $path_params ); //exit;
		//echo PHP_EOL . 'path: ' . $path;
		//exit;

		return ( ( $goal === true ) ? $rs : false );

	}

	/**
	* Loads the 404 not found module.
	*/
	public function LoadNotFoundModule()
	{

		if( !$rs = $this -> RoutingPathScanner( self::APP_FOLDER_MODEL, self::$context_holder -> config -> env -> MODULES['404'] ) )
		{
			throw new \Exception('404/NotFound Module is invalid or not found. Check MODULES.php in /app/config/env/');
		}

		return $rs;

	}

	/**
	* Changes the module to load.
	*/
	public function ChangeModuleAndLoad( $from_module, $to_module )
	{

		/**
		* Must keep the request method from original request.
		*/
		$to_module -> method       = $from_module -> method;
		$to_module -> mode_rewrite = $from_module -> mode_rewrite;

		/**
		* Saving the information of the module that was previously loaded.
		*/
		//$to_module['changed_from'] = $from_module;
		$to_module -> changed_from = $from_module;

		/**
		* Loading Model Class.
		*/
		$clss = '\Tipui\\' . TIPUI_APP_FOLDER_NAME . '\\' . self::APP_FOLDER_MODEL . '\\' . $to_module -> class;
		$to_module -> class_namespace = $clss;

		return (object) array( 'module_info' => $to_module, 'module_object' => new $clss );

	}

}