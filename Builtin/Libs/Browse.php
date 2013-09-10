<?php

/**
* @class  Browse
* @file   Browse.php
* @brief  Browse functions.
* @date   2013-07-11 03:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:28:00
*/

namespace Tipui\Builtin\Libs;

/**
* Browse headers and general info (REFERER, IP, Browser name, version, language, etc)
*/
class Browse
{

	/**
	* (array) Holds the server and client headers
	*/
	private $data;

	/**
	* Initiates general settings
	*/
    public function __construct()
	{

		$this -> GetData();

        return null;
    }

	/**
	* Get header information
	*/
    public function GetData()
	{

		$rnifo = self::GetRemoteInfo();

		$this -> data['SessionID']             = session_id();
		$this -> data['TimeIni']               = time();
		$this -> data['TimeEnd']               = false;

		$this -> data['REQUEST_URI']           = urldecode( $_SERVER['REQUEST_URI'] );

		$this -> data['PageStart']             = $this -> data['REQUEST_URI'];
		$this -> data['PageCurrent']           = $this -> data['REQUEST_URI'];
		$this -> data['PageNext']              = false;
		$this -> data['PagePrevious']          = false;

		$this -> data['REMOTE_ADDR']           = $rnifo['IP']; //$_SERVER['REMOTE_ADDR'];
		$this -> data['REMOTE_HOST_BY_ADDR']   = $rnifo['REMOTE_HOST_BY_ADDR'];
		$this -> data['REMOTE_PROXY']          = $rnifo['PROXY'];
		$this -> data['REMOTE_PORT']           = $_SERVER['REMOTE_PORT'];

		$this -> data['HTTP_REFERER']          = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
		$this -> data['REQUEST_METHOD']        = $_SERVER['REQUEST_METHOD'];

		$this -> data['HTTP_CONNECTION']       = isset( $_SERVER['HTTP_CONNECTION'] )      ? $_SERVER['HTTP_CONNECTION'] : '';
		$this -> data['HTTP_USER_AGENT']       = isset( $_SERVER['HTTP_USER_AGENT'] )      ? $_SERVER['HTTP_USER_AGENT'] : '';
		$this -> data['HTTP_ACCEPT_LANGUAGE']  = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
		$this -> data['HTTP_ACCEPT_ENCODING']  = isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';		
		$this -> data['HTTP_ACCEPT_CHARSET']   = isset( $_SERVER['HTTP_ACCEPT_CHARSET'] )  ? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
		$this -> data['HTTP_ACCEPT']           = isset( $_SERVER['HTTP_ACCEPT'] )          ? $_SERVER['HTTP_ACCEPT'] : '';

		unset( $rnifo );

        return $this -> data;
    }

	/**
	* Reset properties
	*/
    public function __destruct()
	{
		$this -> data = null;
	}

	/**
	* Get IP, Proxy
	*/
    public  static function GetRemoteInfo()
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

		return array(
					'IP'                   => $ip,
					'REMOTE_HOST_BY_ADDR'  => @gethostbyaddr( $ip ),
					'PROXY'                => $proxy
					);
    }
}