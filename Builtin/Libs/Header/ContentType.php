<?php

/**
* @class  ContentType
* @file   ContentType.php
* @brief  ContentType Header functions.
* @date   2013-12-25 04:31:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-25 04:31:00
*/

namespace Tipui\Builtin\Libs\Header;

class ContentType
{

	/**
	* Header content type and charset
	*/
	public function Exec( $type = 'text/plain', $charset = 'UTF-8' )
	{
		header( 'Content-Type: ' . $type . '; charset=' . $charset );
        return null;
    }

}