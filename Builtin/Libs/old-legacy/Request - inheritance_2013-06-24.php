<?php

/**
* @class  Request
* @file   Request.php
* @brief  URL and CLI parameters abstract functions.
* @date   2013-03-03 22:16:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-06-21 20:46:00
*/

namespace Tipui\Builtin\Libs;

class Request extends \Tipui\FW
{

    public  $method     = false;
    public  $parameter  = false;
    private $mthd;
    public  $modRewrite = true;
    public  $uri       = false;
    static  $folderBase = '';

    function Extract( )
    {

        if( isset( $_SERVER['REQUEST_METHOD'] ) )
        {

            if( $this->method != 'FILES' )
            {
                $this->mthd = $this->Method();
    
                if( $this->method and strtoupper( $this->method ) != $this->mthd )
                {
                    return false;
                }
                if( !$this->method ){
                $this->method = $this->mthd;
                }
            }

            $this->mthd = $GLOBALS['_' . $this->method];
            
            if( is_array( $this->mthd ) and count( $this->mthd ) > 0 )
            {

				if( get_magic_quotes_gpc() )
				{
					$this->mthd = array_map('stripslashes', $this->mthd);
				}

                if( $this->parameter )
                {

                    if( isset( $this->mthd[$this->parameter] ) )
                    {
                        return $this->mthd[$this->parameter];
                    }

                }else{

                    return $this->mthd;

                }

            }

        }

        return false;

    }

	function Method()
	{
		$r = strtoupper( $_SERVER['REQUEST_METHOD'] );
		if( in_array( $r, array( 'GET', 'POST', 'FILES' ) ) )
		{
		return $r;
		}
		return 'GET';
	}
	
    function ExtractURI()
    {
	
		if( $this -> IsCliMode() )
		{
			$this -> modRewrite = false;

			$this -> uri = $this -> ExtractShellURI();
			if( !isset( $this -> uri['c'] ) )
			{
				$this -> uri['c'] = false;
			}
			if( !isset( $this -> uri['m'] ) )
			{
				$this -> uri['m'] = false;
			}
			if( !isset( $this -> uri['p'] ) )
			{
				$this -> uri['p'] = false;
			}else{
				// (must be urlencoded on parameters)
				/*
				// special chars:
				[space] -> %20
				&       -> %26
				*/
				$this -> uri['p'] = urldecode( $this -> uri['p'] );
				$arr = explode( '&', $this -> uri['p'] );
				if( is_array( $arr ) )
				{
					//print_r( $arr ); exit;
					$parameters = false;
					foreach( $arr as $k => $v )
					{
						if( strpos( $v, '=' ) )
						{
							$val = explode( '=', $v );
							//print_r( $val ); exit;
							if( is_array( $val ) )
							{
								if( !isset( $val[0] ) )
								{
									echo '[ERR02:missing index 0]' . __FILE__; exit;
								}
								if( !isset( $val[1] ) )
								{
									$val[1] = '';
								}else{
									$val[1] = trim( $val[1] );
								}
								$parameters[$val[0]] = $val[1];
								//print_r( $parameters ); exit;
							}else{
								//ignore
								//echo '[ERR02:must be array]' . __FILE__; exit;
							}
						}
					}
					if( $parameters )
					{
						$this -> uri['p'] = $parameters;
						unset( $parameters );
					}
				}else{
					$this -> uri['p'] = false;
					//echo '[ERR01:must be array]' . __FILE__; exit;
				}
				unset( $arr );
			}

			//print_r( $this -> uri ); exit;

			return null;
		}
	
		/*
		if(!isset($_SERVER['REQUEST_URI'])){
			if( SHELL_MODE ){
				echo 'Check if running in command line. It\'s not supported!';
				echo '[class Request]';
			}
			print_r($_SERVER); exit;
		}
		*/

        $this -> uri = explode( URL_PFS, $_SERVER['REQUEST_URI'] );
        array_shift( $this -> uri );
        if( !isset( $this -> uri[0] ) or trim( $this -> uri[0] ) == '' )
        {
            $this -> uri = false;
        }

        // adicionado devido a um bug nome_modulo::$modRewrite undefined...
        $this -> modRewrite = false;


        if( $this -> uri )
        {
			//echo __FILE__;
            //print_r( $this -> uri ); exit;
			$base = false;
			//echo URL_PFS . $this -> uri[0] . ' : ' . URL_HREF_BASE; exit;
			if( $this -> uri[0] == str_replace( URL_PFS, '', URL_HREF_BASE ) )
			{
				self::$folderBase = $this -> uri[0];
				if( isset( $this -> uri[1] ) and empty( $this -> uri[1] ) )
				{
					$base = true;
				}
				array_shift( $this -> uri );
			}

			//echo substr( $this -> uri[0], 0, 1 ); exit;

            if( $base or substr( $this -> uri[0], 0, 1 ) == PARAM_ARGUMENTOR )
            {
                $this -> modRewrite = false;
            }else{
                $this -> modRewrite = true;
            }
			//echo $this -> modRewrite; exit;

        }else{
            // home
            //echo time(); exit;
        }

        return null;
    }



    function URI( $n = false )
    {

        /*
            Sample:
            http://dev-335i/.6/25/Samples_Mysql_FetchArray
            http://dev-335i/?p=6&page=25
            extract URI('page=2') -> 25
            extract URI('seo=3') -> Samples_Mysql_FetchArray

            URI sempre utilizável somente em requisições GET, devido a possibilidade de uso do mod rewrite
        */

        $rs = false;
    
        self::ExtractURI();
//echo 55; print_r( $this -> uri ); exit;

        /*
        if( $this -> modRewrite ){
        print_r( $this -> uri );
        }
        exit;
        */



        if( $n )
        {

            $arr = explode( '=', $n );

            // mod rewrite
			//echo $n . ' : ' . $this -> modRewrite; exit;
			//print_r( $this -> uri ); //exit;
			//echo 'base: ' . self::GetFolderBase();
			/*
			$fb = self::GetFolderBase();
			if( $fb and $fb == str_replace( URL_PFS, '', URL_HREF_BASE ) )
			{
				array_shift( $this -> uri );
			}
			*/

			//echo 55; print_r( $this -> uri ); exit;

            if( $this -> modRewrite and $this -> uri )
            {

                $n = ($arr[1]-1);
                if( isset( $arr[1] ) and isset( $this -> uri[$n] ) )
                {

                    //print_r( $arr );  //exit;
                    //quando pegar aqui, retornar o valor e o KeyName para atribuir ao formvalidation
                    $rs = $this -> uri[$n];
/*
//deprecated. 2010-05-20
                    FormValidation::$ExternalData[$arr[0]] = FormValidation::$form[$arr[0]];
                    FormValidation::$ExternalData[$arr[0]]['value'] = $rs;
*/
					//echo $rs; exit;
                }else{
				//print_r( $arr ); exit;
/*
//deprecated. 2010-05-20
					if( $this -> uri )
					{ 	
						//echo 2;
						//print_r( $arr ); exit;
						//print_r( $this -> uri ); exit;
						FormValidation::$ExternalData[$arr[0]] = FormValidation::$form[$arr[0]];
						FormValidation::$ExternalData[$arr[0]]['value'] = $rs;
					}
*/
				}

            }else{

                //if( !is_numeric( $arr[0] ) )
                //{

                    //echo Form::keyFieldValue(); exit;
                    if( Form::keyFieldValue() == Form::Steps_End )
                    {

                        FormValidation::$session -> Get( Form::$sindex );
                        if( FormValidation::$session -> exists )
                        {
                            //echo FormValidation::$session -> data[$arr[0]]; exit;
                            if( isset( FormValidation::$session -> data[$arr[0]] ) )
                            {
                                $rs = FormValidation::$session -> data[$arr[0]];
                                Form::End();
                            }
                        }

                    }else{
                        //echo time();
                        //print_r( $arr[0] ); //exit;

                        $rq = new Request;
						isset( FormValidation::$method ) ? $rq -> method = FormValidation::$method : $rq -> method  = false ;
                        $rq -> parameter = $arr[0];

						//echo $rq -> parameter;
                        $rs = $rq -> Extract();

/*
	if( $rq -> parameter == 'page' )
	{
		//echo $rq -> parameter;
		print_r( $rs ); exit;
	}else{
		//echo 'a' . time(); exit;
	}

	//echo $rs; exit;
*/

/*
//deprecated. 2010-05-20
                        if( $this -> uri )
                        {
							//print_r( $this -> uri ); exit;
                            FormValidation::$ExternalData[$arr[0]] = FormValidation::$form[$arr[0]];
                            FormValidation::$ExternalData[$arr[0]]['value'] = $rs;

                        }
*/


                    }
    
                //}

            }

        }else{
            $rs = false;
        }

        return $rs;
    }

	function ExtractShellURI()
	{
		/*
		c -> control
		m -> module
		p -> parameters
		*/
		return getopt( 'c:m:p:', array( 
			'required:',     // Required value 
			'optional::',    // Optional value 
			'option',        // No value 
			'opt',           // No value 
		) );

	}
	
	static public function GetFolderBase(){
		return self::$folderBase;
	}
}


?>