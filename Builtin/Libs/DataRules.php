<?php

/**
* @class  DataRules
* @file   DataRules.php
* @brief  DataRules functions.
* @date   2013-09-01 00:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:55:00
*/

namespace Tipui\Builtin\Libs;

use \Tipui\Builtin\Libs as Libs;

class DataRules
{

	protected static $rules;

	public static function Get( $rule = 'general', $required = true )
	{

		/**
		* Converting the $rule string slashes.
		* If the Operational System of enviroment uses normal slash as directory separator, then, the string backslash will be replaced with normal slash
		*/
		$rule_file = ( DIRECTORY_SEPARATOR != '\\' ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $rule ) : $rule;

		/**
		* If the rule is not handled in array $rules, then, load from DataRules file.
		*/
		if( !isset( self::$rules[$rule] ) )
		{

			/**
			* Building the overriding file path
			*/
			$rule_file = 'Builtin' . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'DataRules' . DIRECTORY_SEPARATOR . $rule_file . \Tipui\Core::CORE_ENV_FILE_EXTENSION;
			$path      = TIPUI_APP_PATH . 'Override' . DIRECTORY_SEPARATOR . $rule_file;

			/**
			* Clear the used vars
			*/
			//unset( $c, $core_data );

			/**
			* Check if rule overriding exists
			*/
			if( !file_exists( $path ) )
			{
				/**
				* Rule overriding not exists. 
				* Loads from defaults.
				*/
				$path = TIPUI_PATH . $rule_file;

				if( !file_exists( $path ) )
				{
					throw new \Exception('Data Rule "' . $rule . '" not found.');
				}
			}

			require_once( $path );
			self::$rules[$rule] = $rs;
			unset( $rs );

		}

		unset( $rule_file, $path );

		/**
		* The rule's state.
		* (boolean) true: is required, false: not required
		*/
		self::$rules[$rule]['required'] = $required;

		return self::$rules[$rule];

	}

}