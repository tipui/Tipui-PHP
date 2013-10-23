<?php

/**
* @class  ArrayRpush
* @file   ArrayRpush.php
* @brief  ArrayRpush strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class ArrayRpush
{

	/**
	* Add an element at the beginning of an array
	*/
	public function Exec( $arr, $item )
	{
		return array_pad( $arr, -( count( $arr ) + 1 ), $item );
    }

}