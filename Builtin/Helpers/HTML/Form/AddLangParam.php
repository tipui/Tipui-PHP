<?php

/**
* @class  AddLangParam
* @file   AddLangParam.php
* @brief  AddLangParam HTML Helper Form Elements functions.
* @date   2014-02-19 16:36:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-02-19 16:36:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class AddLangParam extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add hidden field for language code parameter
	*/
	public function Exec( $id = false, $action = false, $name = false, $method = false )
	{

		if( $lang_code = \Tipui\Core::GetConf()->GetMethodDataCache( 'LanguageCodeFromParameters' ) )
		{

			/**
			* If lang_code is different of the default language, it's not necessary to write the parameter for language code.
			*/
			if( $lang_code != \Tipui\Core::GetConf() -> TEMPLATES -> DEFAULT_LANGUAGE )
			{

				$id = \Tipui\Core::GetConf() -> URL -> PARAM_LANG;
				return '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $lang_code . '" />';

			}

		}

		return null;

	}

}