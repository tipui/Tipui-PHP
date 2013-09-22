<?php

/**
* @class  AddMetatag
* @file   AddMetatag.php
* @brief  AddMetatag HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class AddMetatag extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Add HTML metatag
	*/
	public function Exec( $type, $index, $value = null, $default = null )
	{
		isset( self::$meta[$type][$index] ) ? $value = self::$meta[$type][$index] : null;
		empty( $value ) ? $value = $default : null;
		return '<meta ' . $type . '="' . $index .'" content="' . $value .'" />';
	}

}