<?php

/**
* @class  AddTitle
* @file   AddTitle.php
* @brief  AddTitle HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class AddTitle extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Add HTML title tag
	*/
	public function Exec( $str = null )
	{
		( $str == null ) ? $str = self::$title: null;
		return '<title>' . $str . '</title>';
	}

}