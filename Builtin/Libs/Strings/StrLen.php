<?php

/**
* @class  StrLen
* @file   StrLen.php
* @brief  StrLen strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class StrLen
{

	/**
	* Multibyte safe characters length
	*/
	public function Exec( $str, $charset = 'UTF-8' )
	{
		return mb_strlen( $str, $charset);
    }

}