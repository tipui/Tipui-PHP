<?php

/**
* @class  SetFieldMultiValue
* @file   SetFieldMultiValue.php
* @brief  SetFieldMultiValue Header functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

class SetFieldMultiValue extends \Tipui\Builtin\Libs\Form
{

	/**
	* Sets form fields rules containing array of options (generaly used for radio or checkbox)
	*/
	public function Exec( $name, $rule, $options, $required = true )
	{
		/**
		* Creates parameter rules
		*/
		self::SetField( $name, $rule, $required );
		self::SetFieldProperty( $name, 'options', $options );

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
    }

}