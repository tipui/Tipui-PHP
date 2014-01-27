<?php

/**
* @class  SetURLParam
* @file   SetURLParam.php
* @brief  SetURLParam HTML Helper Form Elements functions.
* @date   2014-01-22 11:40:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-22 11:40:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class SetURLParam extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Set the URL parameter
	* Used as cache, for performance purposes.
	*/
	public function Exec( $val )
	{
		return self::$url_param = $val;
	}

}