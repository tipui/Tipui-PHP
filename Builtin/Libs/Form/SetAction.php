<?php

/**
* @class  SetAction
* @file   SetAction.php
* @brief  SetAction Builtin Form Lib functions.
* @date   2014-01-03 04:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-03 04:09:00
*/

namespace Tipui\Builtin\Libs\Form;

class SetAction extends \Tipui\Builtin\Libs\Form
{

	/**
	* Sets form tag parameters
	* @see \Tipui\Builtin\Helpers\HTML\Form\AddForm
	*/
	public function Exec( $action = null )
	{

		/**
		* Sets the action parameter
		*/
		self::$action = empty( $action ) ? \Tipui\Core::GetConf() -> URL -> FORM_ACTION : $action;

		return new self;
    }

}