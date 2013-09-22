<?php

/**
* @class  SetElementKey
* @file   SetElementKey.php
* @brief  SetElementKey HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class SetElementKey extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Set name of an element as array.
	*/
	public function Exec( $val )
	{
		self::$key_add = $val;
	}

}