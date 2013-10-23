<?php

/**
* @class  StrBr
* @file   StrBr.php
* @brief  StrBr strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class StrBr
{

	/**
	* Replace break line with <br /> tag
	*/
	public function Exec( $str, $force = false )
	{
		if( !$force )
		{
			return nl2br( $str );
		}else{
			$str = nl2br( $str );
			$str = str_replace( array("
","\r\n","\r","\n"), '<br />', $str );
			return $str;
		}
    }

}