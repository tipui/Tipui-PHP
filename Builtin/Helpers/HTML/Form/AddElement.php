<?php

/**
* @class  AddElement
* @file   AddElement.php
* @brief  AddElement HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-07 21:06:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

use Tipui\Builtin\Libs as Libs;

class AddElement extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add new input element
	*/
	public function Exec( $name )
	{
		/**
		* Debug purposes
		*/
		self::$parameter = Libs\Form::GetElement( $name );

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
                $rs[] = self::AddEntitie( $name . '[' . self::$parameter['names'][$k] . ']', $arr );
				unset($arr);
            }

        }else{
			/**
			* Single type
			*/
            $rs = self::AddEntitie( $name, self::$parameter );
        }

		/**
		* Reset class name property and readonly attribute
		*/
		self::SetCSSName( null );
		self::SetReadOnly( false );
		self::SetTagParams( false );
		self::SetNameAsArray( null );

		return $rs;
	}

	/**
	* Returns element entitie.
	*/
	protected function AddEntitie( $name, $property )
	{
		$c = '\Tipui\Builtin\Helpers\HTML\Form\Elements\\' . ucfirst( $property['type'] );
		return $c::Add( $name, $property );
	}
}