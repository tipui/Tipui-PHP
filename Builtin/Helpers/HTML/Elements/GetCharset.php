<?php

/**
* @class  GetCharset
* @file   GetCharset.php
* @brief  GetCharset HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

use Tipui\Builtin\Libs as Libs;

class GetCharset extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Gets the base folder (public_folder)
	* [review] $bootstrap redundant usage
	*/
	public function Exec( )
	{
		/**
		* Retrieves the internal backtrace.
		*/
		//$trace = array_slice( debug_backtrace(), -2, 1 );
		//print_r( $trace ); exit;
		//$model = substr( $trace[0]['args'][0]['ClassName'], 7 );
		//echo $model; exit;
		
		// [review:high] create new method to stores the Model customs override settings
		/*
		$c      = new \Tipui\Core;
		$module = $c -> GetMethodDataCache( 'Routing' );
		print_r( $module ); exit;
		*/

		/**
		* Get environment modules settings
		*/
		$mode = \Tipui\Core::GetConf() -> MODULES -> METHODS_CACHE_STORAGE_MODE;
		//echo $mode; exit;

		/**
		* Intantiates Cache library and retrieves the data.
		*/
		$c  = new Libs\Cache;
		$rs = $c -> Get( 
			array( $mode  => array(
					'key' => \Tipui\Core::MODEL_CACHE_SESSION_NAME
				)
			)
		);

		/**
		* Debug purposes
		*/
		//print_r( $rs ); exit;

		/**
		* note: If key is invalid, returns stdClass Object with property "invalid_key".
		*/
		if( !isset( $rs['Template']['charset'] ) )
		{
			/**
			* If array index $rs['Template']['charset'] not exists, then, returns the BOOTSTRAP default charset.
			*/
			return \Tipui\Core::GetConf() -> BOOTSTRAP -> CHARSET;
		}else{
			return $rs['Template']['charset'];
		}
	}

}