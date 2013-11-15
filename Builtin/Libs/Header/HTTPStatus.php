<?php

/**
* @class  HTTPStatus
* @file   HTTPStatus.php
* @brief  HTTPStatus Header functions.
* @date   2013-09-16 19:16:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-11-15 22:26:00
*/

namespace Tipui\Builtin\Libs\Header;

class HTTPStatus
{

	/**
	* HTTP Status response
	* Reference: http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	*/
	public function Exec( $code )
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
			
			// http://stackoverflow.com/questions/9454811/which-http-status-code-to-use-for-required-parameters-not-provided
			case 422:
			$title = 'Unprocessable Entity (WebDAV; RFC 4918)'; // bad / invalid parameters
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