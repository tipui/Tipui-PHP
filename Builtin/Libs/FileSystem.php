<?php

/**
* @class  FileSystem
* @file   FileSystem.php
* @brief  File System functions.
* @date   2013-09-08 23:45:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs;

/**
* Files and directories manipulation classes.
*/
class FileSystem
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new FileSystem;
	* $c -> FileExists( 'file/path' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'FileSystem', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]FileSystem::WriteFile( 'file/path', 'content' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'FileSystem', $name, $arguments );
    }

}