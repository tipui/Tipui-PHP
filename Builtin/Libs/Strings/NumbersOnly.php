<?php

/**
* @class  NumbersOnly
* @file   NumbersOnly.php
* @brief  NumbersOnly strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class NumbersOnly
{

	/**
	* Removes non numeric chars
	*/
	public function Exec( $str, $float = false )
	{
        if( !is_array( $str ) )
        {
			$r = '';
			if( $float )
			{
				$r   = '.';
				$str = str_replace( ',', $r, $str );
			}
            return preg_replace( '#[^0-9' . $r . ']#', '', mb_convert_kana( $str, 'n' ) );
        }
        return '';
    }

}