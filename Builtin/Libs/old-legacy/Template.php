<?php
/** Template Engine Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2011-01-18 13:49:00 - Daniel Omine
 *
 *   Methods
        Compile
*/

class Template
{
 
    public $lang   = false;                 // engine array name
    public $tag    = false;                 // engine array name
    public $output = false;                 // print, read
    private $path;

    function Compile( $data, $dir = false, $file = false ) 
    {
		( !$this -> lang ) ? $this -> lang = LANG : '';

        if( !$this -> output )
        {
            $this -> output = TEMPLATE_OUTPUT;
        }

        if( !$this -> tag )
        {
            $this -> tag = TEMPLATE_TAG;
        }

        $k   = $this->tag;
        $$k  = $data;
        unset( $data );
        unset( $k );

        $this -> path = TEMPLATE_DIR;
        if( $dir )
        {
            $this -> path .= $dir;
        }


        ini_set( 'include_path', $this -> path . PS . ini_get( 'include_path' ) . PS . TEMPLATE_DIR . $this -> lang . DS );

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

        if( $this -> output == 'print' )
        {
            echo $__r;
            unset( $__r );
            return null;
        }
        $this -> output = 'print';
        return $__r;
    
    }


} // end template class

?>