<?php

/**
* @class  SetFieldProperty
* @file   SetFieldProperty.php
* @brief  SetFieldProperty Builtin Form Lib functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

class SetFieldProperty extends \Tipui\Builtin\Libs\Form
{

	/**
	* Set property of an field
	*/
	public function Exec( $name, $property, $val )
	{
		self::$parameters[$name][$property] = $val;

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
    }

}