<?php

/**
* @class  SetTitle
* @file   SetTitle.php
* @brief  SetTitle HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class SetTitle extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Sets HTML title tag
	*/
	public function Exec( $str )
	{
		self::$title = $str;
		unset( $str );
	}

}