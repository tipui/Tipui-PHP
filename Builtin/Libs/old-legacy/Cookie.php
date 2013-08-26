<?php
/** Cookie Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2012-05-24 19:03:00 - Daniel Omine
 *
 *   Methods
        Set
		Encode
		Decode
        Get
		Del
*/

class Cookie
{

    public  $data    = false;
    public  $exists  = false;

    function Set( $k, $v, $t = false, $p = false, $d = false )
    {
	
		$t = !$t ? strtotime( '+' . COOKIE_TIME . ' ' . COOKIE_TIME_MODE, time() ) : $t; // time expires, default 30 days. must be in seconds
		$p = !$p ? '/' : $p;                      // path allowed, default root path
		$d = !$d ? '.' . SUBDOMAIN . DOMAIN : $d; // domain

        setcookie( $k, $v, $t, $p, $d, false, true );
		$_COOKIE[$k] = $v;

		return null;
    }

    function Encode( $str )
    {
    	//print_r( $str ); exit;
        return serialize( $str );
    }
	
    function Decode( $str )
    {
		if( get_magic_quotes_gpc() )
		{
			$str = stripslashes($str);
		}
        return unserialize( $str );
        //return $str;
    }

    function Get( $key = false )
    {

        if( $key )
        {

            if( isset( $_COOKIE[ $key ] ) )
            {

                $this -> data   = $_COOKIE[ $key ];
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

        return $this;

    }

    function Del( $str )
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