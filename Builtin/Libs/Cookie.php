<?php

/**
* @class  Cookie
* @file   Cookie.php
* @brief  Cookie functions.
* @date   2012-05-24 19:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-07-25 02:59:00
*/

class Cookie
{

    private $data;
    private $exists;
    private $cookie_time;
    private $cookie_time_mode;
    private $cookie_domain;
    private $cookie_subdomain;

	/**
	* Usage samples:
	* [code]
	* // Auto set default cookie parameters
	* $c = new Cookie;
	* [/code]
	* [code]
	* // Set custom cookie parameters
	* $env_bootstrap = \Tipui\FW::GetCoreDataCache( 'GetENV', 'BOOTSTRAP' );
	* $env_cookies   = \Tipui\FW::GetCoreDataCache( 'GetENV', 'COOKIES' );
	* $c1 = new Cookie( 20, 'days', $env_bootstrap['DOMAIN'], $env_bootstrap['SUBDOMAIN'] );
	* $c2 = new Cookie( $env_cookies['COOKIE_TIME'], $env_cookies['COOKIE_TIME_MODE'], $env_bootstrap['DOMAIN'], $env_bootstrap['SUBDOMAIN'] );
	* [/code]
	*/
    public function __construct( $c_time = false, $c_time_mode = false, $c_domain = false, $c_subdomain = false )
    {
		$this -> ResetProperties();

		$this -> cookie_time       = $c_time      ? $c_time: ;
		$this -> cookie_time_mode  = $c_time_mode ? $c_time_mode: ;
		$this -> cookie_domain     = $c_domain    ? $c_domain: ;
		$this -> cookie_subdomain  = $c_subdomain ? $c_subdomain: ;
    }

    public function __destruct()
    {
		$this -> ResetProperties();
	}

    private function ResetProperties()
    {
		$this -> data   = false;
		$this -> exists = false;
	}

    public function Exists( $key, $value )
    {
		return !$this -> exists ? $this -> exists : $this -> data;
	}

    public function Set( $k, $v, $t = false, $p = false, $d = false )
    {
	
		$t = !$t ? strtotime( '+' . COOKIE_TIME . ' ' . COOKIE_TIME_MODE, time() ) : $t; // time expires, default 30 days. must be in seconds
		$p = !$p ? '/' : $p;                      // Path allowed. Default: root path
		$d = !$d ? '.' . SUBDOMAIN . DOMAIN : $d; // Domain

        setcookie( $k, $v, $t, $p, $d, false, true );
		$_COOKIE[$k] = $v;

		return null;
    }

	/**
	* Serialize cookie array
	*/
    public function Encode( $str )
    {
        return serialize( $str );
    }

	/**
	* Unserialize cookie array
	*/
    public function Decode( $str )
    {
		if( get_magic_quotes_gpc() )
		{
			$str = stripslashes($str);
		}
        return unserialize( $str );
    }

	/**
	* Get the value of array key.
	* If the key is false, returns entire array.
	*/
    public function Get( $key = false )
    {

        if( $key )
        {

            if( isset( $_COOKIE[$key] ) )
            {

                $this -> data   = $_COOKIE[$key];
                $this -> exists = true;

            }else{

                $this -> exists = false;
            }

        }else{
            // return entire array

            if( isset( $_COOKIE ) )
            {
                $this -> data = $_COOKIE;
                $this -> exists = true;
            }else{
                $this -> exists = false;
            }

        }

        return $this -> data;

    }

	/**
	* Unset a single array key or entire array if key is false
	*/
    public function Del( $str, $encoded = false )
    {

		if( isset( $_COOKIE[$str] ) )
		{
			self::Set( $str, false );
			unset( $_COOKIE[$str] );
		}

        return null;
    }
}
?>