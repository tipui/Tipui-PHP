<?php

/**
* @class  ChunkSpaces
* @file   ChunkSpaces.php
* @brief  ChunkSpaces strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class ChunkSpaces
{

	/**
	* Removes duplicated spaces
	* "foo     bar", returns "foo bar"
	*/
	public function Exec( $str )
	{
		return preg_replace('!\s+!', ' ', $str);
    }

}