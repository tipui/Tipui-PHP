<?php

/**
* @class  Session
* @file   Session.php
* @brief  Session functions.
* @date   2010-03-25 00:49:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:28:00
*/

namespace Tipui\Builtin\Libs;

class Session
{

    private $data;
    private $exists;

    public function __construct()
    {
		$this -> ResetProperties();

		/**
		* If session_id is empty, means that session was not started.
		*/
        if( session_id() == '' )
        {
            session_start();
        }
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

    public function Set( $key, $value )
    {
        $_SESSION[$key] = $value;
        return null;
    }

	/**
	* Get the value of array key.
	* If the key is false, returns entire array.
	*/
    public function Get( $key = false )
    {

        if( $key )
        {

			/**
			* Returns single key of array, if exists.
			*/
            if( isset( $_SESSION[$key] ) )
            {

                $this -> data   = $_SESSION[$key];
                $this -> exists = true;

            }else{

                $this -> exists = false;
            }

        }else{

			/**
			* Returns entire array if exists.
			*/
            if( isset( $_SESSION ) )
            {
                $this -> data = $_SESSION;
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
    public function Del( $key = false )
    {

        if( $key )
        {
            if( isset( $_SESSION[$key] ) )
            {
                unset( $_SESSION[$key] );
            }

        }else{
            // unset entire array
            foreach( $_SESSION as $k => $v )
            {
                unset( $_SESSION[$k] );
            }

        }

        return null;

    }

    public function Destroy()
    {
        if( session_id() != '' )
        {
            session_destroy();
        }
    }

}