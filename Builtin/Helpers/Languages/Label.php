<?php

/**
* @class  Label
* @file   Label.php
* @brief  Label Helper/Languages functions.
* @date   2014-02-28 00:37:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-02 04:38:00
*/

namespace Tipui\Builtin\Helpers\Languages;

class Label extends \Tipui\Builtin\Helpers\Languages
{

	/**
	* Returns the label for current language
	*
	* \Tipui\Builtin\Helpers\Languages::Label( 'label_index' );[/code]
	*/
	public function Exec( $idx = null  )
	{

		/**
		* Setting the property [code]$lang_code[/code], if empty.
		*/
		if( empty( self::$lang_code ) )
		{
			self::Lang();
		}

		$bt = debug_backtrace();

		/**
		* Debug purposes
		*/
		/*
		if( $idx == 'fff' )
		{
			//print_r( $bt[3] ); exit;
			//return $bt[3]['file'];
			//return self::$base_path;
			//return self::$file_path;
		}
		*/

		/**
		* Necessary when the base is changed.
		* ie: Languages::Base( dirname( dirname( __FILE__ ) ) )->Label('foo')
		*/
		if( !empty( self::$base_path ) )
		{

			$idx_base = self::$base_path;

		}else{

			$idx_base = $bt[3]['file'];

			/**
			* If file name without name extension colides with existing folder, means that this file owns a folder.
			* The translation file is inside this folder.
			*/
			if( $file_extension = pathinfo( $idx_base, PATHINFO_EXTENSION ) )
			{
				$idx_base = substr( $idx_base, 0, -( strlen( $file_extension ) + 1 ) );
			}

			if( !is_dir( $idx_base ) )
			{
				$idx_base = dirname( $idx_base );
			}

		}

		/**
		* Loading the labels
		*/
		if( !isset( self::$labels[self::$lang_code][$idx_base] ) )
		{

			if( empty( self::$base_path ) )
			{
				/**
				* The method [code]self::Base()[/code] assigns the [code]self::$file_path[/code] property according to [code]self::$base_path[/code] value.
				*/
				self::Base( $idx_base );
			}

			/**
			* Loading the array of labels
			*/
			self::$labels[self::$lang_code][$idx_base] = array( 'source' => self::$file_path, 'data' => require_once( self::$file_path ) );

			/**
			* Must set to null to avoid conflict when loading labels from different paths in the same script.
			*/
			self::$base_path = null;

		}

		/**
		* Debug purposes
		*/
		/*
		if( $idx == 'fff' )
		{
			print_r( self::$labels ); exit;
			return $idx_base;
		}
		*/

		return ( !empty( $idx ) && isset( self::$labels[self::$lang_code][$idx_base]['data'][$idx] ) ) ? self::$labels[self::$lang_code][$idx_base]['data'][$idx] : '[NULL]';

	}

}