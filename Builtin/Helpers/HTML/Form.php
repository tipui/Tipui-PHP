<?php

/**
* @class  Form
* @file   Form.php
* @brief  HTML Form Helper functions.
* @date   2013-09-12 03:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-13 00:28:00
*/

namespace Tipui\Builtin\Helpers\HTML;

use Tipui\Builtin\Libs as Libs;

class Form
{

	/**
	* Handles Libs\Form::$parameters
	*/
	protected static $parameter = null;

	/**
	* Optional form object name as array
	* [code]HTML\Form::$key_add = 'a';[/code]
	* i.e. [code]<input name="foo[a]"...[/code]
	*/
	protected static $key_add;

	/**
	* [code]<input class=""[/code]
	*/
	protected static $css_name = null;

	/**
	* [code]<input readonly[/code]
	*/
	protected static $readonly = false;

	/**
	* Additional parameters or inline scripts like css or js.
	* [code]HTML\Form::$tag_params = 'id=foo';[/code]
	* i.e. [code]<input id="foo"...[/code]
	* [code]HTML\Form::$tag_params = array('id'=>'foo');[/code]
	* i.e. [code]<input id="foo"...[/code]
	*/
	protected static $tag_params = false;

	/**
	* Add new form object
	*/
	public static function AddForm( $id = false, $action = false, $name = false )
	{
		$c = new \Tipui\Core;
		!$id     ? $id     = 'frm1' : null;
		!$name   ? $name   = 'frm1' : null;
		!$action ? $action = $c -> GetEnv( 'URL', 'FORM_ACTION' ) : null;
		unset( $c );
		return '<form id="' . $id . ' name="' . $name . '" action="' . $action . '"' . self::ParametersAdd() . '>';
	}

	/**
	* Add new input element
	*/
	public static function AddField( $name )
	{

		/**
		* Debug purposes
		*/
		self::$parameter = Libs\Form::GetParameter( $name );

		/**
		* For array types (multiple)
		*/
        if( is_array( self::$parameter['type'] ) )
        {
			// [review]
            foreach( self::$parameter['type'] as $k => $type )
            {
                $arr['type']        = $type;
                $arr['size']        = self::$parameter['size'][$k];
                $arr['MaxLength']   = self::$parameter['MaxLength'][$k];
                $arr['MinLength']   = self::$parameter['MinLength'][$k];
                $arr['value']       = self::$parameter['value'][$k];
                $arr['default']     = self::$parameter['default'][$k];
                $arr['options']     = self::$parameter['options'][$k];
                $arr['validation']  = self::$parameter['validation'];
                $rs[] = self::InputType( $name . '[' . self::$parameter['names'][$k] . ']', $arr );
				unset($arr);
            }

        }else{
			/**
			* Single type
			*/
            $rs = self::InputType( $name, self::$parameter );
        }

		/**
		* Reset class name property and readonly attribute
		*/
		self::AddFieldCSSName( null );
		self::AddFieldReadOnly( false );
		self::AddFieldTagParams( false );
		self::AddFieldKey( null );

		return $rs;

	}

	public static function InputType( $name, $property )
    {

        switch( $property['type'] )
        {

            case 'hidden':

                return self::InputHidden( $name, $property );

            break;
            case 'text':
            case 'password':

                return self::InputText( $name, $property );

            break;

            case 'textarea':

                return self::InputTextarea( $name, $property );

            break;

            case 'select':

                return self::InputSelect( $name, $property );

            break;
            case 'radio':

                return self::InputRadio( $name, $property );

            break;
            case 'checkbox':

                return self::InputCheckbox( $name, $property );

            break;
            case 'file':

                return self::InputFile( $name, $property );

            break;

        }

        return null;

    }

	public static function ParametersAdd()
	{
		$rs = ' ';

		if( self::$tag_params )
		{
			if( is_array( self::$tag_params ) )
			{
				foreach( self::$tag_params as $k => $v )
				{
					$rs .= ' ' . $k . '="' . $v . '"';
				}
				$rs .= ' ';
			}else{
				$rs .= self::$tag_params . ' ';
			}
		}

		return $rs;
	}

	public static function AddFieldCSSName( $key )
	{
		self::$css_name = $key;
	}

	public static function AddFieldReadOnly( $key )
	{
		self::$readonly = $key;
	}

	public static function AddFieldTagParams( $key )
	{
		self::$tag_params = $key;
	}

	public static function AddFieldKey( $key )
	{
		self::$key_add = $key;
	}

	public static function InputText( $name, $property )
    {
		$rs  = '<input type="text"' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$key_add )
		{
			if( !is_array( self::$key_add  ) )
			{
				$rs .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}
		}
		
		/**
		* value property
		*/
		$rs .= '" value="';

		if( !is_array( $property['value'] ) )
		{
			/**
			* If value is empty, check for default property.
			* Value must be filtered to avoid HTML injections.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= Libs\Strings::Escape( $property['value'], 'quotes' );
			}else{
				$rs .= $property['default'];
			}
		}else{
			/**
			* value is array
			*/
			throw new \Exception('Input text value as array not implemented');
		}

		/**
		* size, maxLength, ExactLength properties
		*/
		$rs .= '" size="' . $property['size'] . '" maxLength="' . ( ( isset( $property['MaxLength'] ) ) ? $property['MaxLength'] : $property['ExactLength'] ) . '"';

		/**
		* class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		/**
		* readonly attribute
		*/
		if( self::$readonly )
		{
			$rs .= ' readonly';
		}

		$rs .= ' />';

		return $rs;
	}

	public static function InputHidden( $name, $property )
	{

		$rs  = '<input type="hidden"' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$key_add )
		{

			if( !is_array( self::$key_add  ) )
			{
				$rs .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}

		}
		
		/**
		* value property
		*/
		$rs .= '" value="';

		if( !is_array( $property['value'] ) )
		{

			/**
			* If value is empty, check for default property.
			* For both, filter to avoid HTML injections.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= Libs\Strings::Escape( $property['value'], 'quotes' );

			}else{
				$rs .= Libs\Strings::Escape( $property['default'], 'quotes' );

			}

		}else{

			/**
			* Value is array
			*/

			if( self::$key_add )
			{

				self::$ArrayVal = '';
				self::ArrayVal( $property['value'] );
				
				if( self::$ArrayVal == '' )
				{
					if( isset( $property['default'] ) )
					{
						$rs .= $property['default'];
					}
				}else{					
					$rs .= Strings::Escape( self::$ArrayVal, 'quotes' );
				}

			}else{
				$rs .= Strings::Escape( $property['default'], 'quotes' );

			}

		}

		$rs .= '" />';

		return $rs;

	}

	public static function InputFile( $name, $property )
    {

		return '<input type="file"' . self::ParametersAdd() . 'name="' . $name . '" />';

    }

	public static function InputTextarea( $name, $property )
    {
		$rs  = '<textarea' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$key_add )
		{
			if( !is_array( self::$key_add  ) )
			{
				$rs .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}
		}

		$rs .= '" cols="' . $property['cols'] . '" rows="' . $property['rows'] . '"';

		/**
		* class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		/**
		* readonly attribute
		*/
		if( self::$readonly )
		{
			$rs .= ' readonly="readonly"';
		}

		$rs .= '>';

		/**
		* value field
		*/
		if( !is_array( $property['value'] ) )
		{
			/**
			* If value is empty, check for default property.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= $property['value'];

			}else{
				$rs .= $property['default'];

			}
		}else{

			/**
			* Value is array
			*/

			if( self::$key_add )
			{

				self::$ArrayVal = '';
				self::ArrayVal( $property['value'] );
				
				if( self::$ArrayVal == '' )
				{
					if( isset( $property['default'] ) )
					{
						$rs .= $property['default'];
					}
				}else{					
					$rs .= Strings::Escape( self::$ArrayVal, 'quotes' );
				}

			}else{
				$rs .= Strings::Escape( $property['default'], 'quotes' );

			}

		}

        $rs .= '</textarea>';

        return $rs;
    }

	public static function InputRadio( $name, $property )
    {

		$rs = self::GroupingFieldOptionProperty( $name, $property );

        return $rs;

    }

	public static function InputCheckbox( $name, $property )
    {

		if( isset( $property['options'] ) )
		{
			return self::GroupingFieldOptionProperty( $name, $property );
		}

		$rs  = '<input type="checkbox"' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$key_add )
		{

			if( !is_array( self::$key_add  ) )
			{
				$rs .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}

		}

		$rs .= '" value="';

		/**
		* value field
		*/
		if( !is_array( $property['value'] ) )
		{
			/**
			* If value is empty, check for default property and ExactValue.
			* For value, must be filtered to avoid HTML injections.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= Libs\Strings::Escape( $property['value'], 'quotes' );
			}else if( !empty( $property['default'] ) ){
				$rs .= $property['default'];
			}else{
				$rs .= $property['ExactValue'];
			}
		}else{

			/**
			* Value is array
			*/
			throw new \Exception('Checkbox value as array not implemented');

		}

		$rs .= '"';

		/**
		* Class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		/**
		* Checked state
		*/
        $check = false;

		if( !empty( $property['value'] ) )
		{
			$rs .= ' checked';
		}else if( !empty( $property['default'] ) )
		{
			$rs .= ' checked';
		}

		$rs .= ' />';

        return $rs;

    }


    public static function InputSelect( $name, $property )
    {

		$rs  = '<select' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$key_add )
		{

			if( !is_array( self::$key_add  ) )
			{
				$rs .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}

		}

		$rs .= '"';

		/**
		* Class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		$rs .= '>';

		$check = false;

		if( $property['value'] != '' )
		{

			$check = (string)$property['value'];
			//echo gettype($check); exit;
		}else{

			if( $property['default'] )
			{
				$check = (string)$property['default'];
			}

		}

		/**
		* [review]
		* Multiple selected options not available
		*/
		if( isset( $property['options'] ) and is_array($property['options']) and count($property['options']) > 0 )
		{
			//print_r($data['options']);
			foreach( $property['options'] as $k => $v )
			{
				if( !is_array( $v ) )
				{
					$rs .= '<option';
					$rs .= ' value="' . $k . '"';
				}else{
					if( isset( $v['optgroup'] ) )
					{
						$rs .= '<optgroup label="' . $v['optgroup']  . '">';
					}
				}

				if( !is_array( $v ) and gettype($check) != 'boolean' and $check == $k )
				{
					$rs .= ' selected';
				}

				if( !is_array( $v ) )
				{
					$rs .= '>';
					$rs .= $v;
					$rs .= '</option>';
				}else{
					if( isset( $v['optgroup'] ) )
					{
						if( isset( $v['options'] ) and is_array( $v['options'] ) and count( $v['options'] ) > 0 )
						{
							foreach( $v['options'] as $k1 => $v1 )
							{
								$rs .= '<option';
								$rs .= ' value="' . $k1 . '"';
								if( gettype($check) != 'boolean' and $check == $k1 )
								{
									$rs .= ' selected';
								}
								$rs .= '>';
								$rs .= $v1;
								$rs .= '</option>';
							}
						}
						$rs .= '</optgroup>';
					}
				}
			}
		}

		$rs .= '</select>';

        return $rs;

    }

	protected static function GroupingFieldOptionProperty( $name, $property )
    {

		/**
		* name property
		*/
		$name_add = '';
		if( self::$key_add )
		{

			if( !is_array( self::$key_add  ) )
			{
				$name_add .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$name_add .= '[' . $v . ']';
				}
			}

			/**
			* Debug purposes
			*/
			//print_r( $v ); exit;
			//print_r( self::$key_add); exit;
			//print_r( $data['key'][self::$key_add] ); exit;

			if( isset( $property['value'][self::$key_add] ) )
			{
				$property['value'] = $property['value'][self::$key_add];
			}

		}

		/**
		* Debug purposes
		*/
		//print_r( $property ); exit;
		//print_r( $property['key'][self::$key_add] ); exit;
		//print_r( $property['value'] ); exit;

        $check = false;

		if( $property['value'] != '' )
		{
			if( is_string( $property['default'] ) )
			{
				$check = $property['value'];
			}else{
				$check = array_combine( $property['value'], $property['value'] );
			}
		}else{
			if( $property['default'] )
			{
				if( is_string( $property['default'] ) )
				{
					$check = $property['default'];
					settype( $check, 'string' );
				}else{
					$check = array_combine( $property['default'], $property['default'] );
				}
			}
		}

		/**
		* Debug purposes
		*/
		//print_r( $data['options'] ); exit;

        foreach( $property['options'] as $k => $v )
        {

			$rs[$k] = '<input type="' . $property['type'] . '"' . self::ParametersAdd() . 'name="' . $name . $name_add;

			if( isset( $property['multiple'] ) )
			{
				$rs[$k] .= '[' . $k . ']';
			}

			$rs[$k] .= '" value="' . $k . '"';
    
			if( $check )
			{

				settype( $k, 'string' );

				/**
				* Debug purposes
				*/
				//echo self::$key . ':' . gettype( $check ); exit;

				if( !is_array( $check ) and $check == $k )
				{
					$rs[$k] .= ' checked';
				}else{

					// For mutidimensional array choices
					if( isset( $property['multiple'] ) )
					{
						if( isset( $check[$k] ) )
						{
							$rs[$k] .= ' checked';
						}
					}

				}

			}
    
			/**
			* class property
			*/
			if( self::$css_name != null )
			{
				$rs[$k] .= ' class="' . self::$css_name . '"';
			}

			$rs[$k] .= ' />';

		}

		/**
		* Debug purposes
		*/
		//print_r( $rs ); exit;

        return $rs;
    }

	/**
	* Set property of an field
	*/
	public static function GetFieldProperty( $name, $property )
	{
		return self::$parameter[$property];

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
	}
}