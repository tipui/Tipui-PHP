<?php

/**
* @class  ReadFile
* @file   ReadFile.php
* @brief  ReadFile file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class ReadFile
{

	/**
	* Read content of an file
	*/
	public function Exec( $path )
	{
        return file_get_contents( $path );
    }

}