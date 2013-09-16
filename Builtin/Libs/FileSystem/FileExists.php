<?php

/**
* @class  FileExists
* @file   FileExists.php
* @brief  FileExists file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class FileExists
{

	/**
	* Check if file exists
	*/
	public function Exec( $path )
	{
		if( @!file_exists( $path ) )
		{
			return false;
		}
        return true;
    }

}