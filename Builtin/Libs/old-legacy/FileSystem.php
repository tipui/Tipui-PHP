<?php
/** FileSystem Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-04-16 02:18:00 - Daniel Omine
 *
 *   Methods
		convertFromBytes
		convertToBytes
        ReadFile
		fileSize
		setCHMOD
		fileExists
		Copy
		Delete
        WriteFile
		download
*/

class FileSystem
{

    public static function convertFromBytes( $n ){
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

    public static function convertToBytes( $x, $t ){
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

    function ReadFile( $x )
    {
		$x = file_get_contents( $x );

        return $x;
    }

    function fileSize( $path )
    {
		if( @!file_exists( $path ) )
		{
			return false;
		}

        return filesize( $path );
    }

    function setCHMOD( $path, $mode = 0777 )
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

    function fileExists( $path )
    {
		if( @!file_exists( $path ) )
		{
			return false;
		}
        return true;
    }

    function Copy( $from, $to )
    {
		if( @file_exists( $from ) )
		{
			copy( $from, $to );
		}else{
			return 4;
		}
        return null;
    }

    function Delete( $path )
    {
		if( self::fileExists( $path ) )
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

    function WriteFile( $path = '', $content = '', $overWrite = '', $method = 'w+', $mode = 0777 )
	{

        if( $overWrite == '' ){
            $overWrite = 'y';
        }

        if( trim( $path ) != '' ){

            if( !@file_exists( $path ) or $overWrite == 'y' ){

                if( $src = fopen( $path, $method ) ){
    
                    if( @fputs( $src, $content ) ){
    
                        if( @file_exists( $path ) ){
            
                            if( DS == '/' ){
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
            $rs = fileSystem::WriteFile( FOLDER_WWW . 'houou.txt', 'OK 3' );

            sample 2 - overwrite disableds
            $rs = fileSystem::WriteFile( FOLDER_WWW . 'houou.txt', 'OK 3', 'n' );

        */

    }

   function download( $path, $fileName = '' ){

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
?>