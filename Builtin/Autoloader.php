<?php

/**
* @class  Autoloader
* @file   Autoloader.php
* @brief  Autoloading routines.
* @date   2013-06-21 20:46:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-17 01:25:00
*/

namespace Tipui\Builtin;

class Autoloader
{

	/**
	* (boolean)
	* true: is overriding
	* false: not overriding
	*/
	private $overriding;

	/**
	* Starts the parent (Core) and the method to process the autoloading procedures.
	*/
	function __construct()
	{
		/**
		* Registering the method for autoload process
		*/
		spl_autoload_register( array( '\Tipui\Builtin\Autoloader', 'Init' ) );
	}

	/**
	* Erases properties.
	*/
	function __destruct()
	{
		$this -> overriding = null;
	}

	private function Init( $class_name )
	{

		/**
		* Debug purposes
		*/
		//echo TIPUI_PATH . PHP_EOL . TIPUI_APP_PATH . PHP_EOL . __NAMESPACE__ . PHP_EOL . $class_name . PHP_EOL . PHP_EOL;

		/**
		* Converting the $class_name string into array. If the Operational System of enviroment uses normal slash as directory separator, then, the namespace backslash will be replaced with normal slash
		*/
		( DIRECTORY_SEPARATOR != '\\' ) ? $class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) : '';
		$ns = explode( DIRECTORY_SEPARATOR, $class_name  );
		array_shift( $ns ); // removes "Tipui\" from namespace

		/**
		* Initiates the override status
		*/
		$this -> overriding = false;

		/**
		* Get file name extension of PHP Files
		*/
		$file_extension = TIPUI_CORE_ENV_FILE_EXTENSION;

		/**
		* determines which path is the base path, based on namespace based class name
		*/
		if( strpos( $class_name, __NAMESPACE__ ) === 0 )
		{

			/**
			* App's override path
			* Check if folder for overriding exists.
			* If exists, then, check if file "$class_name" exists.
			*/
			if( count( $ns ) >= 2 )
			{
				if( $ns[0] == \Tipui\Core::ENV_FOLDER_BUILTIN )
				{
					$base_path = TIPUI_APP_PATH . TIPUI_FOLDER_OVERRIDE . DIRECTORY_SEPARATOR;
					if( file_exists( $base_path ) and is_dir( $base_path ) )
					{
						/**
						* Including the file, if exists
						*/
						$file = $base_path . implode( DIRECTORY_SEPARATOR, $ns ) . $file_extension;
						//echo $file; exit;
						if( file_exists( $file ) )
						{

							require_once( $file );

							$this -> overriding = true;

						}else{
							/**
							* Debug purposes
							*/
							//echo 'ng' . PHP_EOL;
						}

						unset( $file );
					}
					unset( $base_path );
				}
			}

			/**
			* Framework's builtin path
			* Must check if is applying override.
			*/
			if( !$this -> overriding )
			{
				$base_path = TIPUI_PATH;
			}

			//print_r( $ns ); echo PHP_EOL;
			//echo $class_name . PHP_EOL;

		}else{
			/**
			* App's path
			*/
			$base_path = TIPUI_APP_PATH;
			array_shift( $ns ); // removes "App\" from namespace
		}

		/**
		* Debug purposes
		*/
		//print_r( $ns );

		/**
		* Including the file, if exists
		*/
		if( !$this -> overriding )
		{
			$file = $base_path . implode( DIRECTORY_SEPARATOR, $ns ) . $file_extension;
			if( file_exists( $file ) )
			{
				require_once( $file );
			}else{
				throw new \Exception('Class "' . $class_name . '" not found.');
			}
			unset( $base_path, $file );
		}

		/**
		* Clear variables
		*/
		unset( $class_name, $ns, $file_extension );

		return null;
	}
}

?>