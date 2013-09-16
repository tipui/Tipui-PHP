<?php

/**
* @class  Get
* @file   Get.php
* @brief  Get Session functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Session;

class Get
{

	/**
	* Get Session
	*
	* Sample
	[code]
	$c = new Libs\Session
	$rs = $c -> Get( 'foo' );
	if( !isset( $rs -> invalid_key ) )
	{
		echo 'valid session key';
	}else{
		echo 'invalid session key';
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
            if( isset( $_SESSION[$key] ) )
            {

                return $_SESSION[$key];

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
            if( isset( $_SESSION ) )
            {
                return $_SESSION;
            }else{
                throw new \Exception('session not found');
            }

        }

    }

}