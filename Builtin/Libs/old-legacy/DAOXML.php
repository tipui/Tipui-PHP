<?php
/** DAOXML Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-09-30 12:36:00 - Daniel Omine
 *
 *   Methods
        LoadString
*/

class DAOXML
{

    public $Result;
    public $Error;
	
	private function Reset()
	{
        $this -> Error  = false;
        $this -> Result = false;
	}

    function LoadFile( $path )
    {

		$this -> Reset();
	
		//echo $path; exit;
		if( @file_exists( $path ) )
		{
			if( !$this -> Result = @file_get_contents( $path ) )
			{
				$this -> Error  = $php_errormsg;
			}
		}else{
			$this -> Error  = 'file_exists';
		}
		
		return null;
	}

    function LoadString( $data = false )
    {

		$this -> Reset();

        if( $data )
        {
            //echo $data; exit;
            if( !$this -> Result = simplexml_load_string( stripslashes( $data ) ) )
            {
                //echo $php_errormsg; exit;
                $this -> Error = $php_errormsg; // 'internal error: simplexml_load_string';
                //echo time(); exit;    
            }else{

                if( !is_object( $this -> Result ) ){
                    $this -> Error = 2; // 'xml is not an object';
                }
            
            }
            
        }else{
            $this -> Error = 1; // 'parameter not set';
        }
        
        return null;

    }



} // end DAOXML class
 

 
?>