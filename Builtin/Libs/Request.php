<?php

/**
* @class  Request
* @file   Request.php
* @brief  URL and CLI parameters abstraction.
* @date   2013-06-22 14:50:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: yyyy-mm-dd hh:ii:ss
*/

namespace Tipui\Builtin\Libs;

class Request
{

	/**
	* (boolean) Handles the Core IsModeCli method
	*/
	private $sapi_is_cli;

	/**
	* method defined by model/user
	*/
	protected $rq_method        = false;

	/**
	* parameter defined by model/user
	*/
    protected $rq_parameter     = false;

	/**
	* handles the server URI (Entire URL or CLI parameters)
	*/
    protected $uri              = false;

	/**
	* handles the server URI (HTTP only)
	*/
    protected $request_uri      = false;

	/**
	* (boolean) true: on, false: off
	*/
    protected $mod_rewrite;

	/**
	* handles all parameters extrated from URL or CLI
	*/
    protected $parameters       = false;

	/**
	* Parameter separator of URL
	*/
    protected $url_pfs;

	/**
	* Parameter argumentor of URL or CLI parameters
	*/
    protected $url_param_argumentor;

	/**
	* Real method received
	*/
    private $method;

	/**
	* (boolean) true: enables magic quotes checking, false: disable it
	*/
    private $check_magic_quotes;

	function __construct()
	{
		$this -> SetDefaults();
	}

	public function SetDefaults()
	{

		$this -> SetSapiMode();
		$this -> SetURLParts();

		$this -> method             = $this -> GetMethod();
		$this -> mod_rewrite        = true;
		$this -> check_magic_quotes = false;

		return null;
	}

	public function IsModeRewrite()
	{
		return $this -> mod_rewrite;
	}

	public function GetMethod()
	{
		if( $this -> sapi_is_cli or !isset( $_SERVER['REQUEST_METHOD'] ) )
        {
			return false;
		}

		if( $r = $this -> ValidMethod() )
		{
			return $r;
		}

		unset( $r );

		return 'GET';
	}

	public function SetURLParts( $href_base = '/', $pfs = '/', $param_argumentor = '?' )
	{
		$this -> url_href_base        = $href_base;
		$this -> url_pfs              = $pfs;
		$this -> url_param_argumentor = $param_argumentor;
		unset( $href_base, $param_argumentor, $pfs );
		return null;
	}

	public function SetSapiMode( $mode = false )
	{
		$this -> sapi_is_cli = $mode;
		return null;
	}

	public function SetMethod( $method = 'GET' )
	{
		if( $this -> sapi_is_cli or !isset( $_SERVER['REQUEST_METHOD'] ) )
        {
			return false;
		}

		if( $r = $this -> ValidMethod( $method ) )
		{
			$this -> rq_method = $r;
		}

		unset( $r, $method );

		return null;
	}

	public function SetParameter( $parameter )
	{
		$this -> rq_parameter = $parameter;
		return null;
	}

	private function ValidMethod( $method = false )
	{
		/**
		GET Retrieve the resource from the server
		POST Create a resource on the server
		PUT Update the resource on the server
		DELETE Delete the resource from the server
		*/
		$r = (!$method) ? strtoupper( $_SERVER['REQUEST_METHOD'] ) : $method;
		if( in_array( $r, array( 'GET', 'POST', 'FILES', 'PUT', 'DELETE' ) ) )
		{
			return $r;
		}
		return false;
	}

	public function Extract()
	{
		/**
		* Validating user defined method
		*/
		if( $this -> rq_method and $this -> rq_method != $this -> method )
		{
			return false;
		}

		/**
		* If user defined method is false, then assigns the default method (GET)
		*/
		if( !$this -> rq_method )
		{
			$this -> rq_method = $this -> method;
		}

		return $this -> ExtractURI();
	}

	private function ExtractURI()
	{

		if( !$this -> sapi_is_cli )
		{

			/**
			* @brief Extracting from URL, HTTP
			* ie: /?p=Foo/Bar
			*/
			$this -> request_uri = $_SERVER['REQUEST_URI'];
			//echo $this -> request_uri; exit;
			$this -> uri = explode( $this -> url_pfs, $this -> request_uri );
			//print_r( $this -> uri ); exit;

			if( is_array( $this -> uri ) and isset( $this -> uri[1] ) )
			{
				// removes the first index where containing the first slash before argumentor and parameters
				array_shift( $this -> uri );
			}else{
				throw new \Exception('Invalid URI.');
			}

			// if the first string is the parameter argumentor (?), means that is a normal URL (ie: http://foo.bar/?p=x)
			$this -> mod_rewrite = ( substr( $this -> uri[0], 0, 1 ) == $this -> url_param_argumentor ) ? false: true;
			
			return $this -> ParametersFromHTTP();

		}else{

			/**
			* @brief Extract from command line (sapi cli mode)
			* ie: php.exe script.php -p Foo/Bar
			*/
			$this -> parameters = getopt( $this -> rq_parameter . ':', array( 
				'required:',     // Required value 
				'optional::',    // Optional value 
				'option',        // No value 
				'opt',           // No value 
			) );

			if( $this -> rq_parameter )
			{
				// return single index if exists
				if( isset( $this -> parameters[$this -> rq_parameter] ) )
				{
					return $this -> parameters[$this -> rq_parameter];
				}

			}else{

				// return entire array
				return $this -> parameters;

			}

		}

		return null;
	}

	private function ParametersFromHTTP()
	{

		if( !$this -> mod_rewrite )
		{

			$this -> parameters = $GLOBALS['_' . $this -> method];

			if( is_array( $this -> parameters ) and count( $this -> parameters ) > 0 )
			{

				// support for old and ugly configured servers..
				if( $this -> check_magic_quotes and get_magic_quotes_gpc() )
				{
					$this -> parameters = array_map( 'stripslashes', $this -> parameters );
				}

				if( $this -> rq_parameter )
				{
					// return single index if exists
					if( isset( $this -> parameters[$this -> rq_parameter] ) )
					{
						return $this -> parameters[$this -> rq_parameter];
					}

				}else{

					// return entire array
					return $this -> parameters;

				}

			}

		}else{

			// Must check if app index.php file is running under subfolder to avoid conflicts with parameters and the folder base
			//$this -> url_href_base = '/new/';
			$url_href_base_length = Strings::StrLen( $this -> url_href_base );
			if( $url_href_base_length > 1 )
			{
				// if lengh is more than 1, means that app is using subfolder
				$this -> request_uri = substr( $this -> request_uri, $url_href_base_length );
			}

			// if remains any string, assign them to $this -> uri as array
			if( Strings::StrLen( $this -> request_uri ) > 0 )
			{
				$this -> uri = explode( $this -> url_pfs, $this -> request_uri );
				if( $url_href_base_length == 1 )
				{
					array_shift( $this -> uri );
				}
				//print_r( $this -> uri ); exit;
				//echo $this -> request_uri; exit;

				return implode( $this -> url_pfs, $this -> uri );
			}

			unset( $url_href_base_length );
		}
		
		return null;
	}
}

?>