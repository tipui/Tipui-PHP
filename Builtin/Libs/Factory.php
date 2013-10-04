<?php

/**
* @class  Factory
* @file   Factory.php
* @brief  Factory functions.
* @date   2013-07-11 03:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-10-04 13:57:00
*/

namespace Tipui\Builtin\Libs;

/**
* Creates libraries classes instances
*/
class Factory
{

	/**
	* Include and returns "abstract" classes
	*/
    public static function Exec( $library, $name, $arguments )
    {

		/**
		* Retrieves the internal backtrace.
		*/
		//$trace = debug_backtrace();

		/**
		* Debug purposes
		*/
		//print_r( $trace ); exit;
		//echo __NAMESPACE__; exit;

		/**
		* Debug purposes
		*/
		//echo $name . ': ' . implode(', ', $arguments); exit;
		//echo get_class(); exit;

		// [deprecated] 2013-09-30 03:51
		//require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $library . DIRECTORY_SEPARATOR . $name . TIPUI_CORE_ENV_FILE_EXTENSION );

		/**
		* Executes statically
		*/
		//return call_user_func_array( array( __NAMESPACE__ . '\\' . $library . '\\' . $name, 'Exec' ), $arguments );

		/**
		* Executes as instance
		* @param $library
		* If $library not have backslash (\), assumes the builtin path (note that if override file exists, then, will assumes override)
		* If $library have backslash (\), assumes the entire name as full path (namespace) of called library.
		* This is usefull for cases when library not exists in builtin or override folders but exists in the app folder.
		*/
		$reflect  = new \ReflectionClass( ( strpos( $library, '\\' ) ? $library : __NAMESPACE__ . '\\' . $library ) . '\\' . $name );
		$instance = $reflect->newInstance();

		/**
		* Clear variables
		*/
		unset( $reflect, $name );

		/**
		* Executes the instance and call method Exec()
		*/
		return call_user_func_array( array( $instance, 'Exec' ), $arguments );
		//var_dump( $rs ); exit;

	}

}