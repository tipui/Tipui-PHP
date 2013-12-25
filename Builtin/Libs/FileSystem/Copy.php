<?php

/**
* @class  Copy
* @file   Copy.php
* @brief  Copy file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-25 22:51:00
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
			if( @copy( $from, $to ) )
			{
				return true;
			}else{
				/**
				* The file couldn't be copied. May be an error on PHP copy() function or file system permissions
				*/
				return 3;
			}
		}else{
			/**
			* The source file path not exists
			*/
			return 2;
		}
    }

}