<?php

/**
* @class  Compile
* @file   Compile.php
* @brief  Compile Template functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-02-28 19:32:00
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
		* Path base for PHP include function.
		*/
        self::$path = self::$base_dir;

		/**
		* Include optional extra directory
		*/
        if( $dir )
        {
            self::$path .= $dir;
			//set_include_path( get_include_path() . PATH_SEPARATOR . self::$base_dir );
			//ini_set( 'include_path', ini_get( 'include_path' ) . PATH_SEPARATOR . self::$base_dir );
        }

		//echo PHP_EOL . ': ' . self::$path . $file; //exit;
		ob_start();
		require_once( self::$path . $file );
		$__r = ob_get_contents();
		ob_end_clean();

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