<?php

/**
* @class  GetElementProperty
* @file   GetElementProperty.php
* @brief  GetElementProperty HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class GetElementProperty extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add new input element
	*/
	public function Exec( $name, $property )
	{
		return self::$parameter[$property];

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
	}

}