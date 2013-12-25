<?php

/**
* @class  Cache
* @file   Cache.php
* @brief  Cache functions.
* @date   2010-09-22 22:04:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-24 21:28:00
*/

namespace Tipui\Builtin\Libs;

/**
* Cache library.
*/
class Cache
{

	protected static $lib_name = null;
	protected static $lib_args = null;

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
		return self::CheckLibName( $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Cache::Set( [arguments] );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return self::CheckLibName( $name, $arguments );
    }

	/**
	* Check if method name is the reserved names "Cookie" or "Session"
	*
	* sample
	* [code]Cache::Cookie()->Get( [arguments] );[/code]
	* [code]Cache::Session()->Get( [arguments] );[/code]
	*/
    protected static function CheckLibName( $name, $arguments )
    {

		switch( strtolower( $name ) )
		{
			case strtolower( \Tipui\Core::STORAGE_CACHE_MODE_COOKIE ):
			case strtolower( \Tipui\Core::STORAGE_CACHE_MODE_SESSION ):
				self::$lib_name = strtolower( $name );
				return new self(); exit;
			break;
		}

		return Factory::Exec( 'Cache', $name, $arguments );
    }

}