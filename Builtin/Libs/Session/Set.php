<?php

/**
* @class  Set
* @file   Set.php
* @brief  Set Session functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Session;

class Set
{

	/**
	* Set Session
	*
	* Sample
	[code]
	$c = new Libs\Session
	$c -> Set( 'foo', 'bar' );
	[/code]
	*/
	public function Exec( $key, $value )
	{
        $_SESSION[$key] = $value;
        return null;
    }

}