<?php

/**
* @class  AddElement
* @file   AddElement.php
* @brief  AddElement HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 18:30:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

use Tipui\Builtin\Libs\Form as Form;
use Tipui\Builtin\Libs\DataRules as DataRules;

/**
* [review] Include the MAX_FILE_SIZE parameter
*/
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
		self::$parameter = Form::GetElement( $name );

		/**
		* For array types (multiple)
		*/
        if( is_array( self::$parameter[DataRules::TYPE] ) )
        {
			// [review]
			/*
            foreach( self::$parameter['type'] as $k => $type )
            {
                $arr['type']        = $type;
                $arr['size']        = self::$parameter['size'][$k];
                $arr['max_length']  = self::$parameter['max_length'][$k];
                $arr['min_length']  = self::$parameter['min_length'][$k];
                $arr['value']       = self::$parameter['value'][$k];
                $arr['default']     = self::$parameter['default'][$k];
                $arr['options']     = self::$parameter['options'][$k];
                $arr['validation']  = self::$parameter['validation'];
                $rs[] = self::AddEntitie( $name . '[' . self::$parameter['names'][$k] . ']', $arr );
				unset($arr);
            }
			*/

            foreach( self::$parameter[DataRules::TYPE] as $k => $v )
            {
				foreach( self::$parameter as $k2 => $v2 )
				{
					$arr[$k2] = $v2;
					$rs[] = self::AddEntitie( $name . '[' . self::$parameter[DataRules::NAME][$k] . ']', $arr );
					unset($arr);
				}
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

		$c = new \stdClass();
		$c -> html  = $rs;
		if( isset( self::$parameter[DataRules::OPTIONS] ) )
		{
			$c -> {DataRules::OPTIONS}  = self::$parameter[DataRules::OPTIONS];
		}
		$c -> error = isset( self::$parameter[DataRules::ERROR] ) ? self::$parameter[DataRules::ERROR] : false;

		return $c;
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