<?php

/**
* @class  SEO_strip
* @file   SEO_strip.php
* @brief  SEO_strip strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

use Tipui\Builtin\Libs as Libs;

class SEO_strip
{

	/**
	* Limit of chars for friendly URL name
	*/
	public function Exec( $str, $limit = 100 )
	{
		return Libs\Strings::SEOFilter( trim( substr( $str, 0, $limit ) ) );
    }

}