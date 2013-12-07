<?php

/**
* @class  SetCSSName
* @file   SetCSSName.php
* @brief  SetCSSName HTML Helper Form Elements functions.
* @date   2013-12-05 17:59:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-05 17:59:00
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