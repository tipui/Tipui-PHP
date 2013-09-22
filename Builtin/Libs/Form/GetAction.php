<?php

/**
* @class  GetAction
* @file   GetAction.php
* @brief  GetAction Header functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

class GetAction extends \Tipui\Builtin\Libs\Form
{

	/**
	* Gets form action parameter
	*/
	public function Exec()
	{
		/**
		* <form action="">
		*/
		return self::$action;
    }

}