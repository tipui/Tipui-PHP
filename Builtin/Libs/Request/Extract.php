<?php

/**
* @class  Extract
* @file   Extract.php
* @brief  Extract Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class Extract extends Libs\Request
{

	/**
	* Extracts requested parameters
	*/
    public static function Exec( $method = false ) 
    {
		/**
		* Validates user defined method if exists
		*/
		if( self::$rq_method and self::$rq_method != self::$method )
		{
			return false;
		}

		/**
		* If user defined method is false, then assigns the default method (GET)
		*/
		if( !self::$rq_method )
		{
			self::$rq_method = self::$method;
		}

		return self::URI();
	}

	protected static function URI()
	{

		if( !self::$sapi_is_cli )
		{

			/**
			* @brief Extracting from URL, HTTP
			* ie: /?p=Foo/Bar
			*/
			self::$request_uri = $_SERVER['REQUEST_URI'];
			//echo self::$request_uri; exit;
			self::$uri = explode( self::$url_pfs, self::$request_uri );
			//print_r( self::$uri ); exit;

			if( is_array( self::$uri ) and isset( self::$uri[1] ) )
			{
				// removes the first index where containing the first slash before argumentor and parameters
				array_shift( self::$uri );
			}else{
				throw new \Exception('Invalid URI.');
			}

			// if the first string is the parameter argumentor (?), means that is a normal URL (ie: http://foo.bar/?p=x)
			self::$mod_rewrite = ( substr( self::$uri[0], 0, 1 ) == self::$url_param_argumentor ) ? false: true;

			return self::ParametersFromHTTP();

		}else{

			/**
			* @brief Extract from command line (sapi cli mode)
			* ie: php.exe script.php -p Foo/Bar
			*/
			self::$parameters = getopt( self::$rq_parameter . ':', array( 
				'required:',     // Required value 
				'optional::',    // Optional value 
				'option',        // No value 
				'opt',           // No value 
			) );

			if( self::$rq_parameter )
			{
				// return single index if exists
				if( isset( self::$parameters[self::$rq_parameter] ) )
				{
					return self::$parameters[self::$rq_parameter];
				}

			}else{

				// return entire array
				return self::$parameters;

			}

		}

		return null;
	}

	protected static function ParametersFromHTTP()
	{

		if( !self::$mod_rewrite )
		{

			self::$parameters = $GLOBALS['_' . self::$method];

			if( is_array( self::$parameters ) and count( self::$parameters ) > 0 )
			{

				// support for old and ugly configured servers..
				if( self::$check_magic_quotes and get_magic_quotes_gpc() )
				{
					self::$parameters = array_map( 'stripslashes', self::$parameters );
				}

				if( self::$rq_parameter )
				{
					// return single index if exists
					if( isset( self::$parameters[self::$rq_parameter] ) )
					{
						return self::$parameters[self::$rq_parameter];
					}

				}else{

					// return entire array
					return self::$parameters;

				}

			}

		}else{

			// Must check if app index.php file is running under subfolder to avoid conflicts with parameters and the folder base
			//self::$url_href_base = '/new/';
			$url_href_base_length = Libs\Strings::StrLen( self::$url_href_base );
			//var_dump($url_href_base_length); exit;
			if( $url_href_base_length > 1 )
			{
				// if lengh is more than 1, means that app is using subfolder
				self::$request_uri = substr( self::$request_uri, $url_href_base_length );
			}

			// if remains any string, assign them to self::$uri as array
			if( Libs\Strings::StrLen( self::$request_uri ) > 0 )
			{
				self::$uri = explode( self::$url_pfs, self::$request_uri );
				if( $url_href_base_length == 1 )
				{
					array_shift( self::$uri );
				}
				//print_r( self::$uri ); exit;
				//echo self::$request_uri; exit;

				return implode( self::$url_pfs, self::$uri );
			}

			unset( $url_href_base_length );
		}
		
		return null;
	}

}