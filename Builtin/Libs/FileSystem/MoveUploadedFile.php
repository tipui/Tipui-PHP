<?php

/**
* @class  MoveUploadedFile
* @file   MoveUploadedFile.php
* @brief  MoveUploadedFile file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-24 00:52:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class MoveUploadedFile
{

	/**
	* Move uploaded file from temporary path to other
	*/
	public function Exec( $from, $to )
	{
		return move_uploaded_file( $from, $to );
    }

}