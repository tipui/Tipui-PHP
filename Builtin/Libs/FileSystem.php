<?php

/**
* @class  FileSystem
* @file   FileSystem.php
* @brief  File System functions.
* @date   2013-09-08 23:45:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-10 01:26:00
*/

namespace Tipui\Builtin\Libs;

class FileSystem
{

    public static function ConvertFromBytes( $n ){
        if($n >= 1073741824){
        $n = round($n / 1073741824 * 100) / 100 . 'gb';
        }elseif($n >= 1048576){
        $n = round($n / 1048576 * 100) / 100 . 'mb';
        }elseif($n >= 1024){
        $n = round($n / 1024 * 100) / 100 . 'kb';
        }else{
        $n = $n . ' bytes';
        }
        return $n;
    }

    public static function ConvertToBytes( $x, $t ){
        $t = strtolower( $t );
        if( $t == 'gb' ){
                $x = round( $x * 1073741824 ); //gb
                }elseif( $t == 'mb' ){
                $x = round($x * 1048576); //mb
                }elseif( $t == 'kb' ){
                $x = round( $x * 1024 ); //kb
        }else{
            $x = $x; //bytes
        }
        return $x;
    }

    public function ReadFile( $x )
    {
        return file_get_contents( $x );
    }

    public function FileSize( $path )
    {
        return filesize( $path );
    }

    public function setCHMOD( $path, $mode = 0777 )
    {
		// for linux only
		if( DS == '/' )
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

    public function FileExists( $path )
    {
		if( @!file_exists( $path ) )
		{
			return false;
		}
        return true;
    }

    public function Copy( $from, $to )
    {
		if( @file_exists( $from ) )
		{
			copy( $from, $to );
		}else{
			return 4;
		}
        return null;
    }

    public function Delete( $path )
    {
		if( $this -> FileExists( $path ) )
		{
			if( !unlink( $path ) )
			{
				return 3;
			}
		}else{
			return 4;
		}
        return null;
    }

    public function WriteFile( $path, $content = '', $overWrite = true, $method = 'w+', $mode = 0777 )
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
                        return 4; // returned true but file was not found
                        }
						
						return 0; // ok sucessfull
						
                    }else{
                    return 3; // error from fputs
                    }
    
                }else{
                return 2; // error from fopen 
                }
                @fclose( $src );

            }else{
            return 5; // file exists and overWrite option is off
            }

        }else{
        return 1; // invalid path
        }

        /** usage

            sample 1
            $fs = new FileSystem;
			$fs -> WriteFile( FOLDER_WWW . 'houou.txt', 'OK 3' );

            sample 2 - overwrite disabled
            $fs = new FileSystem;
			$fs -> WriteFile( FOLDER_WWW . 'houou.txt', 'OK 3', 'n' );

        */

    }

    public function download( $path, $fileName = '' ){

        if( $fileName == '' ){
            $fileName = basename( $path );
        }

        header("Content-Type: application/force-download");
        header("Content-type: application/octet-stream;");
    	header("Content-Length: " . filesize( $path ) );
    	header("Content-disposition: attachment; filename=" . $fileName );
    	header("Pragma: no-cache");
    	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    	header("Expires: 0");
    	readfile( $path );
    	flush();
    }

}