<?php

/**
* @class  GetRemoteInfo
* @file   GetRemoteInfo.php
* @brief  GetRemoteInfo browse functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Browse;

class GetRemoteInfo
{

	/**
	* (array) Holds the data information
	*/
	private $data;

	/**
	* [review]
	* Get user IP, proxy, remote address
	*/
	public function Exec()
	{
		$proxy = null;
		$ip    = null;
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

		$this -> data['REMOTE_ADDR']         = $ip;
		$this -> data['REMOTE_HOST_BY_ADDR'] = @gethostbyaddr( $ip );
		$this -> data['REMOTE_PROXY']        = $proxy;
		$this -> data['REMOTE_PORT']         = $_SERVER['REMOTE_PORT'];
		
		unset( $ip, $proxy );

		return $this -> data;
	}

	/**
	* Reset properties
	*/
    public function __destruct()
	{
		$this -> data = null;
	}

}