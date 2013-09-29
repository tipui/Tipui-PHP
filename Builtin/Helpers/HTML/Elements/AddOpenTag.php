<?php

/**
* @class  AddOpenTag
* @file   AddOpenTag.php
* @brief  AddOpenTag HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class AddOpenTag
{

	/**
	* Add HTML opener tag <html>
	*/
	public function Exec( $lang = 'english' )
	{
		return '<html lang="' . $lang . '" xmlns="http://www.w3.org/1999/xhtml">';
	}

}