<?php

/**
* @class  Header
* @file   Header.php
* @brief  Header functions.
* @date   2013-07-08 02:18:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:28:00
*/

namespace Tipui\Builtin\Libs;

class Header
{

	/**
	* HTTP Status response
	* Reference: http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	*/
    public static function HTTPStatus( $code ) 
    {

		switch( $code )
		{
			case 200:
			$title = 'OK';
			break;
			case 204:
			$title = 'No Content';
			break;

			case 301:
			$title = 'Moved Permanently';
			break;

			case 400:
			$title = 'Bad Request';
			break;
			case 402:
			$title = 'Payment Required';
			break;
			case 401:
			$title = 'Unauthorized';
			break;
			case 403:
			$title = 'Forbidden';
			break;
			case 404:
			$title = 'Not Found';
			break;
			case 405:
			$title = 'Method Not Allowed';
			break;
			case 410:
			$title = 'Gone';
			break;
			case 429:
			$title = 'Too Many Requests';
			break;

			case 500:
			$title = 'Internal Server Error';
			break;
		}

		header( 'HTTP/1.0 ' . $code . ' ' . $title );

        return null;

    }

}