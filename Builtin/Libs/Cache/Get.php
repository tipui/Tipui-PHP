<?php

/**
* @class  Get
* @file   Get.php
* @brief  Get Cache functions.
* @date   2013-09-22 23:25:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-24 21:35:00
*/

namespace Tipui\Builtin\Libs\Cache;

use Tipui\Builtin\Libs as Libs;

class Get extends \Tipui\Builtin\Libs\Cache
{

	/**
	* Set Cache session
	*
	* Sample with Cookie
	[code]
		$rs = Cache::Get( Core::STORAGE_CACHE_MODE_COOKIE, 'Tipui::App::Model' );
		print_r( $rs ); exit;

		// If encrypted by Tipui Builtin Library Encryption
		print_r( \Tipui\Builtin\Libs\Encryption::Auto() -> Decode( $rs ) ); exit;
	[/code]
	*
	* Sample with Session
	[code]
		$rs = Cache::Get( Core::STORAGE_CACHE_MODE_SESSION, 'Tipui::App::Model' );
		print_r( $rs ); exit;

		// If encrypted by Tipui Builtin Library Encryption
		print_r( \Tipui\Builtin\Libs\Encryption::Auto() -> Decode( $rs ) ); exit;
	[/code]
	*/
	public function Exec( $args = null )
	{

		$rs = null;

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
		* Abstract array the from instantiated library (cookie, session)
		*/
		if( is_array( $args ) )
		{
			/*
			* Multiple keys
			* Iterates the array to abstract array data.
			*/
			foreach( $args as $v )
			{
				//echo $v; exit;
				$rs[$v] = $storage -> Get( $v );
			}
		}else{
			/*
			* Single key
			*/
			$rs = $storage -> Get( $args );
		}

		/*
		* Resetting used variables
		*/
		unset( $args, $storage );

		return $rs;

    }

}