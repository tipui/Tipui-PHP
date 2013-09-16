<?php

/**
* @class  WriteFile
* @file   WriteFile.php
* @brief  WriteFile file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class WriteFile
{

	/**
	* Creates new file or replace existing one.
	*
	* sample 1
	* [code]
	* FileSystem::Method( 'WriteFile' ) -> Exec( 'file/path', 'content' );
	* [/code]
	*
	* sample 2 - overwrite disabled
	* [code]
	* FileSystem::Method( 'WriteFile' ) -> Exec( 'file/path', 'content', true );
	* [/code]
	*/
	public function Exec( $path, $content = '', $overWrite = true, $method = 'w+', $mode = 0777 )
	{
        if( !empty( $path ) ){

            if( !@file_exists( $path ) or $overWrite ){

                if( $src = fopen( $path, $method ) ){

                    if( @fputs( $src, $content ) ){

                        if( @file_exists( $path ) ){

                            if( DIRECTORY_SEPARATOR == '/' ){
                                // for linux
                                $mask = @umask( 0 );
                                @chmod( $path, $mode );
                                @umask( $mask );
                            }

                        }else{
                        return 6; // returned true but file was not found
                        }

						return true; // ok sucessfull

                    }else{
                    return 5; // error from fputs
                    }

                }else{
                return 4; // error from fopen 
                }
                @fclose( $src );

            }else{
            return 3; // file exists and overWrite option is off
            }

        }else{
        return 2; // invalid path
        }

    }

}