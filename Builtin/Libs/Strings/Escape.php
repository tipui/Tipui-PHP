<?php

/**
* @class  Escape
* @file   Escape.php
* @brief  Escape strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

use Tipui\Builtin\Libs as Libs;

class Escape
{

	/**
	* Removes blank spaces from the beginning and the end of an string
	*/
	public function Exec( $str, $escape = false, $exceptions = false )
	{

		/**
		* Debug purposes
		*/
		//print_r( $escape ); exit;
		//echo time(); exit;
		//$str = urldecode( $str );

		if( is_array( $escape ) or !$escape )
		{
			if( !$escape or in_array( 'strip_tags', $escape ) )
			{
				//echo $exceptions; exit;
				$str = Libs\Strings::StripTags( $str, $exceptions );
			}

			if( !$escape or in_array( 'trim', $escape ) )
			{
				$str = Libs\Strings::Trim( $str );
			}

			if( !$escape or in_array( 'php', $escape ) )
			{
				$str = str_ireplace( '<?php ', '&#60;?php ', $str );
			}

			if( !$escape or in_array( 'form', $escape ) )
			{
				$f = array( '<textarea',     '</textarea',     '<form',     '</form',     '<input' );
				$t = array( '&#60;textarea', '&#60;/textarea', '&#60;form', '&#60;/form', '&#60;input' );
				$str = str_ireplace( $f, $t, $str );
			}

			if( !$escape or in_array( 'script', $escape ) )
			{
				$f = array( '<script',     '</script' );
				$t = array( '&#60;script', '&#60;/script' );
				$str = str_ireplace( $f, $t, $str );
			}
			
			if( !$escape or in_array( 'headers', $escape ) )
			{
				$f = array( '<html',     '</html',     '<head',     '</head',     '<body',     '</body' );
				$t = array( '&#60;html', '&#60;/html', '&#60;head', '&#60;/head', '&#60;body', '&#60;/body' );
				$str = str_ireplace( $f, $t, $str );
			}

			if( $escape and in_array( 'htmlspecialchars', $escape ) )
			{
				$str = htmlspecialchars( $str, ENT_QUOTES );
			}

		}else{
			if( $escape == 'quotes' )
			{
				$str = str_replace( '"', '&quot;', $str );
			}
			if( $escape == 'url_slash' )
			{
				$str = str_ireplace( '_-_', '/', $str );
			}
			if( $escape == 'slash_url' )
			{
				$str = str_ireplace( '/', '_-_', $str );
			}

		}

		return $str;

		/**
		* [usage]
			Libs\Strings::Escape( 'foo bar', array( 'php', 'form' ) );
			Libs\Strings::Escape( 'foo bar', array( 'php' ) );
			Libs\Strings::Escape( 'foo bar', array( 'php', 'form', 'strip_tags' ) );
			Libs\Strings::Escape( 'foo bar' ); // apply all filters
		*/
    }

}