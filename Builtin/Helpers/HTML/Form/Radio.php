<?php

/**
* @class  Radio
* @file   Radio.php
* @brief  HTML Form input type Radio Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-14 02:56:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

use Tipui\Builtin\Libs as Libs;

class Radio extends \Tipui\Builtin\Helpers\HTML\Form
{

	protected static function Add( $name, $property )
    {
		return self::GroupingFieldOptionProperty( $name, $property );
	}

}