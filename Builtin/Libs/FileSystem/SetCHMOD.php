<?php

/**
* @class  SetCHMOD
* @file   SetCHMOD.php
* @brief  SetCHMOD file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class SetCHMOD
{

	/**
	* Set Unix file or folder permissions
	*/
	public function Exec( $path, $mode = 0777 )
	{
		/**
		* Directory separator indicates that normal slash is non-windows system
		*/
		if( DIRECTORY_SEPARATOR == '/' )
		{
			if( @file_exists( $path ) )
			{
				$mask = @umask( 0 );
				@chmod( $path, $mode );
				@umask( $mask );
			}else{
				return 4;
			}
		}
        return null;
    }

}