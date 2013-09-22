<?php

/**
* @class  AddHead
* @file   AddHead.php
* @brief  AddHead HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class AddHead
{

	/**
	* $close == false: Open sintax
	* $close == true or anything different of boolean "true": Close sintax
	*/
	public function Exec( $close = false )
	{
		return !$close ? '<head>' : '</head>';
	}

}