<?php

/**
* @class  Get
* @file   Get.php
* @brief  Get Cache functions.
* @date   2013-09-22 23:25:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 23:25:00
*/

namespace Tipui\Builtin\Libs\Cache;

use Tipui\Builtin\Libs as Libs;

class Get
{

	/**
	* Set Cache session
	*
	* Sample with Cookie
	[code]
	$c = new Libs\Cache
	$c -> Get( 
		array( 'cookie'     => array(
				'key'       => 'Tipui::Core::' . $method,
			)
		)
	);
	[/code]
	*
	* Sample with Session
	[code]
	$c = new Libs\Cache
	$c -> Get( 
		array( 'session' => array(
				'key'    => 'Tipui::Core::' . $method,
			)
		)
	);
	[/code]
	*/
	public function Exec( $data )
	{

		$mode    = key( $data );
		$storage = null;
		$rs      = null;

		switch( $mode )
		{
			case \Tipui\Core::STORAGE_CACHE_MODE_SESSION:

				$storage = new Libs\Session;
				$rs = $storage -> Get( $data[$mode]['key'] );

			break;
			default:
			case \Tipui\Core::STORAGE_CACHE_MODE_COOKIE:

				$storage = new Libs\Cookie;
				$rs = $storage -> Get( $data[$mode]['key'] );

			break;
			case \Tipui\Core::STORAGE_CACHE_MODE_SQLITE:
				throw new \Exception('Core method cache storage for sqlite not available.');
			break;
		}

		unset( $mode, $data, $storage );

		return $rs;
    }

}