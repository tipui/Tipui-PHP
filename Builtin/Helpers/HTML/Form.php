<?php

/**
* @class  Form
* @file   Form.php
* @brief  HTML Form Helper functions.
* @date   2013-09-12 03:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-12 03:03:00
*/

namespace Tipui\Builtin\Helpers\HTML;

use Tipui\Builtin\Libs as Libs;

class Form
{

	/**
	* Optional form object name as array
	* [code]HTML\Form::$key_add = 'a';[/code]
	* i.e. [code]<input name="foo[a]"...[/code]
	*/
	public static $key_add;

	/**
	* [code]<input class=""[/code]
	*/
	public static $css_name = null;

	/**
	* [code]<input readonly[/code]
	*/
	public static $readonly = false;

	/**
	* Additional parameters or inline scripts like css or js.
	* [code]HTML\Form::$tag_params = 'id=foo';[/code]
	* i.e. [code]<input id="foo"...[/code]
	* [code]HTML\Form::$tag_params = array('id'=>'foo');[/code]
	* i.e. [code]<input id="foo"...[/code]
	*/
	public static $tag_params = false;

	/**
	* Sets form fields rules
	*/
	public static function AddField( $name )
	{

		/**
		* Debug purposes
		*/
		//print_r( Libs\Form::$parameters[$name] ); exit;

		/**
		* For array types (multiple)
		*/
        if( is_array( Libs\Form::$parameters[$name]['type'] ) )
        {

            foreach( Libs\Form::$parameters[$name]['type'] as $k => $type )
            {
                $arr['type']        = $type;
                $arr['size']        = Libs\Form::$parameters[$name]['size'][$k];
                $arr['MaxLength']   = Libs\Form::$parameters[$name]['MaxLength'][$k];
                $arr['MinLength']   = Libs\Form::$parameters[$name]['MinLength'][$k];
                $arr['value']       = Libs\Form::$parameters[$name]['value'][$k];
                $arr['default']     = Libs\Form::$parameters[$name]['default'][$k];
                $arr['options']     = Libs\Form::$parameters[$name]['options'][$k];
                $arr['validation']  = Libs\Form::$parameters[$name]['validation'];
                $rs[] = self::InputType( $name . '[' . Libs\Form::$parameters[$name]['names'][$k] . ']', $arr );
				unset($arr);
            }

        }else{
			/**
			* Single type
			*/
            $rs = self::InputType( $name, Libs\Form::$parameters[$name] );
        }

		/**
		* Reset class name property and readonly attribute
		*/
		self::$css_name   = null;
		self::$readonly   = false;
		self::$tag_params = false;

		return $rs;

	}

	public static function InputType( $name, $property )
    {

		/**
		* Debug purposes
		*/
		//print_r( Libs\Form::$parameters[$name] ); exit;

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

	public static function tag_params_add()
	{
		$tag = '';

		if( self::$tag_params )
		{
			if( is_array( self::$tag_params ) )
			{
				foreach( self::$tag_params as $k => $v )
				{
					$tag .= ' ' . $k . '="' . $v . '"';
				}
			}else{
				$tag = ' ' . self::$tag_params;
			}
		}

		return $tag;
	}

	public static function InputText( $name, $property )
    {
		$rs  = '<input type="text" ' . self::tag_params_add() . ' name="' . $name;

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
			* value is array
			*/
			throw new \Exception('Value as array not implemented');
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

		$rs .= '>';

		return $rs;
	}

	public static function InputHidden( $name, $property )
	{

		$rs  = '<input type="hidden" ' . self::tag_params_add() . ' name="' . $name;

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
			* value is array
			*/
			throw new \Exception('Value as array not implemented');

		}

		$rs .= '">';

		return $rs;

	}

}