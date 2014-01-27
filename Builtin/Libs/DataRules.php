<?php

/**
* @class  DataRules
* @file   DataRules.php
* @brief  DataRules functions.
* @date   2013-09-01 00:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-22 20:30:00
*/

namespace Tipui\Builtin\Libs;

use \Tipui\Builtin\Libs as Libs;

class DataRules
{

	/**
	* Handles the element name parameter
	*/
	const NAME           = 'name';

	/**
	* Handles the rule name parameter
	* [review] [deprecated]
	*/
	const RULE           = 'rule';

	/**
	* Handles the required parameter
	*/
	const REQUIRED       = 'required';

	/**
	* Handles the element value parameter
	*/
	const VALUE          = 'value';
 
	/**
	* Defines the default value
	*/
	const DEFAULTS       = 'default';

	/**
	* Element type (text, hidden, password, checkbox, radio, file, select, textearea)
	*/
	const TYPE           = 'type';

	/**
	* Multiple choices elements.
	* Defines the exact quantity of choices.
	*/
	const SELECT_EXACT   = 'select_exact';

	/**
	* Multiple choices elements.
	* Defines the maximum choices required.
	*/
	const SELECT_MIN     = 'select_min';

	/**
	* Multiple choices elements.
	* Defines the maximum choices required.
	*/
	const SELECT_MAX     = 'select_max';

	/**
	* Element type as array
	* [review] [deprecated]
	*/
	const MULTIPLE       = 'multiple';

	/**
	* Defines the element/field size
	*/
	const SIZE           = 'size';

	/**
	* For select, radio and checkbox types
	*/
	const OPTIONS        = 'options';

	/**
	* For select optgroup
	*/
	const OPTGROUP       = 'optgroup';

	/**
	* Validation type (int, float, char, date, time, datetime, upload)
	*/
	const VALIDATION     = 'validation';

	/**
	* Filter the value parameter before assign to data rule parameter 'value'
	*/
	const PRE_FILTER     = 'pre_filter';

	/**
	* The exact value parameter
	*/
	const EXACT_VALUE    = 'exact_value';

	/**
	* The value parameter string length
	*/
	const MIN_LENGTH     = 'min_length';
	const MAX_LENGTH     = 'max_length';
	const EXACT_LENGTH   = 'exact_length';

	/**
	* The error parameter, if error exists after sanitizes
	* @see: \Tipui\Builtin\Libs\DataValidation::Sanitize()
	*/
	const ERROR          = 'error';

	/**
	* For textarea type
	*/
	const COLS           = 'cols';
	const ROWS           = 'rows';

	/**
	* For files (file upload)
	*/

	/**
	* For file maximum and minimum size defined in bytes
	*/
	const MAX_SIZE       = 'max_size';
	const MIN_SIZE       = 'min_size';

	/**
	* Media dimensions (jpg, gif, bmp, swf, etc)
	*/
	const EXACT_WIDTH    = 'exact_width';
	const EXACT_HEIGHT   = 'exact_height';
	const MIN_WIDTH      = 'min_width';
	const MAX_WIDTH      = 'max_width';
	const MIN_HEIGHT     = 'min_height';
	const MAX_HEIGHT     = 'max_height';

	/**
	* File types allowed
	* ie: array('jpg', 'gif', 'png')
	*/
	const CONTENT_TYPES  = 'content_types';

	/**
	* Handles the rules requested
	*/
	protected static $rules;

	/**
	* Returns file name/path
	*/
	public static function RuleFileName( $rule = 'general' )
	{
		/**
		* Converting the $rule string slashes.
		* If the Operational System of environment uses normal slash as directory separator, then, the string backslash will be replaced with normal slash
		*/
		$rule = strtolower( $rule );
		return \Tipui\Core::ENV_FOLDER_HELPERS . DIRECTORY_SEPARATOR . \Tipui\Core::ENV_FOLDER_DATARULES . DIRECTORY_SEPARATOR . ( ( DIRECTORY_SEPARATOR != '\\' ) ? str_replace( '\\', DIRECTORY_SEPARATOR, $rule ) : $rule ) . TIPUI_CORE_ENV_FILE_EXTENSION;
	}

	/**
	* Returns the rule data file content.
	*/
	public static function Get( $rule = 'general', $required = true )
	{

		/**
		* If the rule is not handled in array $rules, then, load from DataRules file.
		*/
		if( !isset( self::$rules[$rule] ) )
		{

			$rule_file = self::RuleFileName( $rule );

			/**
			* App custom file (priority)
			*/
			$path = TIPUI_APP_PATH . $rule_file;

			if( !file_exists( $path ) )
			{

				$folder_builtin = \Tipui\Core::ENV_FOLDER_BUILTIN;

				/**
				* Overriding path
				* Only for override framework builtin DataRules files
				*/
				$path = TIPUI_APP_PATH . TIPUI_FOLDER_OVERRIDE . DIRECTORY_SEPARATOR . $folder_builtin . DIRECTORY_SEPARATOR . $rule_file;

				/**
				* Check if rule overriding exists
				*/
				if( !file_exists( $path ) )
				{
					/**
					* Overriding file not exists. 
					* Loads from defaults (core builtin), if exists too.
					*/
					$path = TIPUI_PATH . $folder_builtin . DIRECTORY_SEPARATOR . $rule_file;

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
		self::$rules[$rule][self::REQUIRED] = $required;

		return self::$rules[$rule];

		/*
		Recommend usage with Libs\Form
		Form::SetElement( [field name], [rule] );

		[Samples]
		Form::SetElement( 'email', 'email' );

		Calling from subfolders in DataRules folder (DataRules/Foo/email.php):
		Form::SetElement( 'email', 'Foo/email' );
		*/
	}

}