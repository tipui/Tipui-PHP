<?php

/**
* @class  Lang
* @file   Lang.php
* @brief  Lang Helper/Languages functions.
* @date   2014-02-28 00:37:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-29 02:53:00
*/

namespace Tipui\Builtin\Helpers\Languages;

class Lang extends \Tipui\Builtin\Helpers\Languages
{

	/**
	* Switch the language
	*
	* Set the language code for current instance
	* \Tipui\Builtin\Helpers\Languages::Lang( 'lang_code' );
	*
	* Setting language and writing the label at same line
	* \Tipui\Builtin\Helpers\Languages::Lang( 'lang_code' ) -> Label( 'label_index' );
	*/
	public function Exec( $lang_code = null )
	{

		if( empty( $lang_code ) )
		{
			/**
			* Retrieves from user current session
			*/
			if( !$lang_code = \Tipui\Core::GetContext()->LanguageCodeFromParameters )
			{
				/**
				* Retrieves from defaults
				*/
				$lang_code = \Tipui\Core::GetConf() -> TEMPLATES -> DEFAULT_LANGUAGE;
			}
		}

		self::$lang_code = $lang_code;

        return new self;

	}

}