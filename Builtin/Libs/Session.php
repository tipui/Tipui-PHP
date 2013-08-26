<?php

/**
* @class  Session
* @file   Session.php
* @brief  Session functions.
* @date   2010-03-25 00:49:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-07-25 02:46:00
*/

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
        if( empty( session_id() ) )
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

	/**
	* Usage sample:
	* [code]
	* // Auto set default cookie parameters
	* $c = new Session;
	* $c -> Get( 'foo' );
	* if( $data = $c -> Exists() ){
	*     print_r( $data ); 
	* }
	* $c2 = new Cookie( $env_cookies['COOKIE_TIME'], $env_cookies['COOKIE_TIME_MODE'], $env_bootstrap['DOMAIN'], $env_bootstrap['SUBDOMAIN'] );
	* [/code]
	*/
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
        if( !empty( session_id() ) )
        {
            session_destroy();
        }
    }

}
?>