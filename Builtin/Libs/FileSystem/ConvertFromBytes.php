<?php

/**
* @class  ConvertFromBytes
* @file   ConvertFromBytes.php
* @brief  ConvertFromBytes file system functions.
* @date   2013-09-16 02:57:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 02:57:00
*/

namespace Tipui\Builtin\Libs\FileSystem;

class ConvertFromBytes
{

	/**
	* Convert bytes to kb, mb or gb if bigger or equal to 1024
	*/
	public function Exec( $n )
	{
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

}