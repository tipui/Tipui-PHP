<?php

/**
* @class  WriteFile
* @file   WriteFile.php
* @brief  WriteFile file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-25 19:04:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class WriteFile
{

	/**
	* Creates new file or replace existing one.
	*
	* sample 1
	* [code]
	* FileSystem::WriteFile( 'file/path', 'content' );
	* [/code]
	*
	* sample 2 - overwrite disabled
	* [code]
	* FileSystem::WriteFile( 'file/path', 'content', false );
	* [/code]
	*/
	public function Exec( $path, $content = '', $overWrite = true, $method = 'w+', $mode = 0777 )
	{
        if( !empty( $path ) )
		{

            if( !@file_exists( $path ) or $overWrite )
			{

                if( $src = fopen( $path, $method ) )
				{

                    if( @fputs( $src, $content ) )
					{

						if( DIRECTORY_SEPARATOR == '/' )
						{
							// for linux
							$mask = @umask( 0 );
							@chmod( $path, $mode );
							@umask( $mask );
						}

						/**
						* OK, sucessfull. However, still may be occured errors.
						*/
						return true;

                    }else{
						/**
						* The file couldn't be written. May be an error on PHP fputs() function or file system permissions
						*/
						return 5;
                    }

                }else{
					/**
					* The file couldn't be oppened. May be an error on PHP fopen() function or file system permissions
					*/
					return 4;
                }
                @fclose( $src );

            }else{
				/**
				* The file exists but the overwrite option is off
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