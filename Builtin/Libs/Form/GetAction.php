<?php

/**
* @class  GetAction
* @file   GetAction.php
* @brief  GetAction Builtin Form Lib functions.
* @date   2014-01-03 04:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-03 04:09:00
*/

namespace Tipui\Builtin\Libs\Form;

class GetAction extends \Tipui\Builtin\Libs\Form
{

	/**
	* Returns the $action property
	*/
	public function Exec()
	{
		return self::$action;
    }

}