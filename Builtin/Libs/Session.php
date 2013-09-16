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

/**
* Session library.
*/
class Session
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Session;
	* $c -> Set( 'foo', 'bar' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		self::StartCheck();
		return Factory::Exec( 'Session', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Session::Set( 'foo', 'bar' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		self::StartCheck();
		return Factory::Exec( 'Session', $name, $arguments );
    }

	/**
	* Check if session_id() is started.
	*
	*/
    protected function StartCheck()
    {
		/**
		* Debug purposes
		*/
		//echo time() . PHP_EOL;

		/**
		* If session_id is empty, means that session was not started.
		*/
        if( session_id() == '' )
        {
            session_start();
        }
    }

	/**
	* Debug purposes
	* [review] StartCheck() to construct and execute from static
	*/
	/*
    public function __construct()
    {
		echo time() . PHP_EOL;
	}
	*/

}