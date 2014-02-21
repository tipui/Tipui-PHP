<?php

/**
* @class  SetURLParam
* @file   SetURLParam.php
* @brief  SetURLParam Builtin Form Lib functions.
* @date   2014-01-22 11:40:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-02-02 00:37:00
*/

namespace Tipui\Builtin\Libs\Form;

use \Tipui\Builtin\Libs\DataRules as DataRules;

class SetURLParam extends \Tipui\Builtin\Libs\Form
{

	/**
	* Set the main parameter that identifies the module to be called
	* @see self::AddURLParam
	*/
	public function Exec( $val )
	{
		return $this -> SetElement( \Tipui\Builtin\Helpers\HTML\Form::SetURLParam( \Tipui\Core::GetConf() -> URL -> PARAM_NAME ), 'input_hidden', true, array( DataRules::VALUE => $val, DataRules::DEFAULTS => $val, DataRules::EXACT_VALUE => $val ) );
	}

}