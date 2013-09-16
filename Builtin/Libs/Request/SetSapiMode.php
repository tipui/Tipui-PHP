<?php

/**
* @class  SetSapiMode
* @file   SetSapiMode.php
* @brief  SetSapiMode Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class SetSapiMode extends Libs\Request
{

	/**
	* Sets identifier of on witch environment is running
	*/
    public static function Exec( $mode = false ) 
    {
		self::$sapi_is_cli = $mode;
		return null;
	}

}