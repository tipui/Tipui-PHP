<?php

/**
* @class  Destroy
* @file   Destroy.php
* @brief  Destroy Session functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Session;

class Destroy
{

	/**
	* Destroy Session and it session_id()
	*/
	public function Exec()
	{
        if( session_id() != '' )
        {
            session_destroy();
        }
    }

}