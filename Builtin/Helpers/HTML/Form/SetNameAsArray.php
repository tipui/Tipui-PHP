<?php

/**
* @class  SetNameAsArray
* @file   SetNameAsArray.php
* @brief  SetNameAsArray HTML Helper Form Elements functions.
* @date   2013-12-04 02:26:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-04 02:26:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class SetNameAsArray extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Set name of an element as array.
	*/
	public function Exec( $val )
	{
		self::$name_as_array = $val;
	}

}