<?php

/**
* @class  SetMetatag
* @file   SetMetatag.php
* @brief  SetMetatag HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class SetMetatag extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Sets HTML metatags
	*/
	public function Exec( $type, $index, $value )
	{
		self::$meta[$type][$index] = $value;
		unset( $index, $value );
	}

}