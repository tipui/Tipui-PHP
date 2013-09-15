<?php

/**
* @class  Strings
* @file   Strings.php
* @brief  Strings functions.
* @date   2013-03-18 18:50:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:28:00
*/

namespace Tipui\Builtin\Libs;

use Tipui\Builtin\Libs as Libs;

class Strings
{

    public static function Method( $name )
	{

		require_once( 'Strings/' . $name . '.php' );
		$c = '\Tipui\Builtin\Libs\Strings\\' . $name;
		return new $c;
	}

}