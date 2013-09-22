<?php

/**
* @class  SetAction
* @file   SetAction.php
* @brief  SetAction Header functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

class SetAction extends \Tipui\Builtin\Libs\Form
{

	/**
	* Sets form action parameter
	*/
	public function Exec( $action = false )
	{
		/**
		* <form action="">
		*/
		self::$action = $action;
    }

}