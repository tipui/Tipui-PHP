<?php

/**
* @class  ParseRange
* @file   ParseRange.php
* @brief  ParseRange strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class ParseRange
{

	/**
	* (array) range of objects
	*/
	public function Exec( $start = false, $end = false, $mode = 'auto' )
	{
		if( !$start or !$end )
        {
            return false;
        }else{

            $rs = range( $start, $end );

			/**
			* (array) normal range of objects (numeric indexes)
			*/
            if( $mode == 'auto' )
            {
                return $rs;
            }

			// [review] try array combine
			/**
			* (array) range of objects, however, indexes are replaced by it values
			*/
            foreach( $rs as $k => $v )
            {
                $arr[$v] = $v;
            }
            unset( $rs );

            return $arr;

        }
    }

}