<?php

/**
* @class  Request
* @file   Request.php
* @brief  URL and CLI parameters abstraction.
* @date   2013-06-22 14:50:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 21:00:00
*/

namespace Tipui\Builtin\Libs;

use Tipui\Builtin\Libs\Strings as Strings;

class Request
{

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
	* $t = new Libs\Request;
	* $t -> Init( base_path, tag, output );
	* $t -> Compile( dataArray, template_dir, template_file );
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
	* Libs\Request::Init( base_path, tag, output );
	* Libs\Request::Compile( dataArray, template_dir, template_file );
	* [/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Request', $name, $arguments );
    }

}