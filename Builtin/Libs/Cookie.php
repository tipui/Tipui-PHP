<?php

/**
* @class  Cookie
* @file   Cookie.php
* @brief  Cookie functions.
* @date   2012-05-24 19:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 23:43:00
*/

class Cookie
{

 	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Strings;
	* $c -> Trim( ' foo ' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Cookie', $name, $arguments );
    }

	/**
	* Statically.
	*
	* not available
	*/
    public static function __callStatic( $name, $arguments )
    {
		//return Factory::Exec( 'Cookie', $name, $arguments );
    }

}