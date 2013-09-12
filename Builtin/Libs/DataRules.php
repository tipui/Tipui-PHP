<?php

/**
* @class  DataRules
* @file   DataRules.php
* @brief  DataRules functions.
* @date   2013-09-01 00:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-12 13:30:00
*/

namespace Tipui\Builtin\Libs;

use \Tipui\Builtin\Libs as Libs;

class DataRules
{

	protected static $rules;

	public static function Get( $rule = 'general', $required = true )
	{

		/**
		* If the rule is not handled in array $rules, then, load from DataRules file.
		*/
		if( !isset( self::$rules[$rule] ) )
		{

			/**
			* Converting the $rule string slashes.
			* If the Operational System of enviroment uses normal slash as directory separator, then, the string backslash will be replaced with normal slash
			*/
			$rule_file = 'Helpers' . DIRECTORY_SEPARATOR . 'DataRules' . DIRECTORY_SEPARATOR . ( ( DIRECTORY_SEPARATOR != '\\' ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $rule ) : $rule ) . \Tipui\Core::CORE_ENV_FILE_EXTENSION;

			/**
			* App custom file (priority)
			*/
			$path = TIPUI_APP_PATH . $rule_file;

			if( !file_exists( $path ) )
			{
				/**
				* Overriding path
				* Only for override framework buintin DataRules files
				*/
				$path = TIPUI_APP_PATH . 'Override' . DIRECTORY_SEPARATOR . 'Builtin' . DIRECTORY_SEPARATOR . $rule_file;

				/**
				* Check if rule overriding exists
				*/
				if( !file_exists( $path ) )
				{
					/**
					* Overriding file not exists. 
					* Loads from defaults (core builtin), if exists too.
					*/
					$path = TIPUI_PATH . 'Builtin' . DIRECTORY_SEPARATOR . $rule_file;

					if( !file_exists( $path ) )
					{
						throw new \Exception('Data Rule "' . $rule . '" not found.');
					}

				}
			}

			require_once( $path );

			self::$rules[$rule] = $rs;
			unset( $rs, $path, $rule_file );

		}

		/**
		* The rule's state.
		* (boolean) true: is required, false: not required
		*/
		self::$rules[$rule]['required'] = $required;

		return self::$rules[$rule];

		/*
		Recommend usage with Libs\Form
		Form::SetField( [field name], [rule] );

		[Samples]
		Form::SetField( 'email', 'email' );

		Calling from subfolders into DataRules folder (DataRules/Foo/email.php):
		Form::SetField( 'email', 'Foo/email' );
		*/
	}

}