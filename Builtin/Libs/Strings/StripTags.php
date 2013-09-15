<?php

/**
* @class  StripTags
* @file   StripTags.php
* @brief  StripTags strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class StripTags
{

	/**
	* Strip HTML tags
	*/
	public function Exec( $v, $allow = '' )
	{
		return strip_tags( $v, $allow );
    }

}