<?php

/**
* @class  AddForm
* @file   AddForm.php
* @brief  AddForm HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-02-19 16:36:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class AddForm extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add new form object
	*/
	public function Exec( $id = false, $action = false, $name = false, $method = false )
	{
		!$action ? $action = \Tipui\Builtin\Libs\Form::GetAction() : null;
		!$id     ? $id     = 'frm1' : null;
		!$name   ? $name   = 'frm1' : null;
		!$method ? $method = \Tipui\Builtin\Libs\Form::GetMethod() : null;
		return '<form id="' . $id . '" name="' . $name . '" action="' . $action . '"' . ' method="' . $method . '"' . self::ParametersAdd() . '>' . self::AddLangParam();
	}

}