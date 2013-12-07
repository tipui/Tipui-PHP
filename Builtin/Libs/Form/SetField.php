<?php

/**
* @class  SetField
* @file   SetField.php
* @brief  SetField Builtin Form Lib functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

use \Tipui\Builtin\Libs\DataRules as DataRules;

class SetField extends \Tipui\Builtin\Libs\Form
{

	/**
	* Sets form fields rules
	*/
	public function Exec( $name, $rule, $required = true )
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

}