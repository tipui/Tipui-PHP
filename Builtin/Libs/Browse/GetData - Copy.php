<?php

/**
* @class  GetData
* @file   GetData.php
* @brief  GetData browse functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 14:41:00
*/

namespace Tipui\Builtin\Libs\Browse;

class GetData
{

	/**
	* (array) Holds the server and client headers
	*/
	protected static $data;

	/**
	* Get user browser header information
	*/
	public function Exec()
	{
	//print_r( $this ); exit;
		self::GetRemoteInfo();

		self::$data['SessionID']             = session_id();
		self::$data['TimeIni']               = time();
		self::$data['TimeEnd']               = false;

		self::$data['REQUEST_URI']           = urldecode( $_SERVER['REQUEST_URI'] );

		self::$data['PageStart']             = self::$data['REQUEST_URI'];
		self::$data['PageCurrent']           = self::$data['REQUEST_URI'];
		self::$data['PageNext']              = false;
		self::$data['PagePrevious']          = false;

		self::$data['REMOTE_PORT']           = $_SERVER['REMOTE_PORT'];

		self::$data['HTTP_REFERER']          = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
		self::$data['REQUEST_METHOD']        = $_SERVER['REQUEST_METHOD'];

		self::$data['HTTP_CONNECTION']       = isset( $_SERVER['HTTP_CONNECTION'] )      ? $_SERVER['HTTP_CONNECTION'] : '';
		self::$data['HTTP_USER_AGENT']       = isset( $_SERVER['HTTP_USER_AGENT'] )      ? $_SERVER['HTTP_USER_AGENT'] : '';
		self::$data['HTTP_ACCEPT_LANGUAGE']  = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
		self::$data['HTTP_ACCEPT_ENCODING']  = isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';		
		self::$data['HTTP_ACCEPT_CHARSET']   = isset( $_SERVER['HTTP_ACCEPT_CHARSET'] )  ? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
		self::$data['HTTP_ACCEPT']           = isset( $_SERVER['HTTP_ACCEPT'] )          ? $_SERVER['HTTP_ACCEPT'] : '';

        return self::$data;
    }

	/**
	* Reset properties
	*/
    public function __destruct()
	{
		self::$data = null;
	}

	/**
	* Get IP, Proxy
	*/
    public function GetRemoteInfo()
	{

		$proxy = '';
		$ip    = '';
		if( isset( $_SERVER ) )
		{
			if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			{
               $ip     = $_SERVER['HTTP_X_FORWARDED_FOR'];
               $proxy  = $_SERVER['REMOTE_ADDR'];
			}elseif( isset( $_SERVER['HTTP_CLIENT_IP'] ) ){
               $ip     = $_SERVER['HTTP_CLIENT_IP'];
			}else{
               $ip     = $_SERVER['REMOTE_ADDR'];
			}
		}else{
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
			{
               $ip     = getenv( 'HTTP_X_FORWARDED_FOR' );
               $proxy  = getenv( 'REMOTE_ADDR' );
			}elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
               $ip     = getenv( 'HTTP_CLIENT_IP' );
			}else{
               $ip     = getenv( 'REMOTE_ADDR' );
			}
		}
		if( strstr( $ip, ',' ) )
		{
			$ips  = explode( ',', $ip );
			$ip   = $ips[0];
		}

		self::$data['REMOTE_ADDR']         = $ip;
		self::$data['REMOTE_HOST_BY_ADDR'] = @gethostbyaddr( $ip );
		self::$data['REMOTE_PROXY']        = $proxy;

		unset( $ip, $proxy );

		return self::$data;
    }

}