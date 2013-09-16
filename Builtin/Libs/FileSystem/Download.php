<?php

/**
* @class  Download
* @file   Download.php
* @brief  Download file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class Download
{

	/**
	* Download file
	*/
	public function Exec( $path, $file_name = false )
	{
		header( 'Content-Type: application/force-download' );
		header( 'Content-type: application/octet-stream;' );
		header( 'Content-Length: ' . filesize( $path ) );
		header( 'Content-disposition: attachment; filename=' . ( ( !$file_name ) ? basename( $path ) : $file_name ) );
		header( 'Pragma: no-cache' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0' );
		header( 'Expires: 0' );
		readfile( $path );
		flush();
    }

}