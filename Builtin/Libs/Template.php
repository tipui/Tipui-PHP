<?php

/**
* @class  Strings
* @file   Strings.php
* @brief  Strings functions.
* @date   2011-01-18 13:49:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-07-01 06:01:00
*/

namespace Tipui\Builtin\Libs;

class Template
{
	/**
	* Tag for template files
	*/
    private $tag;

	/**
	* Type of output (hold or print)
	*/
    private $output;

	/**
	* Base of path
	*/
    private $base_dir;

	/**
	* Full path of template file
	*/
    private $path;

	/**
	* Init settings
	*/
	public function __construct( $base_dir = false, $tag = 'T', $output = 'print' )
	{
		$this -> tag      = $tag;
		$this -> output   = $output;
		$this -> base_dir = $base_dir;

		return null;
	}

	/**
	* Renderer
	*/
    function Compile( $data, $dir = false, $file = false ) 
    {

        $k   = $this->tag;
        $$k  = $data;
        unset( $data, $k );

        $this -> path = $this -> base_dir;
        if( $dir )
        {
            $this -> path .= $dir;
        }

        ini_set( 'include_path', $this -> path . PATH_SEPARATOR . ini_get( 'include_path' ) . PATH_SEPARATOR . $this -> base_dir . DIRECTORY_SEPARATOR );

        //echo $this -> path . $file; exit;
        if( $fp = @fopen( $this -> path . $file, 'r' ) )
        {

            $src = '';
            while( !feof( $fp ) )
            {
                $src .= fread( $fp, 65536 );
				// this is essential for large files
				if( $this -> output == 'print' )
				{
					flush();
				}
            }  
            fclose( $fp ); 

            ob_start();

            eval( '?>' . $src );
            $__r = ob_get_contents();
			//ob_end_flush();
            ob_end_clean(); 
            unset( $src );

			if( $this -> output == 'print' )
			{
				flush();
			}

        }else{
            $__r = '[' . get_class( $this ) . '], ERROR: FILE NOT EXISTS -> ' . $this -> path . $file;
        }
		unset( $fp );

        if( $this -> output == 'print' )
        {
            echo $__r;
            unset( $__r );
            return null;
        }
        $this -> output = 'print';
        return $__r;

    }


}

?>