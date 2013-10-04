<?php

/**
* @class  Compile
* @file   Compile.php
* @brief  Compile Template functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-10-04 16:47:00
*/

namespace Tipui\Builtin\Libs\Template;

use Tipui\Builtin\Libs as Libs;

class Compile extends Libs\Template
{

	/**
	* Renderer
	*/
    public static function Exec( $data, $dir = false, $file = false ) 
    {

		/**
		* Mount the main tag. ie: $T[]
		*/
        $k   = self::$tag;
        $$k  = $data;
        unset( $data, $k );

		/**
		* Path base for PHP include_path directive.
		*/
		$paths = self::$base_dir;

		/**
		* Path base for PHP include function.
		*/
        self::$path = self::$base_dir;

		/**
		* [deprecated]
		*/
		//$paths = self::$base_dir . PATH_SEPARATOR . ini_get( 'include_path' );

		/**
		* Include optional extra directory
		*/
        if( $dir )
        {
            self::$path .= $dir;
			$paths = self::$path . PATH_SEPARATOR . $paths;
        }

        ini_set( 'include_path', $paths );
		unset( $paths );

        //echo self::$path . $file; exit;
        if( $fp = @fopen( self::$path . $file, 'r' ) )
        {

            $src = '';
            while( !feof( $fp ) )
            {
                $src .= fread( $fp, 65536 );
				// Essential for large files
				if( self::$output == 'print' )
				{
					flush();
				}
            }  
            fclose( $fp ); 

            ob_start();

            eval( '?>' . $src );
            $__r = ob_get_contents();
			//ob_end_flush();
            ob_end_clean(); 
            unset( $src );

			if( self::$output == 'print' )
			{
				flush();
			}

        }else{
            $__r = '[' . get_class() . '], ERROR: FILE NOT EXISTS -> ' . self::$path . $file;
        }
		unset( $fp );

        if( self::$output == 'print' )
        {
            echo $__r;
            unset( $__r );
            return null;
        }

        self::$output = 'print';
        return $__r;

    }

}