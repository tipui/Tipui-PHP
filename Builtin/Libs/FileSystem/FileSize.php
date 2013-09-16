<?php

/**
* @class  FileSize
* @file   FileSize.php
* @brief  FileSize file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class FileSize
{

	/**
	* Returns file size
	*/
	public function Exec( $path )
	{
        return filesize( $path );
    }

}