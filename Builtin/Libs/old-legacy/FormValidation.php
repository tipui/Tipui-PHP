<?php
/** FormValidation Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-03-04 00:53:00 - Daniel Omine
 *
 *   Methods
        LoadMsg
        StrLen
        Check
		SetParameterErrorMsg
        AllFields
        Reset
		Transport
*/

class FormValidation
{


    static $action           = FORM_ACTION;
    static $form;
	static $key              = false;
    static $method;
    static $keyField;
    static $Result;
    static $compare          = false;
    static $data;
    static $ExternalData     = false;
    static $Parameters       = false;
    static $rules;
    static $ErrorMsg         = false;
	static $ErrorMsgMulti;
    static $Error;
	static $MultiError       = false;
    static $Results;
	static $ValueOriginal    = false;
    static $session          = false;
    static $steps            = true;
    static $SessionTimeLimit = false;
	static $request          = false;
    static $multiKeys        = false;
    static $tree             = false;

	const SESS_NAME_Transport = 'FormValidation_Transport';


    function LoadMsg( $code = false )
    {

        //require_once( TEMPLATE_DIR . LANG . DS . 'lib' . DS . 'FormValidation' . FILE_EXTENSION );
        self::$ErrorMsg = FormValidationLabels::WarnMessages( $code );

    }
    function LoadMsgMulti( $code = false )
    {

        //require_once( TEMPLATE_DIR . LANG . DS . 'lib' . DS . 'FormValidation' . FILE_EXTENSION );
        self::$ErrorMsgMulti = FormValidationLabels::WarnMessages( $code );

    }



    function StrLen( $v = false )
    {

		$v = !$v ? self::$data : $v;

        if( is_string( $v ) )
        {

			switch( TRUE )
			{
				case (self::$rules['validation'] == 'date' or self::$rules['validation'] == 'datetime'):
					//print_r(self::$rules); exit;
					self::$data = DateAndTime::Format( self::$data, 'datetime_string_to_array' );
					/*
					if( strpos( self::$data, '-' ) )
					{
						self::$data = explode( '-', self::$data );
					}
					*/
					//print_r(self::$data); exit;
					return null;
				break;
			}

			if( isset( self::$rules['ExactValue'] ) )
			{
				/*
				if( in_array( $v, self::$rules['ExactValue'] ) )
				{
					echo 11;
				}else{
					echo 22;
				}
				*/
				//exit;
				if( ( is_string( self::$rules['ExactValue'] ) and $v != self::$rules['ExactValue'] ) or ( is_array( self::$rules['ExactValue'] ) and !in_array( $v, self::$rules['ExactValue'] ) ) )
				{
					//echo time(); exit;
					self::LoadMsg( 'ExactValue' );
					if( self::$ErrorMsg )
					{
						return null;
					}
				}
			}else{
		
				$size = Strings::StrLen( $v );
			
				if( isset( self::$rules['ExactLength'] ) )
				{
					if( $size != self::$rules['ExactLength'] )
					{
						self::LoadMsg( 'ExactLength' );
					}
				
				}else{

					if( $size > self::$rules['MaxLength'] )
					{
			
						self::LoadMsg( 'MaxLength' ); 
			
					}elseif( $size < self::$rules['MinLength'] )
					{
			
						self::LoadMsg( 'MinLength' );
			
					}
				}
			}

        }else{

            // must be string to do this validation
			//echo time(); exit;

        }

        return null;

    }

	function ArrayBaseTree( $array, $index=0 )
	{

		// for debug purposes
		if( $index == 0 )
		{
			//echo 'A' . PHP_EOL;
			//self::$tree = false;
			//echo implode( '/', self::$tree ); exit;
		}

		if( is_array( $array ) )
		{
			$index++;
			foreach( $array as $k => $v )
			{
				self::$tree[$index]  = '';
				//self::$tree[$index] .= '[' . $index . ' ' . count( self::$tree ) . ']';  // for debug purposes
				self::$tree[$index] .= $k;
				if( !is_array( $v ) )
				{
					$id = ( $index + 1 );
					self::$tree[$id]  = '';
					//self::$tree[$id] .= '[' . $index . ' ' . count( self::$tree ) . ']';  // for debug purposes

					self::$tree[$id] .= $v; // may use validation filter here

					$j = count( self::$tree );
					if( $id < $j )
					{
						while( $j > $id )
						{
							unset( self::$tree[$j] );
							$j--;
						}
					}
				}
				self::ArrayBaseTree( $v, $index );
			}
			//echo $index . '<br>'; // for debug purposes
		}else{

			 $val = array_pop( self::$tree );
			 self::$multiKeys[ implode( '/', self::$tree ) ] = $val;

		}
	}

    function Check( ){


        // return "this is required", when empty and is required
		self::$ErrorMsg   = false;
		//self::$MultiError = false;

        if( !isset( self::$rules['multiple'] ) )
        {
            //if( !is_array( self::$data ) and self::$data == '' )
            if( self::$data == '' )
            {
				switch( self::$rules['validation'] )
				{
					default:
						self::LoadMsg( 'required' );
					break;
					case 'options':
						self::LoadMsg( 'options' );
					break;
				}
                
                return null;
            }
 
			// check exact value, min length and max length
			self::StrLen();
			if( self::$ErrorMsg )
			{
				return null;
			}

		}else{
			if( isset( self::$data ) and is_array( self::$data ) )
			{
			

				self::ArrayBaseTree( self::$data );
				//print_r( self::$multiKeys ); exit;
			
				if( self::$multiKeys and is_array( self::$multiKeys ) )
				{
					foreach( self::$multiKeys as $k => $v )
					{
						//echo $k . ': ' . $v . '<br>';
						
						//$val  = $v;
						switch( self::$rules['validation'] )
						{
							case 'number':
							case 'number_float':
								$v = Strings::NumbersOnly( $v, ( ( self::$rules['validation'] == 'number_float' ) ? true : false ) );
							break;
							default:
								if( isset( self::$rules['pre-filter'] ) )
								{
									$v = self::Filter( self::$rules, $v );
								}
							break;
						}

						if( self::$rules['required'] or ( $v and $v != '' ) or ( gettype( $v ) == 'string' and $v == '0' ) )
						{
							if( $v == '' )
							{
								//echo $k . ': ' . $v . '<br>';
								self::LoadMsg(); 
								self::LoadMsgMulti();

							}else{
								//echo $k . ': ' . $v . '<br>';
								// check exact value, min length and max length
								self::StrLen( $v );
								self::$ErrorMsgMulti = self::$ErrorMsg;
								//echo $k . ': ' . $v . '<hr>';
							}
							//echo self::$ErrorMsgMulti; exit;
							//echo self::$key . ' - ';
							//echo $k . ': ' . self::$ErrorMsgMulti . '<hr>';
							if( self::$ErrorMsgMulti )
							{						
								//echo time(); exit;
								self::$MultiError[self::$key][$k] = self::$ErrorMsgMulti;
								self::$ErrorMsgMulti = false;
								self::$ErrorMsg      = false;
							}
						
						}
						
						

					}
					
					if( self::$MultiError )
					{
						self::$ErrorMsg = 'multi error';
					}
				//exit;
				}
			}

		}


        switch( self::$rules['validation'] )
        {


            case 'number':
            case 'number_float':

				//echo time(); exit;
				/*
				if( self::$rules['MaxLength'] > 1 )
				{
				print_r( self::$rules ); exit;
				}
				*/
				
                if( isset( self::$rules['multiple'] ) )
                {
					//echo time(); exit;
					// 2010-09-26

				}else{

			
					//self::$data = (string)self::$data;
					self::$data = Strings::NumbersOnly( self::$data, ( ( self::$rules['validation'] == 'number_float' ) ? true : false ) );

					//self::$form[$k]['value']
					if( self::$data == '' )
					{
						self::LoadMsg(); 
					}

				
				}

            break;



            case 'email':

                if( !Strings::ValidMailAddress( self::$data ) )
                {
                    self::LoadMsg();
                }

            break;



            case 'date':
            case 'datetime':
			
				//self::$data = '20100917';
			
				if( !is_array( self::$data ) )
				{

					if( strpos( self::$data, '-' ) )
					{

						self::$data = DateAndTime::Format( self::$data, 'datetime_string_to_array' );
						unset( $d );

					}else{
						self::$data = self::Filter( array( 'pre-filter' => array( array( 'Strings', 'NumbersOnly' ) ) ), self::$data );
						self::LoadMsg();
					}
				}

				//echo __FILE__ . ':' . __LINE__ . '<br />' . PHP_EOL . print_r(self::$data); exit;
				
				if( !self::$ErrorMsg )
				{
					if( is_array( self::$data ) )
					{
					
						// filter array
						foreach( self::$data as $k => $v )
						{
							self::$data[$k] = Strings::NumbersOnly( $v );
						}
					
					}else{
						self::LoadMsg();
					}
				}

				if( !self::$ErrorMsg )
				{
					DateAndTime::CheckDate( self::$data );
					//print_r( DateAndTime::$rs ); exit;
					if( DateAndTime::$rs['error'] )
					{
						//echo time(); exit;
						self::LoadMsg();
						//echo self::$ErrorMsg; exit;
					}
				}

            break;


            case 'options':
			
				if( isset( self::$rules['pre-filter'] ) )
				{
					$pf    = self::$rules;
					if( isset( self::$data ) and is_array( self::$data ) )
					{
						foreach( self::$data as $k => $v )
						{
							//$pf[1] = isset( $pf[1] ) ? Strings::ArrayRpush( $pf[1], $v ) : array( $v );
							//self::$data[$k] = Functions::Call( $pf );
							
							//echo 'a' . print_r( $pf ); exit;
							self::$data[$k] = self::Filter( $pf, $v );
							
						}
					}else{
						//$pf[1] = isset( $pf[1] ) ? Strings::ArrayRpush( $pf[1], self::$data ) : array( self::$data );
						//self::$data = Functions::Call( $pf );
						//print_r( $pf ); exit;
						isset( self::$data )? self::$data = self::Filter( $pf, self::$data ) : '';
					}
				}
				
                if( isset( self::$rules['multiple'] ) )
                {

					if( is_array( self::$data ) )
					{
						$s = count( self::$data );
					
						//print_r( self::$form[self::$key] ); exit;
						//print_r( $_POST[self::$key] ); exit;
						//print_r( self::$form[self::$key]['options'] ); exit;
						if( $s < self::$rules['SelectMin'] and self::$rules['SelectMin'] > 0 )
						{
							self::LoadMsg( 'SelectMin' ); // it's required select at last [X] itens 
						}
						if( $s < self::$rules['SelectMax'] and self::$rules['SelectMax'] > 0 )
						{
							self::LoadMsg( 'SelectMax' ); // Is allowed to choose up to [X] options 
						}	
					}else{
						self::LoadMsg( 'SelectMin' ); // it's required select at last [X] itens
					}

                }else{
					if( isset( self::$rules['after_post_options'] ) )
					{	
						//$v['pre-filter'][1] = isset( $v['pre-filter'][1] ) ? Strings::ArrayRpush( $v['pre-filter'][1], $str ) : array( $str );
		
						$cal[0] = self::$rules['after_post_options'][0];
						$cal[1] = array(self::$Results[self::$rules['after_post_options'][1][0]]);
						self::$rules['options'] = Functions::Call($cal);
						//print_r( $cal ); exit;
						//print_r( self::$rules['options'] ); exit;
						//echo self::$data; exit;
						//47KDd9cBo2b
						//echo self::$rules['options'][ self::$data ]; exit;
					}
					if( !isset( self::$rules['options'][ self::$data ] ) )
					{
						self::LoadMsg( 'invalid-option' );
					}
						

                }


            break;


            case 'password':
 
                if( !self::$compare )
                {

                    self::$compare = $data;

                }else{

                    if( self::$data != self::$compare )
                    {
                        self::LoadMsg();
                    }
                    self::$compare = false;

                }

            break;



            case 'upload':

				//print_r( self::$rules ); exit;
				//print_r( self::$data ); exit;
                //print_r( $_POST[self::$key] ); exit;

				// check the upload error code
				if( self::$data['error'] > 0 ){
					self::LoadMsg('upload-error-code');
				}else{

					// check content-type
					if( !in_array( self::$data['type'], self::$rules['content_types'] ) ){
						self::LoadMsg('upload-type');
					}
				
					if( !self::$ErrorMsg ){
						// check file size limits
						if( self::$rules['max_size'] < self::$data['size'] ){
							// maximum size limit was exceeded
							self::LoadMsg('upload-max-size');
						}else{
							if( self::$rules['min_size'] > self::$data['size'] ){
								// minimum size expected
								self::LoadMsg('upload-min-size');
							}
						}
					}
					if( !self::$ErrorMsg ){
						// for images only
						if( isset( self::$rules['exact_height'] ) or isset( self::$rules['exact_width'] ) or isset( self::$rules['max_width'] ) or isset( self::$rules['min_width'] ) or isset( self::$rules['max_height'] ) or isset( self::$rules['min_height'] ) ){
							$arr = getimagesize( self::$data['tmp_name'] );
							self::$data['width']  = $arr[0];
							self::$data['height'] = $arr[1];
							if( isset( self::$rules['exact_height'] ) and self::$rules['exact_height'] <> $arr[1] ){
								self::LoadMsg('upload-exact_height');
							}
							if( !self::$ErrorMsg and isset( self::$rules['exact_width'] ) and self::$rules['exact_width'] <> $arr[0] ){
								self::LoadMsg('upload-exact_width');
							}
							if( !self::$ErrorMsg and isset( self::$rules['min_width'] ) and self::$rules['min_width'] > $arr[0] ){
								self::LoadMsg('upload-min_width');
							}
							if( !self::$ErrorMsg and isset( self::$rules['max_width'] ) and self::$rules['max_width'] < $arr[0] ){
								self::LoadMsg('upload-max_width');
							}
							if( !self::$ErrorMsg and isset( self::$rules['min_height'] ) and self::$rules['min_height'] > $arr[1] ){
								self::LoadMsg('upload-min_height');
							}
							if( !self::$ErrorMsg and isset( self::$rules['max_height'] ) and self::$rules['max_height'] < $arr[1] ){
								self::LoadMsg('upload-max_height');
							}
							unset($arr);
						}
					}
				}

            break;
        }


        return null;


    }
	
	function Filter( $v, $str )
	{
	
		if( !isset( $v['pre-filter'] ) )
		{
			echo 123; exit;
			echo self::$key . PHP_EOL . __LINE__ . ' : ' . __FILE__;
			print_r( $v ); exit;
		}
	
		$v['pre-filter'][1] = isset( $v['pre-filter'][1] ) ? Strings::ArrayRpush( $v['pre-filter'][1], $str ) : array( $str );
		
		// debug purposes only
		//if( $v['validation'] == 'options' )
		//{
		//print_r( $v['pre-filter'] ); exit;
		//echo Functions::Call( $v['pre-filter'] ); exit;
		//}
		
		return Functions::Call( $v['pre-filter'] );
	}

    function Field( $parameter, $method = false )
    {
	
		if( $method )
		{
			$tmp          = self::$method;
			self::$method = $method;
			$method       = $tmp;
			unset( $tmp );
		}
	
		if( !isset( $this -> RQ ) )
		{
			$this -> RQ = new Request;
			$this -> RQ -> ExtractURI();
			//$p          = self::$form[$parameter];
		}
		//self::$key  = $parameter;

		if( $this -> RQ -> modRewrite )
		{
		
			//print_r( array_keys( self::$form ) ); exit;
			//echo array_search( self::$keyField, array_keys( self::$Parameters ) ); exit;
			//print_r( array_keys( array_keys( self::$Parameters ), $parameter ) ); exit;
			
			if( !self::$data = array_keys( array_keys( self::$form ), $parameter ) )
			{
				self::$data = false;
				//echo time() . 'a';
			}else{
			
				//echo $parameter; exit;
				//print_r( self::$request ); exit;
				//print_r( self::$data ); exit;
				//echo 'position: ' . ( self::$data[0] + MOD_COUNT ); //exit;
			
				// 2010.09.03
				// extract value of PARAM_NAME.
				// PARAM_NAME always on 0 position, then, must check if MOD_COUNT is over than 1 
				// if MOD_COUNT over 1, means that PARAM_NAME is splited into array
				if( self::$data[0] == 0 and MOD_COUNT > 1 )
				{
				
					$i = 0;
					//print_r( Controller::$mod ); exit; //ModFolder
					//print_r( $this -> RQ -> uri ); exit;
					if( $this-> RQ -> uri and Request::GetFolderBase() == $this-> RQ -> uri[0] )
					{
						array_shift( $this-> RQ -> uri );

						//echo Request::GetFolderBase() . ':' . __FILE__ . ':' . __LINE__ . '<br />';
						//print_r( $this->rq -> uri ); exit;
						
						//print_r( $this -> RQ -> uri ); //exit;
						$mod_alias = Controller::$mod['alias'];
						//$mod_alias_arr = explode( URL_PFS, $mod_alias );
						//print_r( $mod_alias_arr ); exit;
						//echo MOD_COUNT; exit;

						$module_name = '';
						foreach(  $this -> RQ -> uri as $mk => $mv )
						{
							// $mk >= MOD_COUNT prevents overloop hackz
							if( $module_name == $mod_alias or $mk >= MOD_COUNT )
							{
								break;
							}
							if( $mk > 0 )
							{
								$module_name .= URL_PFS;
							}
							$module_name .= $mv;
							//array_shift( $this-> RQ -> uri );
						}
						//echo $module_name; exit;
						//print_r( $this -> RQ -> uri ); //exit;

						$val[$i] = $module_name;

						$i = MOD_COUNT;
						$i2 = 1;
					}else{
						$i2 = 0;
					}
					$decrease = 1;
					//echo $i; exit;
					//echo $parameter; //exit;

					//if( $parameter == 'id' )
					//{
					//	echo __FILE__;
					//	print_r( $val ); exit;
					//}

					while( $i <= MOD_COUNT - $decrease )
					{
						$val[$i2] = $this -> RQ -> uri[$i];
						$i++;
						$i2++;
					}
					//print_r( $val ); exit;
					self::$data = implode( URL_PFS, $val ); //exit;
				}else{
					//echo MOD_COUNT; exit;
					//print_r( array_keys( array_keys( self::$form ), $parameter ) ); exit;
					//print_r( self::$data ); exit;
					self::$data = Request::URI( $parameter . '=' . ( self::$data[0] + MOD_COUNT ) );
					//echo 33; exit;
				}

				//self::$data = Request::URI( $parameter . '=' . ( self::$data[0] + MOD_COUNT ) );
				//echo self::$data; exit;
				//echo time(); exit;
			}

			//if( $parameter == 'id' ){
			//	echo 'modRewrite / ' . $parameter . ': ' . self::$data; exit;
			//}
			
		}else{
			//$this -> RQ -> parameter = self::$keyField;

			if( SHELL_MODE )
			{
				if( $parameter == PARAM_NAME )
				{
					self::$data = '';
					if( $this -> RQ -> uri['c'] )
					{
						self::$data .= $this -> RQ -> uri['c'];
					}
					if( $this -> RQ -> uri['m'] )
					{
						self::$data .= URL_PFS . $this -> RQ -> uri['m'];
					}
				}else if( isset( $this -> RQ -> uri['p'][$parameter] ) ){
					self::$data = $this -> RQ -> uri['p'][$parameter];
				}
			}else{
				$this -> RQ -> parameter = $parameter;
				self::$data = $this -> RQ -> Extract();
			}
			//echo $parameter . ' : ' . self::$data; exit;
		}
		//exit;

		//if( $parameter == 'id' ){
		//echo __FILE__ . ' : ' . self::$key . ':' . $parameter . ': ' . self::$data; exit;
		//}

		/*
		if( self::$key == 'page' )
		{
			echo self::$data; exit;
		}
		*/
		
		/*
		if( self::$key == 'page' )
		{
			echo gettype( self::$data ); exit;
		}
		*/
		

		$v = self::$form[$parameter];
		if( $v['required'] or ( self::$data and self::$data != '' ) or ( gettype( self::$data ) == 'string' and self::$data == '0' ) or ( $v['type'] == 'file' and isset( $_FILES[$parameter] ) and is_array( $_FILES[$parameter] ) and !empty($_FILES[$parameter]['name']) ) )
		{
			/*
			if( self::$key == 'page' )
			{
				echo gettype( self::$data ); exit;
			}
			*/

			if( $parameter == self::$keyField )
			{

				if( !Form::KeyFieldValid() )
				{
					FormValidation::LoadMsg( 'KeyField' );
				}

			}else{
			
				// for upload
				if( $v['type'] == 'file' )
				{
					//echo $_SERVER['REQUEST_METHOD']; exit;
					//print_r($_FILES); exit;
					//echo Form::$sindex; exit;
					/*
					// for debug
					Form::$sindex = get_class( $this );
					self::$session = new Session;
					self::$session -> init();
					self::$session -> Get( Form::$sindex );
					*/
					//echo time(); print_r(self::$session -> data[$parameter]); //exit;

					if( isset( $_FILES[$parameter] ) and is_array( $_FILES[$parameter] ) and !empty($_FILES[$parameter]['name']) )
					{
						//echo 123; print_r($_FILES[$parameter]); exit;
						self::$data = $_FILES[$parameter];
					}else{

						/*
						Form::$sindex = get_class( $this );
						self::$session = new Session;
						self::$session -> init();
						self::$session -> Get( Form::$sindex );

						//echo time(); print_r(self::$session -> data[$parameter]); exit;
						if(self::$session -> exists and isset( self::$session -> data[$parameter] ))
						{
							self::$data = self::$session -> data[$parameter];
						}else{
							// require and not exists. must return error message
							self::$data = false;
						}
						*/

						self::$data = false;

					}
					//exit;
				}
//exit;
				if( isset( $v['pre-filter'] ) )
				{
					/*
					if( self::$key == 'image' ){
						print_r($v); exit;
					}
					*/
					//echo time(); exit;
					self::$ValueOriginal[$parameter] = self::$data; // save original value. Usefull for logging and debug
					
					//print_r( $v['pre-filter'] ); exit;
					
					if( is_string( self::$data ) )
					{

						self::$data = self::Filter( $v, self::$data );

					}else{
						// for uploads
						if( $v['type'] == 'file' and is_array( self::$data ) and isset( self::$data['name'] ) )
						{
							// prevents any injection by file nomenclature
							self::$data['name'] = self::Filter( $v, self::$data['name'] );
							//echo time(); print_r(self::$data); exit;
						}
					}
				}

				self::$rules     = $v;
				//self::$ErrorMsg  = false;
				self::Check();
				


			}

			self::$form[$parameter]['value'] = self::$data;
			
			/*
			if( self::$key == 'date_from' )
			{
				//echo self::$data . ':' . gettype( self::$data ) . ':' . self::$form[$parameter]['value']; exit;
				//echo time(); exit;
				//echo self::$ErrorMsg; exit;
			}
			*/
			if( self::$ErrorMsg )
			{
				//if( self::$key == 'date_from' )
				//{
					//echo self::$data . ':' . gettype( self::$data ) . ':' . self::$form[$parameter]['value']; exit;
					//echo time(); exit;
					//echo $parameter . ': ' . self::$ErrorMsg; exit;
				//}
				//self::$Error[$parameter] = self::$ErrorMsg;
				self::SetParameterErrorMsg( $parameter );
			}

		}

		// used to store into session vars
		//settype( self::$form[$k]['value'], 'string' );
		self::$Results[$parameter] = self::$form[$parameter]['value'];
		//echo ( self::$Results[$parameter] ); exit;
		self::Reset();

		// 2010-07-26 prevents modified method.
		if( $method )
		{
			self::$method = $method;
		}
		
		return null;
	}

	function SetParameterErrorMsg( $parameter )
	{
		self::$Error[$parameter] = self::$ErrorMsg;
	}
	
    function AllFields()
    {

        // when don't need send data via post or get.
        if( !self::$form )
        {
            if( !self::$ExternalData )
            {
                return null;
            }
        }


		$this -> RQ = new Request;
		$this -> RQ -> ExtractURI();
		/*
		if( $this -> RQ -> modRewrite )
		{
			echo 'a';
		}else{
			echo 'b';
		}
		exit;
		*/
		//echo 'self::method: ' . self::$method; exit;
		//echo 'RQ->Method: ' . $this -> RQ -> Method(); exit;
		
        if( self::$ExternalData )
        {
            self::$Parameters = self::$ExternalData;

            //if( self::$ExternalData ){
            //print_r( self::$ExternalData ); exit;
            //}
			//echo 'a1';
        }else{
            self::$Parameters = self::$form;
			//echo 'b1';
        }
//exit;
//print_r( self::$Parameters ); exit;


		// check if 'act' parameter exist
		if( !self::$method or self::$method == $this -> RQ -> Method() )
		{

			if( $this -> RQ -> modRewrite )
			{
				//echo time(); //exit;
				//print_r( array_keys( self::$Parameters ) ); exit;
				//echo MOD_COUNT; exit;
				//echo array_search( self::$keyField, array_keys( self::$Parameters ) ); exit;
				//print_r( array_keys( array_keys( self::$Parameters ), self::$keyField ) ); exit;
				
				if( !self::$request = array_keys( array_keys( self::$Parameters ), self::$keyField ) )
				{
					//self::$request = false;
					//echo time() . 'a'; exit;
				}else{
					// if true, means that 'act' was found on the self::$form.
					// extract the position in array to pass as parameter of "Request::URI"
					//print_r( self::$request ); exit;
					//echo time(); exit;
					self::$request = Request::URI( self::$keyField . '=' . ( self::$request[0] + MOD_COUNT ) );
					//echo time();
					
					//print_r( self::$request ); exit;
				}

				//echo 'modRewrite / ' . self::$keyField . ': ' . self::$request; exit;
			}else{
				$this -> RQ -> parameter = self::$keyField;
				self::$request = $this -> RQ -> Extract();
				//echo self::$keyField . ': ' . self::$request; exit;
			}

		}
		//exit;
		//
		if( !self::$request )
        {
			if( !self::$keyField and !self::$steps )
			{
				//echo time() . 'b'; exit;
				self::$request   = Form::Steps_New;
				self::$keyField  = Form::KEY_FIELD_NAME;
				
				//if( !isset( self::$form[ self::$keyField ] ) )
				//{
					//echo time() . 'c'; exit;
				//	self::$Results[ self::$keyField ] = self::$request;
				//}
				
			}
		}
		//

        if( self::$request )
        {
			//echo time() . 'a: ' . __LINE__; exit;
		
		
			// parametro "act" é usado para identificar se está sendo postado dados
			// the special parameter "act" or defined by "$KeyField" is usefull to check if exist posted data
			
			// 2010-09-07 Daniel Omine
			//self::$form[ self::$keyField ]['value'] = self::$request;
			//print_r( self::$request ); exit;
			Form::keyFieldSet( self::$request );

			//echo self::$request; exit;
			//echo self::$form[ self::$keyField ]['value']; exit;
			//echo Form::keyFieldValue(); exit;


            // não entra na validação dos campos quando estiver com status 'back' ou 'confirm'.
            // quando estiver como 2 ou 3, obter os dados da variável de sessão
            if( Form::keyFieldValue() != Form::Steps_Back and Form::keyFieldValue() != Form::Steps_Confirm )
            {
				//print_r( self::$Parameters ); exit;
				$position = 0;
                foreach( self::$Parameters as $k => $v )
                {
					self::$key       = $k; // 2010-09-10 Daniel Omine
					//
					//deprecated. 2010-05-20
					/*
										if( !self::$ExternalData )
										{

											//$this -> RQ -> parameter = $k;
											//self::$data = $this -> RQ -> Extract();

										}else{
											//echo time(); exit;
											//self::$data = self::$ExternalData[$k]['value'];
										}
					*/
					//
					/*
					if( $k == 'image' )
					{
						print_r($v); exit;
						echo $k; exit;
					}
					*/
					//echo PHP_EOL . $k . PHP_EOL . ': '; 
					//print_r( self::$data ); //exit;
					self::Field( $k );

					//$position++; // for mod rewrite
                }
				//exit;

            }


			self::SessionRegister();


        }else{

            // não foi detectado nenhum dado postado via GET ou POST
            //self::$form[ self::$keyField ]['value'] = Form::Steps_New; // recebe valor inicial.
			Form::keyFieldSet( Form::Steps_New );
            
            // exclui a sessão (contem somente '_end') Form::$end
            self::$session = new Session;
            self::$session -> init();

            //Form::$sindex = get_class( $this );
            //echo ':'.Form::$sindex.':'; exit;

            // evita que seja enviado vazio e exclua todos os índices de sessão
			/*
            if( Form::$sindex != '' )
            {
                // exclui o índice de sessão ativo, porém inválido
                self::$session -> Del( Form::$sindex );
            }
			*/
			Form::Clear();

            //echo time(); exit;
            // Implementação para impedir envio duplicado de dados para formularios com opção "steps=false"
            Form::UID( 'ini' );

            /*
            // for debug purposes only
            echo '<hr>';
            if( Form::UID( ) )
            {
            echo 'uid: ' . Form::UID( ); 
            }
            exit;
            */

        }

        return null;

    }

	function SessionRegister()
	{
		//print_r( self::$Results ); exit;
		// form KeyField controller
		Form::$sindex = get_class( $this );
		//echo Form::$sindex; exit;
		self::$session = new Session;
		self::$session -> init();
		
		if( self::$Error )
		{
			//print_r( self::$Results ); exit;
			//print_r( self::$Error ); exit;
			self::ResetSteps(); // any error was found, therefore, regardless of value, return to value 1
			self::$session -> Set( Form::$sindex, self::$Results );
		}else{
			//print_r( self::$Results ); exit;
			if( self::$steps )
			{
				//echo Form::keyFieldValue(); exit;
				//print_r( self::$Results ); exit;
				switch( Form::keyFieldValue() )
				{
					case Form::Steps_New:
						// Received without errors. Show confirmation screen. Deny data edition.
						//self::$form[ self::$keyField ]['value'] = Form::Steps_Confirm;  
						Form::keyFieldSet( Form::Steps_Confirm );
						// store into global session vars (usefull for multi-part forms)
						self::$session -> Set( Form::$sindex, self::$Results );

					break;
					case Form::Steps_Confirm:

						// finished "without errors"
						//self::$form[ self::$keyField ]['value'] = Form::Steps_End;
						Form::keyFieldSet( Form::Steps_End );

					break;
				}
				//print_r( self::$Results ); exit;
			}else{
//print_r( self::$Results ); exit;
				// jump the steps. don't need confirmation step
				// don't need send keyField value, butr need store into session for internal control
				// finished "without errors"
				//self::$form[ self::$keyField ]['value'] = Form::Steps_End;
				Form::keyFieldSet( Form::Steps_End );
				
				// store into global session vars (usefull for multi-part forms)
				//Form::End(); exit;
				//print_r( $_SESSION );
				//self::$session -> Get( Form::$sindex );
				//print_r(self::$session->data); exit;
				self::$session -> Set( Form::$sindex, self::$Results );

			}


		}

	}

	function ResetSteps()
	{
		//self::$form[ self::$keyField ]['value'] = Form::Steps_New;
		Form::keyFieldSet( Form::Steps_New );
		return null;
	}

	function FieldErrorRegistry( $f, $errorCode = false )
	{
		FormValidation::LoadMsg( $errorCode );
		FormValidation::$Error[ $f ] = FormValidation::$ErrorMsg;
		FormValidation::$ErrorMsg = false;
		FormValidation::ResetSteps();
		return null;
	}

    function Reset()
    {
		self::$multiKeys = false;
		self::$tree      = false;
		self::$key       = false;
        self::$data      = false;
        self::$rules     = false;
        self::$ErrorMsg  = false;
        return null;
    }

	/*
	FormValidation::TransportSet( );
	*/
    function TransportSet( $m_to = 'all', $m_from = false  )
    {
		$a = self::TransportGet( );
		!$m_from ? $m_from = Form::$sindex : '';
		$a[$m_from][$m_to] = array(
				'tini'           => time(),
				'FormError'      => self::$Error,
				'FormMultiError' => self::$MultiError,
				'Form'           => self::$form,
				'FormResults'    => self::$Results,
				);
		$s = new Session;
		$s -> Set( self::SESS_NAME_Transport, $a );
        return null;
    }
    function TransportGet( $m_from = false, $m_to = false )
    {

		$s = new Session;
		$s -> Get( self::SESS_NAME_Transport );
		if( $s -> exists )
		{
			//print_r( $s -> data ); exit;
			if( $m_from )
			{
				if( isset( $s -> data[$m_from] ) )
				{
					if( $m_to and isset( $s -> data[$m_from][$m_to] ) )
					{
						// auto-transfer form errors to current module
						self::$Error       = $s -> data[$m_from][$m_to]['FormError'];
						self::$MultiError  = $s -> data[$m_from][$m_to]['FormMultiError'];
						self::$Results     = $s -> data[$m_from][$m_to]['FormResults'];
						//print_r( self::$Results ); exit;
						//Form::keyFieldSet( Form::Steps_Back );
						//echo Form::$sindex; exit;
						self::$session = new Session;
						self::$session -> init();
						self::ResetSteps();
						self::$session -> Set( Form::$sindex, self::$Results );

						FormValidation::TransportDel( $m_from, $m_to );

						return $s -> data[$m_from][$m_to];
					}else{
						return $s -> data[$m_from];
					}
				}
			}else{
				$s -> data;
			}
		}
        return false;
    }
    function TransportDel( $m_from = false, $m_to = false )
    {
		$s = new Session;
		$s -> Get( self::SESS_NAME_Transport );
		if( $s -> exists )
		{
			//print_r( $s -> data ); exit;
			if( $m_from )
			{
				if( isset( $s -> data[$m_from] ) )
				{
					if( $m_to and isset( $s -> data[$m_from][$m_to] ) )
					{
						unset( $s -> data[$m_from][$m_to] );
					}else{
						unset( $s -> data[$m_from] );
					}
					$s -> Set( self::SESS_NAME_Transport, $s -> data );
				}
			}else{
				// clear all entire session
				$s -> Del( self::SESS_NAME_Transport );
			}
		}
        return false;
    }

    function ResetError( $k )
    {
		if( isset( self::$Error[$k] ) )
		{
			unset( self::$Error[$k] );
		}
		if( isset( self::$MultiError[$k] ) )
		{
			unset( self::$MultiError[$k] );
		}
		if( count( FormValidation::$Error ) < 1 )
		{
			FormValidation::$Error = false;
		}
		if( count( FormValidation::$MultiError ) < 1 )
		{
			FormValidation::$MultiError = false;
		}
		return false;
	}

}
?>