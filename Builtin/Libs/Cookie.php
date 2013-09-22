<?php

/**
* @class  Cookie
* @file   Cookie.php
* @brief  Cookie functions.
* @date   2012-05-24 19:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 03:42:00
*/

namespace Tipui\Builtin\Libs;

/**
* Cookie library.
*/
class Cookie
{

 	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Cookie;
	* $c -> Get( 'index_name' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Cookie', $name, $arguments );
    }

	/**
	* Statically.
	*
	* Cookie::Get( 'index_name' );
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Cookie', $name, $arguments );
    }

}