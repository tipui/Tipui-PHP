<?php

/**
* @class  EscapeJS
* @file   EscapeJS.php
* @brief  EscapeJS strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class EscapeJS
{

	/**
	* [review]
	* Escapes quotes. 
	* Intention is implement more filters.. not only escape quotes.
	*/
	public function Exec( $str )
	{
		return str_replace( '"', '&#34;', str_replace( "'", "&#39;", $str ) );
    }

}