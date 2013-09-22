<?php

/**
* @class  AddBody
* @file   AddBody.php
* @brief  AddBody HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class AddBody
{

	/**
	* $add is optional to add parameters, inline scripts, etc. ie: AddBody( ' onload="alert(1);"' )
	* $close == false: Open sintax
	* $close == true or anything different of boolean "true": Close sintax
	*/
	public function Exec( $add = '', $close = false )
	{
		return !$close ? '<body' . $add . '>' : '</body>';
	}

}