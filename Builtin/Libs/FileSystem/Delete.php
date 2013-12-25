<?php

/**
* @class  Delete
* @file   Delete.php
* @brief  Delete file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-25 22:51:00
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
			if( unlink( $path ) )
			{
				return true;
			}else{
				/**
				* The file couldn't be deleted. May be an error on PHP unlink() function or file system permissions
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