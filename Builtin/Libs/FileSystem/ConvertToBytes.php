<?php

/**
* @class  ConvertToBytes
* @file   ConvertToBytes.php
* @brief  ConvertToBytes file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class ConvertToBytes
{

	/**
	* Convert to bytes from kb, mb or gb
	*/
	public function Exec( $x, $t )
	{
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

}