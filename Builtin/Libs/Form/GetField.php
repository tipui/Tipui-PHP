<?php

/**
* @class  GetField
* @file   GetField.php
* @brief  GetField Header functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

class GetField extends \Tipui\Builtin\Libs\Form
{

	/**
	* Gets form field
	*/
	public function Exec( $name = false, $property = false )
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