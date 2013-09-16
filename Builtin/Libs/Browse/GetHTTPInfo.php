<?php

/**
* @class  GetHTTPInfo
* @file   GetHTTPInfo.php
* @brief  GetHTTPInfo browse functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Browse;

class GetHTTPInfo
{

	/**
	* (array) Holds the data information
	*/
	private $data;

	/**
	* [review]
	* Get user browser HTTP header information
	*/
	public function Exec()
	{

		$this -> data['HTTP_REFERER']          = isset( $_SERVER['HTTP_REFERER'] ) ?       $_SERVER['HTTP_REFERER'] : '';
		$this -> data['HTTP_CONNECTION']       = isset( $_SERVER['HTTP_CONNECTION'] )      ? $_SERVER['HTTP_CONNECTION'] : '';
		$this -> data['HTTP_USER_AGENT']       = isset( $_SERVER['HTTP_USER_AGENT'] )      ? $_SERVER['HTTP_USER_AGENT'] : '';
		$this -> data['HTTP_ACCEPT_LANGUAGE']  = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
		$this -> data['HTTP_ACCEPT_ENCODING']  = isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';		
		$this -> data['HTTP_ACCEPT_CHARSET']   = isset( $_SERVER['HTTP_ACCEPT_CHARSET'] )  ? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
		$this -> data['HTTP_ACCEPT']           = isset( $_SERVER['HTTP_ACCEPT'] )          ? $_SERVER['HTTP_ACCEPT'] : '';

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