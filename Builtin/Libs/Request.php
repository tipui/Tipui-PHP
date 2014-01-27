<?php

/**
* @class  Request
* @file   Request.php
* @brief  URL and CLI parameters abstraction.
* @date   2013-06-22 14:50:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-23 12:50:00
*/

namespace Tipui\Builtin\Libs;

use Tipui\Builtin\Libs\Strings as Strings;

class Request
{

	/**
	* Constants that defines valid methods
	*/
	const METHOD_GET    = 'GET';
	const METHOD_POST   = 'POST';
	const METHOD_FILES  = 'FILES';
	const METHOD_PUT    = 'PUT';
	const METHOD_DELETE = 'DELETE';

	/**
	* Parameter defined by model/user config settings
	*/
    protected static $rq_parameter;

	/**
	* (boolean) Handles the Core IsModeCli method
	*/
	protected static $sapi_is_cli;

	/**
	* Handles URL base path
	*/
	protected static $url_href_base;

	/**
	* Handles URL folder separator path
	*/
	protected static $url_pfs;

	/**
	* Handles URL parameter argument
	*/
	protected static $url_param_argumentor;

	/**
	* Method defined by model/user
	*/
	protected static $rq_method;

	/**
	* Real method received
	*/
    protected static $method;

	/**
	* Handles the server URI (Entire URL or CLI parameters)
	*/
    protected static $uri;

	/**
	* handles the server URI (HTTP only)
	*/
    protected static $request_uri;

	/**
	* (boolean) true: on, false: off
	*/
    protected static $mod_rewrite;

	/**
	* Handles all parameters extracted from URL or CLI
	*/
	protected static $parameters;

	/**
	* (boolean) true: enables magic quotes checking, false: disable it
	*/
    protected static $check_magic_quotes;

	public function SetDefaults()
	{

		$this -> SetSapiMode();
		$this -> SetURLParts();

		self::$rq_method          = null;
		self::$rq_parameter       = null;
		self::$uri                = null;
		self::$request_uri        = null;
		self::$parameters         = null;
		self::$method             = self::GetMethod();
		self::$mod_rewrite        = true;
		self::$check_magic_quotes = false;

		return null;
	}

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Libs\Request;
	* $c -> MethodName();
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Request', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]
	* Request::MethodName();
	* [/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Request', $name, $arguments );
    }

}