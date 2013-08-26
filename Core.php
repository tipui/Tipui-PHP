<?php

/**
* @class  Core
* @file   Core.php
* @brief  Engine's core.
* @date   2013-06-21 02:00:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-07-08 02:09:00
*/

namespace Tipui;

class Core
{
	/**
	* Handles the enviroment settings
	*/
	private $env;

	/**
	* (boolean) true: CLI, false: HTTP
	*/
	private $sapi_is_cli;

	/**
	* Handles the request results
	*/
	private $request;

	/**
	* Initiates general settings
	*/
    public function __construct()
    {

		/**
		* Debug purposes
		*/
		//echo '[' . time() . ']' . PHP_EOL;

		/**
		* Detect if script is running over HTTP or CLI
		*/
		$this -> sapi_is_cli = ( php_sapi_name() == 'cli' ) ? true : false;

		/**
		* Loads general settings
		*/
		$this -> LoadSettings();

		return null;
	}

	/**
	* Registered enviroment settings files
	*/
    private function GetRegisteredSettingsFiles( )
    {
		return array( 'BOOTSTRAP', 'URL', 'PHP', 'ENGINE', 'TIME_ZONE', 'COOKIES', 'MODULES', 'TEMPLATES', 'INTERFACE' );
	}

	/**
	* Loads bootstrap and general files for enviroment settings
	*/
    private function LoadSettings()
    {

		/**
		* @brief Abstract the enviroment settings from the main file
		*/
		$script_path   = $_SERVER['SCRIPT_FILENAME'];
		$app_conf_path = TIPUI_APP_PATH . 'config' . DIRECTORY_SEPARATOR;

		$env_json = json_decode( file_get_contents( $app_conf_path . 'env' . DIRECTORY_SEPARATOR . 'ENV.json' ), true );
		$env_name = strpos( str_replace( '/', DIRECTORY_SEPARATOR, $script_path ), DIRECTORY_SEPARATOR . $env_json['DEV'] . '-' ) ? $env_json['DEV'] : $env_json['PRODUCTION'] ;

		//print_r( $env_json ); exit;

		// stores to $env property
		$env = array( 
				'CONF_PATH'        => $app_conf_path,
				'FILE_EXTENSION'   => '.php',
				'PRODUCTION'       => $env_json['PRODUCTION'],
				'DEV'              => $env_json['DEV'],
				'NAME'             => $env_name,
				'PATH'             => $app_conf_path . 'env' . DIRECTORY_SEPARATOR . $env_name . DIRECTORY_SEPARATOR,
		);

		$this -> SetENV( 'ENV', $env );

		unset( $script_path, $env_json, $env_name, $app_conf_path );

		/**
		* @brief General constants specific for the enviroment
		*/
		$env_files = $this -> GetRegisteredSettingsFiles();
		foreach( $env_files as $v )
		{
			require_once( $env['PATH'] . $v . $env['FILE_EXTENSION'] );
		}

		/**
		* $array variable is declared into included files
		*/
		unset( $env, $env_files, $v, $array );

		return null;
	}

	/**
	* Returns method of core data storage
	* array, session, sqlite, etc
	*/
    public function Autoloader( )
    {
		require_once( TIPUI_PATH . $this -> env['ENGINE']['builtin_folder'] . DIRECTORY_SEPARATOR . 'Autoloader' . $this -> env['ENV']['FILE_EXTENSION'] );
		$a = new Builtin\Autoloader;
		unset( $a );
		return null;
	}

	/**
	* Returns method of core data storage
	* array, session, sqlite, etc
	*/
    public function GetCoreStorageMode( )
    {
		return $this -> env['ENGINE']['env_storage'];
	}

	/**
	* Stores enviroment config data
	*/
    private function SetENV( $index, $value )
    {
		$this -> env[ $index ] = $value;
	}

	/**
	* Get stored enviroment config data
	*/
    public function GetENV( $index = false, $subindex = false )
    {

		if( !$index )
		{
			return $this -> env;
		}else if( isset( $this -> env[ $index ] ) ){
			if( !$subindex )
			{
				return $this -> env[ $index ];
			}else if( isset( $this -> env[ $index ][ $subindex ] ) ){
			
				return $this -> env[ $index ][ $subindex ];
			}
		}else{
			//print_r( $this -> env );
			//echo '[EMPTY] ' . $index . PHP_EOL;
			//throw new \Exception('Index not exists.');
		}

		return false;
	}

	/**
	* (boolean) Retrieves the value os property
	*/
    public function IsCliMode( )
    {
		return $this -> sapi_is_cli;
	}

	/**
	* Abstracts parameters from HTTP or CLI
	*/
    public function Request( )
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
    public function Routing( )
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

		// folders levels
		$i       = $this -> env['URL']['FOLDER_LEVELS'];

		// (boolean) true: path found, false: path not found
		$goal    = false;
		
		// Handles the Routing/Module class
		$routing = false;

		while( $i > 0 and !$goal and !empty( $clss ) )
		{
			if( $path_base == 'Model' )
			{

				$path = TIPUI_APP_PATH . $path_base . DIRECTORY_SEPARATOR . $clss . $this -> env['ENV']['FILE_EXTENSION'];

				//echo $path . ': ';

				if( file_exists( $path ) )
				{
					//echo 'ok';
					//echo $clss; exit;
					$goal = true;
					$rs   = array( 'path' => $path, 'class' => $clss );
				}else{
					$clss = substr( $clss, 0, strrpos( $clss, DIRECTORY_SEPARATOR ) );
					//echo 'ng [next] ' . $clss;
				}

			}else{

				if( !$routing )
				{
					$routing = new \Tipui\App\Routing\Modules;
				}

				if( $rs = $routing -> Get( $clss ) )
				{
					//echo 'ok';
					//print_r( $rs ); exit;
					$goal       = true;
					$rs['path'] = TIPUI_APP_PATH . 'Model' . DIRECTORY_SEPARATOR . str_replace( $this -> env['URL']['PFS'], DIRECTORY_SEPARATOR, $rs['class'] ) . $this -> env['ENV']['FILE_EXTENSION'];

					if( !file_exists( $rs['path'] ) )
					{
						throw new \Exception('[' . $path_base . '] Alias class not found. Check \Tipui\App\Routing\Modules');
					}

				}else{
					$clss = substr( $clss, 0, strrpos( $clss, $this -> env['URL']['PFS'] ) );
					//echo 'ng [next] ' . $clss;
				}

			}
			//echo PHP_EOL;

			$i--;
		}
		//echo $path;
		//exit;

		unset( $routing, $i );

		return $goal ? $rs : false;

	}

	/**
	* Browse headers and general info (REFERER, IP, Browser name, version, language, etc)
	*/
    public function Browse( )
    {
		return Builtin\Libs\Browse::GetData();
	}
}
?>