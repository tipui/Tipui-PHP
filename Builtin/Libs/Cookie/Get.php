<?php

/**
* @class  Get
* @file   Get.php
* @brief  Get Cookie functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-22 23:40:00
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


				/**
				* Workaround... check if is serialized or not.
				*/
				$data = @unserialize( $_COOKIE[$key] );

				if( $data !== false || $data === 'b:0;' )
				{
					//echo $data; exit;
					return ( $data === 'b:0;' ) ? false : ( $data === 'b:1;' ? true : $data );
				}

				//echo $_COOKIE[$key]; exit;
				return ( $_COOKIE[$key] === 'b:0;' ) ? false : ( $_COOKIE[$key] === 'b:1;' ? true : $_COOKIE[$key] );

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
			* Returns entire array.
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