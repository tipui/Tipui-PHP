<?php

/**
* @class  Set
* @file   Set.php
* @brief  Set Cache functions.
* @date   2013-09-22 23:25:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-24 21:35:00
*/

namespace Tipui\Builtin\Libs\Cache;

use Tipui\Builtin\Libs as Libs;

class Set extends \Tipui\Builtin\Libs\Cache
{

	/**
	*
	* Sample saving to cache without especifies the mode (cookie or session)
	* In this case, the Library Cache will assumes the cache mode defined in BOOTSTRAP environment file.
	*
	[code]
	$c = new Libs\Cache
	$c -> Set( array(
					'KeyName' => 'Keyvalue',
					)
				);
	[/code]
	*
	*
	* Set Cache session
	*
	* Sample for Cookie, especifying cookie parameters
	[code]
	$c = new Libs\Cache
	$c -> Cookie(
				Core::GetConf()->COOKIES->COOKIE_TIME,
				Core::GetConf()->COOKIES->COOKIE_TIME_MODE,
				Core::GetConf()->BOOTSTRAP->PUBLIC_FOLDER,
				Core::GetConf()->BOOTSTRAP->DOMAIN,
				Core::GetConf()->BOOTSTRAP->SUBDOMAIN
			) -> Set( array(
					'KeyName' => 'KeyValue',
					)
				);
	[/code]
	*
	* Sample especifying to save in Session
	[code]
	$c = new Libs\Cache
	$c -> Session() -> Set( array(
					'KeyName' => 'Keyvalue',
					)
				);
	[/code]
	*/
	public function Exec( $data = null )
	{

		/*
		* Get defined Cache Storage mode from BOOTSTRAP if self::$lib_name is empty
		*/
		( empty( self::$lib_name ) ) ? self::$lib_name = \Tipui\Core::GetConf() -> BOOTSTRAP -> DEFAULT_CACHE_STORAGE_MODE : null;

		/*
		* Builds the class namespace nomenclature for new instance
		*/
		$c = '\Tipui\Builtin\Libs\\' . ucfirst( self::$lib_name );
		$storage = new $c;
		self::$lib_name = null;
		unset( $c );

		/*
		* Iterates the array to save in instantiated library (cookie, session)
		*/
		foreach( $data as $k => $v )
		{
			$storage -> Set( $k, $v );
		}

		/*
		* Resetting the property [code]$lib_args[/code].
		*/
		self::$lib_args = null;

		// [review] For cookie case
		//( isset( $data['time'] ) ? $data['time'] : false ), ( isset( $data['time_mode'] ) ? $data['time_mode'] : false ), ( isset( $data['path'] ) ? $data['path'] : false ), ( isset( $data['domain'] ) ? $data['domain'] : false ), ( isset( $data['subdomain'] ) ? $data['subdomain'] : false )

		/*
		* Resetting used variables
		*/
		unset( $c, $data );

		return null;
    }

}