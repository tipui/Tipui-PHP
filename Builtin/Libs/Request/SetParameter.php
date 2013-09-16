<?php

/**
* @class  SetParameter
* @file   SetParameter.php
* @brief  SetParameter Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class SetParameter extends Libs\Request
{

	/**
	* Sets the main parameter name
	*/
    public static function Exec( $parameter ) 
    {
		self::$rq_parameter = $parameter;
		return null;
	}

}