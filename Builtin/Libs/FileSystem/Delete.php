<?php

/**
* @class  Delete
* @file   Delete.php
* @brief  Delete file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class Delete
{

	/**
	* Delete file
	*/
	public function Exec( $path )
	{
		if( @file_exists( $path ) )
		{
			if( !unlink( $path ) )
			{
				return 3;
			}
		}else{
			return 2;
		}
        return null;
    }

}