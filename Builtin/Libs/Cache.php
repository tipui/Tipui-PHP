<?php

/**
* @class  Cache
* @file   Cache.php
* @brief  Cache functions.
* @date   2010-09-22 22:04:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2010-09-22 22:04:00
*/

namespace Tipui\Builtin\Libs;

/**
* Cache library.
*/
class Cache
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Cache;
	* $c -> Set( [arguments] );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Cache', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Cache::Set( [arguments] );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Cache', $name, $arguments );
    }

}