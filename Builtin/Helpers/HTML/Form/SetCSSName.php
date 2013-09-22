<?php

/**
* @class  SetCSSName
* @file   SetCSSName.php
* @brief  SetCSSName HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class SetCSSName extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Set CSS classname to an entitie.
	*/
	public function Exec( $val )
	{
		self::$css_name = $val;
	}

}