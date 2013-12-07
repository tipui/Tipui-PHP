<?php

/**
* @class  SetReadOnly
* @file   SetReadOnly.php
* @brief  SetReadOnly HTML Helper Form Elements functions.
* @date   2013-12-05 17:59:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-05 17:59:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class SetReadOnly extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Set readonly property
	* (boolean)
	* Default: true
	*/
	public function Exec( $val = true )
	{
		self::$readonly = $val;
	}

}