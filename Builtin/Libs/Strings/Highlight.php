<?php

/**
* @class  Highlight
* @file   Highlight.php
* @brief  Highlight strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class Highlight
{

	/**
	* Replace found chars into tags
	*/
	public function Exec( $str, $s, $n = array( '<b>', '</b>' ) )
	{
		return str_ireplace( $s, $n[0] . $s . $n[1], $str );
    }

}