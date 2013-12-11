<?php

/**
* @class  GetElementProperty
* @file   GetElementProperty.php
* @brief  GetElementProperty HTML Helper Form Elements functions.
* @date   2013-12-04 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-07 21:06:00
*
* @see: \Tipui\Builtin\Libs\Form\GetField;
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class GetElementProperty extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add new input element
	*
	* If @param $property is false or index not exists, returns entire array.
	*/
	public function Exec( $name, $property = false )
	{

		/**
		* Load paremeter properties if is empty
		*/
		( empty( self::$parameter ) ) ? self::$parameter = \Tipui\Builtin\Libs\Form::GetElement( $name ) : '';

		/**
		* Returns the results
		*/
		return ( $property and isset( self::$parameter[$property] ) ) ? self::$parameter[$property] : self::$parameter;

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;

	}

}