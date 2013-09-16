<?php

/**
* @class  Del
* @file   Del.php
* @brief  Del Session functions.
* @date   2013-09-16 15:24:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs\Session;

class Del
{

	/**
	* Delete Session
	*/
	public function Exec( $key = false )
	{

        if( $key )
        {

			/**
			* Unset array index.
			*/
            if( isset( $_SESSION[$key] ) )
            {
                unset( $_SESSION[$key] );
            }

        }else{

			/**
			* Unset entire array.
			*/
            foreach( $_SESSION as $k => $v )
            {
                unset( $_SESSION[$k] );
            }

        }

        return null;

    }

}