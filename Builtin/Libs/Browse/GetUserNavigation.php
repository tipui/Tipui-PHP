<?php

/**
* @class  GetUserNavigation
* @file   GetUserNavigation.php
* @brief  GetUserNavigation browse functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Browse;

class GetUserNavigation
{

	/**
	* (array) Holds the data information
	*/
	private $data;

	/**
	* [review]
	* Get user browser navigation information
	*/
	public function Exec()
	{
		$this -> data['SessionID']             = session_id();

		/**
		* [review]
		* Request method and parameters
		*/
		$this -> data['REQUEST_URI']           = urldecode( $_SERVER['REQUEST_URI'] );
		$this -> data['REQUEST_METHOD']        = $_SERVER['REQUEST_METHOD'];

		/**
		* [review]
		* First page where the user landed.
		*/
		$this -> data['TimeIni']               = null; // sets on first entrance and hold in session time()
		$this -> data['PageIni']               = null; // sets on first entrance and hold in session $this -> data['REQUEST_URI'];

		/**
		* [review]
		* Current navigating page
		*/
		$this -> data['PageCurrent']           = $this -> data['REQUEST_URI'];
		$this -> data['PageCurrentTimeIni']    = time();
		$this -> data['PageCurrentTimeEnd']    = null;

		/**
		* [review]
		* Page to forward or reward
		*/
		$this -> data['PageNext']              = null;
		$this -> data['PagePrevious']          = null;

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