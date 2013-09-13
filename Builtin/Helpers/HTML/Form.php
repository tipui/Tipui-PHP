<?php

/**
* @class  Form
* @file   Form.php
* @brief  HTML Form Helper functions.
* @date   2013-09-12 03:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-14 02:56:00
*/

namespace Tipui\Builtin\Helpers\HTML;

use Tipui\Builtin\Libs as Libs;
//use Tipui\Builtin\Helpers\HTML as HTML;

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
                $rs[] = self::ElementAdd( $name . '[' . self::$parameter['names'][$k] . ']', $arr );
				unset($arr);
            }

        }else{
			/**
			* Single type
			*/
            $rs = self::ElementAdd( $name, self::$parameter );
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

	public static function ElementAdd( $name, $property )
    {

		$c = '\Tipui\Builtin\Helpers\HTML\Form\\' . ucfirst( $property['type'] );
		return $c::Add( $name, $property );

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
}