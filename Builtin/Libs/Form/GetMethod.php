<?php

/**
* @class  GetMethod
* @file   GetMethod.php
* @brief  GetMethod Builtin Form Lib functions.
* @date   2014-01-03 04:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-27 18:21:00
*/

namespace Tipui\Builtin\Libs\Form;

class GetMethod extends \Tipui\Builtin\Libs\Form
{

	/**
	* Returns the $action property
	* @see \Tipui\Builtin\Helpers\HTML\Form\AddForm
	*/
	public function Exec()
	{
		return !empty( self::$method ) ? self::$method : 'GET';
    }

}