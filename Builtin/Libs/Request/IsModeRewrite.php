<?php

/**
* @class  IsModeRewrite
* @file   IsModeRewrite.php
* @brief  IsModeRewrite Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class IsModeRewrite extends Libs\Request
{

	/** [review]
	* (boolean) Teturns if URL is mod_rewrite (user friendly) or not
	*/
    public static function Exec() 
    {
		return self::$mod_rewrite;
	}

}