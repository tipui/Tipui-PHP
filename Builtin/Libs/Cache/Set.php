<?php

/**
* @class  Set
* @file   Set.php
* @brief  Set Cache functions.
* @date   2013-09-22 23:25:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 23:25:00
*/

namespace Tipui\Builtin\Libs\Cache;

use Tipui\Builtin\Libs as Libs;

class Set
{

	/**
	* Set Cache session
	*
	* Sample with Cookie
	[code]
	$c = new Libs\Cache
	$c -> Set( 
		array( 'cookie'     => array(
				'key'       => 'Tipui::Core::' . $method,
				'val'       => $this -> $method(),
				'time'      => $this -> core_cached_data['COOKIES']['COOKIE_TIME'],
				'time_mode' => $this -> core_cached_data['COOKIES']['COOKIE_TIME_MODE'],
				'path'      => $this -> core_cached_data['BOOTSTRAP']['PUBLIC_FOLDER'],
				'domain'    => $this -> core_cached_data['BOOTSTRAP']['DOMAIN'],
				'subdomain' => $this -> core_cached_data['BOOTSTRAP']['SUBDOMAIN'],
			)
		)
	);
	[/code]
	*
	* Sample with Session
	[code]
	$c = new Libs\Cache
	$c -> Set( 
		array( 'session' => array(
				'key'    => 'Tipui::Core::' . $method,
				'val'    => $this -> $method(),
			)
		)
	);
	[/code]
	*/
	public function Exec( $data )
	{

		$mode    = key( $data );
		$storage = null;
		//echo $mode; exit;

		switch( $mode )
		{
			case 'session':

				//( $storage == null ) ? $storage = new Libs\Session : null;
				$storage = new Libs\Session;
				$storage -> Set( $data[$mode]['key'], $data[$mode]['val'] );
			break;
			default:
			case 'cookie':

				//( $storage == null ) ? $storage = new Libs\Cookie : null;
				$storage = new Libs\Cookie;
				$storage -> Set( $data[$mode]['key'], $data[$mode]['val'], ( isset( $data[$mode]['time'] ) ? $data[$mode]['time'] : false ), ( isset( $data[$mode]['time_mode'] ) ? $data[$mode]['time_mode'] : false ), ( isset( $data[$mode]['path'] ) ? $data[$mode]['path'] : false ), ( isset( $data[$mode]['domain'] ) ? $data[$mode]['domain'] : false ), ( isset( $data[$mode]['subdomain'] ) ? $data[$mode]['subdomain'] : false ) );

			break;
			case 'sqlite':
				throw new \Exception('Core method cache storage for sqlite not available.');
			break;
		}

		unset( $mode, $data, $storage );

		return null;
    }

}