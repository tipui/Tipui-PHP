<?php

/**
* @class  Radio
* @file   Radio.php
* @brief  HTML Form Element input type Checkbox Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 18:30:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form\Elements;

class Radio extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		return self::GroupingOptionProperty( $name, $property );
	}

}