<?php

/**
* @class  AddURLParam
* @file   AddURLParam.php
* @brief  AddURLParam Builtin Form Lib functions.
* @date   2014-01-22 11:40:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-22 11:40:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class AddURLParam extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add the main parameter
	* @see self::SetURLParam(), Builtin\Libs\Form::SetURLParam()
	*/
	public function Exec()
	{
		return $this -> AddElement( self::$url_param ) -> html;
	}

}