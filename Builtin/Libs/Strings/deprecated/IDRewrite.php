<?php

/**
* @class  IDRewrite
* @file   IDRewrite.php
* @brief  IDRewrite strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class IDRewrite
{

	/**
	* Useful for extract ID of friendly URLs.
	* http://localhost/Foo/10-Bar_Lorem_ipsum
	* returns 10
	*/
	public function Exec( $str )
	{
		$str = explode( '-', $str . '-' );
		return $str[0];
    }

}