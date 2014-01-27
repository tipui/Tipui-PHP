<?php

/**
* @class  SetMethod
* @file   SetMethod.php
* @brief  SetMethod Builtin DataValidation Lib functions.
* @date   2014-01-03 04:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-03 04:09:00
*/

namespace Tipui\Builtin\Libs\Form;

class SetMethod extends \Tipui\Builtin\Libs\Form
{

	/**
	* Sets form tag parameters
	* @see \Tipui\Builtin\Helpers\HTML\Form\AddForm
	*/
	public function Exec( $method = null )
	{

		/**
		* Sets the method parameter
		* @see \Tipui\Builtin\Libs\Request
		*/
		self::$method = empty( $method ) ? 'GET' : $method;

		return new self;
    }

}