<?php

/**
* @class  Storage
* @file   Storage.php
* @brief  Storage functions.
* @date   2013-07-12 04:48:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-07-12 04:48:00
*/

namespace Tipui\Builtin\Libs;

class Storage
{

	const MODE_ARRAY    = 'array';
	const MODE_SESSION  = 'session';
	const MODE_SQLITE   = 'sqlite';

	/**
	* (array) Holds the data as array
	* If mode is not array, will be empty after saved.
	*/
	private $data;

	/**
	* (string) Defines the storage mode
	* array, session, sqlite, etc
	*/
	private $mode;

	/**
	* Initiates general settings
	*/
    public function __construct($mode)
	{

		$this -> SetMode( $mode );

        return null;
    }

	/**
	* Set mode (array, session, sqlite, etc)
	*/
    public function SetMode()
	{
		$this -> mode = $mode;
	}

	/**
	* Set data
	*/
    public function SetData( $data )
	{

		foreach( $data as $k => $v )
		{
			$this -> data[$k] = $v;
		}

		unset( $k, $v, $data );

		switch( $this -> mode )
		{
			case $this -> MODE_SESSION:
				// save to session
			break;
			case $this -> MODE_SQLITE:
				throw new \Exception( $this -> MODE_SQLITE . ' not available.' );
			break;
		}

		if( $this -> mode != $this -> MODE_ARRAY )
		{
			$this -> data = null;
		}

        return null;
    }

}
?>