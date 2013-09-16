<?php

/**
* @class  Set
* @file   Set.php
* @brief  Set Cookie functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Cookie;

class Set
{

	/**
	* Sets a cookie
	*
	*
	* Usage sample 1:
	* [code]
	* // Set custom cookie parameters
	* $env_bootstrap = \Tipui\Core::GetEnv( 'BOOTSTRAP' );
	* $env_cookies   = \Tipui\Core::GetEnv( 'COOKIES' );
	* $c -> Set( 'foo', 'bar', $env_cookies['COOKIE_TIME'], $env_cookies['COOKIE_TIME_MODE'], $env_bootstrap['PUBLIC_FOLDER'], $env_bootstrap['DOMAIN'], $env_bootstrap['SUBDOMAIN'] );
	* [/code]
	*
	*
	*
	* Usage sample 2:
	* [code]
	* // Set custom cookie parameters
	* $env_bootstrap = \Tipui\Core::GetConf -> BOOTSTRAP;
	* $env_cookies   = \Tipui\Core::GetConf -> COOKIES;
	* $c -> Set( 'foo', 'bar', $env_cookies -> COOKIE_TIME, $env_cookies -> COOKIE_TIME_MODE, $env_bootstrap -> PUBLIC_FOLDER, $env_bootstrap -> DOMAIN, $env_bootstrap -> SUBDOMAIN );
	* [/code]
	*/
    public function Exec( $k, $v, $time = false, $time_mode = false, $path = false, $domain = false, $subdomain = '' ) 
    {

		$env_bootstrap = null;

		/**
		* Time to expire in seconds
		*/
		if( !$seconds )
		{
			$env_cookies = \Tipui\Core::GetConf -> COOKIES;
			$seconds     = strtotime( $env_cookies -> COOKIE_TIME . ' ' . $env_cookies -> COOKIE_TIME_MODE, time() );
			unset( $env_cookies );
		}else{
			$seconds = strtotime( $time . ' ' . $time_mode, time() );
		}

		/**
		* Cookie path folder
		*/
		if( !$path )
		{
			$env_bootstrap = \Tipui\Core::GetConf -> BOOTSTRAP;
			$path          = $env_bootstrap -> PUBLIC_FOLDER;
		}

		/**
		* Sets domain and subdomain if exists
		*/
		if( !$domain )
		{
			/**
			* Ignores $subdomain parameter
			*/
			( $env_bootstrap == null ) ? $env_bootstrap = \Tipui\Core::GetConf -> BOOTSTRAP : null;
			$domain = '.' . $env_bootstrap -> SUBDOMAIN . $env_bootstrap -> DOMAIN;
		}else{
			$domain = '.' . $subdomain . $domain;
		}

		/**
		* Serializes cookie value
		*/
		if( !is_string( $v ) )
		{
			$v = self::Encode( $v );
		}

		/**
		* Sets client cookie file
		*/
        setcookie( $k, $v, $seconds, $path, $domain, false, true );

		/**
		* Sets global variable $_COOKIE
		*/
		$_COOKIE[$k] = $v;

		/**
		* Clear variables
		*/
		unset( $env_bootstrap, $k, $v, $time, $time_mode, $seconds, $path, $domain );

		return null;

	}

}