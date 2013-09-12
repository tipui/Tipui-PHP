<?php

/**
* @class  Form
* @file   Form.php
* @brief  Form functions.
* @date   2013-08-31 23:37:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-10 15:30:00
*/

namespace Tipui\Builtin\Libs;

use \Tipui\Builtin\Libs\DataRules as DataRules;

class Form
{

	/**
	* Handles form parameters
	*/
	public static $parameters = null;


	/**
	* Sets form fields rules
	*/
	public static function SetField( $name, $rule, $required = true )
	{
		/**
		* Creates parameter rules
		*/
		self::$parameters[$name] = DataRules::Get( $rule, $required );

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
	}

	/**
	* Gets form field
	*/
	public static function GetField( $name = false, $property = false )
	{

		/**
		* [code]self::$parameters[/code] must be an valid array
		*/
		if( self::$parameters == null )
		{
			throw new \Exception('Form::$parameters is null');
		}

		/**
		* If [code]$name[/code] is false, returns entire array
		*/
		if( !$name )
		{
			return self::$parameters;
		}else if( isset( self::$parameters[$name] ) ){
			if( !$property )
			{
				/**
				* Returns entire index property if exists
				*/
				if( isset( self::$parameters[$name][$property] ) )
				{
					/**
					* Returns index property
					*/
					return self::$parameters[$name][$property];
				}else{
					/**
					* Property not exists exception error
					*/
					throw new \Exception('Form::$parameters[' . $name . '][' . $property . '] not found.');
				}
			}else{
				/**
				* Returns entire index array
				*/
				return self::$parameters[$name];
			}
		}else{
			/**
			*  Index name not exists exception error
			*/
			throw new \Exception('Form::$parameters index "' . $name . '" not found.');
		}

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
	}

}