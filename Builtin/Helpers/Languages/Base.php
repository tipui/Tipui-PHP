<?php

/**
* @class  Base
* @file   Base.php
* @brief  Base Helper/Languages functions.
* @date   2014-02-28 00:37:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-03 19:28:00
*/

namespace Tipui\Builtin\Helpers\Languages;
//use \Tipui\Builtin\Helpers\Languages as Languages;

class Base extends \Tipui\Builtin\Helpers\Languages
{

	/**
	* Switch the language
	*
	* Set the language code for current instance
	* \Tipui\Builtin\Helpers\Languages::Base( 'base_path' );
	*
	* Setting language and writing the label at same line
	* \Tipui\Builtin\Helpers\Languages::Base( 'base_path' ) -> Label( 'label_index' );
	*/
	public function Exec( $base_path = null )
	{

		/**
		* Setting the property [code]$lang_code[/code], if empty.
		*/
		if( empty( self::$lang_code ) )
		{
			self::Lang();
		}

		/**
		* Calling backtrace to get the class name that is invoking the label
		*/
		$bt = debug_backtrace();

		/**
		* Debug purposes
		*/
		//print_r( $bt ); exit;
		//echo TIPUI_PATH . PHP_EOL;
		//echo TIPUI_APP_PATH . PHP_EOL;
		//echo TIPUI_APP_PUBLIC_PATH . PHP_EOL;
		//return $base_path;
		//echo 'aa: ' . $base_path . PHP_EOL;
		//echo strpos( $base_path, TIPUI_APP_PATH );
		//echo strpos( $base_path, TIPUI_PATH );
		//exit;

		//$file_path = $bt[3]['file'];

		if( empty( $base_path ) )
		{

			if( $file_extension = pathinfo( $bt[3]['file'], PATHINFO_EXTENSION ) )
			{
				$base_path = substr( $bt[3]['file'], 0, -( strlen( $file_extension ) + 1 ) );
			}else{
				$base_path = $bt[3]['file'];
			}

		}else{
			//$base_path = dirname( $bt[3]['file'] );
		}

		/**
		* Changing to base dir os current file that is calling this instance
		* This is needed when [code]$base_path[/code] is relative
		*/
		chdir( dirname( $bt[3]['file'] ) );

		/**
		* Debug purposes
		* http://stackoverflow.com/questions/4049856/replace-phps-realpath/4050444#4050444
		* http://www.php.net/manual/en/function.realpath.php
		*/
		//echo $bt[3]['file'] . PHP_EOL;
		//echo $base_path . PHP_EOL;
		//echo 'Base(): ' . realpath( $base_path ) . PHP_EOL . PHP_EOL; //exit;

		/**
		* The [code]$base_path[/code] must be absolute, in order to work.
		*/
		self::$base_path = realpath( $base_path );

		/**
		* Mounting the path base
		*/
		/*
		if( $file_extension = pathinfo( $base_path, PATHINFO_EXTENSION ) )
		{
			self::$base_path = substr( $base_path, 0, -( strlen( $file_extension ) + 1 ) );
		}else{
			self::$base_path = $base_path;
		}
		*/
		//echo 'vv: ' . $base_path . PHP_EOL;

		/**
		* Canonicalizing the path
		*/
		//chdir( self::$base_path );
		//echo self::$base_path . PHP_EOL . realpath($base_path) . PHP_EOL . PHP_EOL; //exit;

		/**
		* Checking if is calling from builtin path
		*/
		$pos = strpos( self::$base_path, TIPUI_PATH );
		if( $pos === (int)0 )
		{
			
		}

		/**
		* Debug purposes
		*/
		//echo gettype($pos);

		/**
		* Validating the return of strpos()
		*/
		if( $pos === (int)0 )
		{

			/**
			* Called from builtin paths.
			*
			* Mounting path to builtin.
			*/
			self::$file_path = str_replace( TIPUI_PATH, TIPUI_PATH . \Tipui\Core::LANGUAGE_FOLDER . DIRECTORY_SEPARATOR, self::$base_path );

		}else{

			/**
			* Called from app paths.
			*
			* Checking if is calling from app/Template folder
			*/
			$app_template_base_path = TIPUI_APP_PATH . \Tipui\Core::GetConf() -> TEMPLATES -> FOLDER; // . DIRECTORY_SEPARATOR
			if( strpos( self::$base_path, $app_template_base_path ) === (int)0 )
			{

				//echo 'oo: ' . self::$base_path . PHP_EOL; //exit;
				self::$file_path = TIPUI_APP_PATH . \Tipui\Core::LANGUAGE_FOLDER . substr( self::$base_path, strlen( $app_template_base_path ) );
				//echo 'bb: ' . self::$file_path . PHP_EOL; //exit;

			}else{

				/**
				* Called from app paths.
				*
				* Checking if is calling from app/Override folder
				*/
				$pos = strpos( self::$base_path, TIPUI_APP_PATH . TIPUI_FOLDER_OVERRIDE . DIRECTORY_SEPARATOR );
				if( $pos === (int)0 )
				{

					/**
					* Changing the path to builtin.
					*/
					self::$file_path = str_replace( TIPUI_APP_PATH . TIPUI_FOLDER_OVERRIDE . DIRECTORY_SEPARATOR, TIPUI_PATH . \Tipui\Core::LANGUAGE_FOLDER . DIRECTORY_SEPARATOR, self::$base_path );

				}else{

					/**
					* Mounting path to app folder.
					*/
					self::$file_path = str_replace( TIPUI_APP_PATH, TIPUI_APP_PATH . \Tipui\Core::LANGUAGE_FOLDER . DIRECTORY_SEPARATOR, self::$base_path );

				}

			}

		}

		/**
		* Debug purposes
		*/
		//echo dirname( self::$file_path ) . PHP_EOL;
		//echo 'hh: ' . self::$file_path . PHP_EOL;

		/**
		* If current path have no folder under current path base with the same name of the file basename, will change to the base of the given path.
		*/
		if( !is_dir( self::$base_path ) )
		{
			self::$file_path = dirname( self::$file_path );
		}

		/**
		* Checking for existing builtin translation files overriding
		*/
		if( $pos === (int)0 )
		{

			/**
			* Change the base is enought to mount the path to app folders.
			*/
			//echo self::$lang_code . PHP_EOL; 
			//echo str_replace( TIPUI_PATH, TIPUI_APP_PATH, self::$file_path . DIRECTORY_SEPARATOR . self::$lang_code . TIPUI_CORE_ENV_FILE_EXTENSION ); exit;
			if( file_exists( $app_file = str_replace( TIPUI_PATH, TIPUI_APP_PATH, self::$file_path . DIRECTORY_SEPARATOR . self::$lang_code . TIPUI_CORE_ENV_FILE_EXTENSION ) ) )
			{

				/**
				* File for [code]self::$lang_code[/code] was found.
				*/
				self::$file_path = $app_file;

			}else if( file_exists( $app_file = str_replace( TIPUI_PATH, TIPUI_APP_PATH, self::$file_path . DIRECTORY_SEPARATOR . \Tipui\Core::BUILTIN_LANG_CODE . TIPUI_CORE_ENV_FILE_EXTENSION ) ) ){

				/**
				* File for [code]self::$lang_code[/code] was not found. Loading the file of Core default language code [code]\Tipui\Core::BUILTIN_LANG_CODE[/code]
				*/
				self::$file_path = $app_file;

			}

			unset( $app_file );

		}else{

			/**
			* Concatenating the language file, according to the language code
			*/
			self::$file_path .= DIRECTORY_SEPARATOR . self::$lang_code . TIPUI_CORE_ENV_FILE_EXTENSION;

		}

		/**
		* Checking if final path exists
		*/
		if( !file_exists( self::$file_path ) )
		{
			throw new \Exception('Translation file "' . self::$file_path . '" not found.');
		}

		//print_r( self::$labels );
		
		/**
		* Debug purposes
		*/
		//echo self::$base_path . PHP_EOL;
		//echo $base_path . PHP_EOL;
		//echo basename( $base_path, TIPUI_CORE_ENV_FILE_EXTENSION ) . PHP_EOL;
		//echo self::$file_path . PHP_EOL;
		//exit;

        return new self;

	}

}