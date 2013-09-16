<?php

/**
* @class  Copy
* @file   Copy.php
* @brief  Copy file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class Copy
{

	/**
	* Copy file to another path
	*/
	public function Exec( $from, $to )
	{
		if( @file_exists( $from ) )
		{
			copy( $from, $to );
		}else{
			return 3;
		}
        return null;
    }

}