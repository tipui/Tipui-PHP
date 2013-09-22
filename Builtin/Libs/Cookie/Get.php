<?php

/**
* @class  Get
* @file   Get.php
* @brief  Get Cookie functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 03:42:00
*/

namespace Tipui\Builtin\Libs\Cookie;

class Get extends \Tipui\Builtin\Libs\Cookie
{

	/**
	* Get Cookie
	*
	* Sample
	[code]
	$c = new Libs\Cookie
	$rs = $c -> Get( 'foo' );
	if( !isset( $rs -> invalid_key ) )
	{
		echo 'valid cookie key';
	}else{
		echo 'invalid cookie key';
	}
	[/code]
	*/
	public function Exec( $key = false )
	{

        if( $key )
        {

			/**
			* Returns single key of array, if exists.
			*/
            if( isset( $_COOKIE[$key] ) )
            {

                return $this -> Decode( $_COOKIE[$key] );

            }else{

				/**
				* Invalid key.
				*/
				$c = new \StdClass;
				$c -> invalid_key = true;
				return $c;

            }

        }else{

			/**
			* Returns entire array if exists.
			*/
            if( isset( $_COOKIE ) )
            {
                return $_COOKIE;
            }else{
                throw new \Exception('cookie not found');
            }

        }

    }

}