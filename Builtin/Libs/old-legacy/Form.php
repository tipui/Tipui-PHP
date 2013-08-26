<?php
/** Form Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-05-03 23:12:00 - Daniel Omine
 *
 *   Methods
		Rules
		ValidSession
		UserNavigation
		SetResults
		RequestExists
		Get
		Set
		SetOpt
		GetOpt
		KeyFieldValid
        SessionGet
		keyFieldSet
        keyFieldValue
		Clear
		End
		ValidUID
        Init
		SessionExpires
		UID
		tag_params_add
        HTMLField
		InputType
		InputHidden
		InputFile
		InputTextarea
		ArrayVal
		InputText
		InputSelect
		InputRadio
		InputCheckbox
		FieldInTable
*/

class Form
{

    static $sindex            = false;
    static $data              = false;

    static $key;
	static $keyAdd            = false;
    static $cssname           = false;
	static $FieldInTableCSS   = false;
	static $FieldParamsAdd    = false;
    static $readonly          = false;
    static $PrintMode         = false;
    static $TableCols         = false;
	static $ArrayVal          = false;
	static $MultiErrorPrepend = false;
	static $MultiErrorAppend  = false;
	static $GetInit           = false;
	static $ini               = false;
	static $tag_params        = false;
    const  UID                = Register_Form::UID_SESSION_NAME;
	const  KEY_FIELD_NAME     = Register_Form::KEY_FIELD_NAME;
    const  SESSION_TIME_LIMIT = Register_Form::SESSION_TIME_LIMIT; // minutes

    // passos para o KeyField 
    const Steps_New     = 1; // first step, first entry on page withou post or used to return and correct the form entrys
    const Steps_Back    = 2; // back and correct form entrys
    const Steps_Confirm = 3; // do not display form entrys, but only form values from session. this step is used for confirmation before finish
    const Steps_End     = 4; // finished and all sessions are empty, but not automatically. must call Form::End() to  clear all sessions and set UID() as false

	function Rules( $p = false, $required = true, $value = '' )
	{
		switch( $p )
		{
			case 'PARAM_NAME':
				$rs = array (
					'type'           => 'hidden',
					'ExactValue'     => $value,
					'validation'     => 'text',
					'value'          => $value,
					'default'        => '',
					'pre-filter'     => array( array( 'Strings', 'Escape' ), array( false ) ),
				);
			break;

			case 'KeyField':
				$rs = array (
					'type'           => 'hidden',
					'ExactLength'    => 1,
					'validation'     => 'number',
					'value'          => '',
					'default'        => Form::Steps_New,
					'pre-filter'     => array( array( 'Strings', 'NumbersOnly' ) ),
				);
			break;
			// for customized rules
			default:
				$rs = DataRules::Get( $p );
			break;

		}

		// apply 'required' index value for all, regardless if it from memory or new loaded rule
		$rs['required'] = $required;

		return $rs;
	}

	function ValidSession( )
	{
		self::SetResults( );
		return self::$GetInit;
	}
	
	function UserNavigation( $module, $parameter )
	{
		//print_r( UserNavigation::URLModuleGet() ); exit;
		UserNavigation::$URLModuleName = $module;
		//print_r( UserNavigation::URLModuleGet( $parameter ) ); exit;
		$rs = UserNavigation::URLModuleGet( $parameter );
		UserNavigation::$URLModuleName = false;
		//echo $this -> reg['SymposiumID']; exit;
		return $rs;
		
		// $this -> reg['SymposiumID'] = Form::UserNavigation( 'ModuleAdminSymposiumsItemsList', 'SymposiumID' );
	}

	function SetResults( )
	{
		if( !self::$GetInit )
		{
			self::$GetInit = true;
			//echo Form::keyFieldValue(); exit;
			if( Form::keyFieldValue() == Form::Steps_End )
			{
				//echo 'UID: ' . self::UID( 'get' ); exit; // necessary to prevent duplicated posts on steps=false
				//echo Form::$sindex; exit;
				FormValidation::$session -> Get( Form::$sindex );

				// prevents bug if FormValidation::$keyField == false and steps == fase and use of ValidSession()
				$u = true;
				//echo 'keyField: ' . FormValidation::$keyField; exit;
				// and FormValidation::$steps
				if( FormValidation::$keyField and FormValidation::$steps )
				{
					//echo time(); exit;
					// only for multi-part/data forms
					$u = self::UID( 'get' );
				}else{
					//echo time() . 'b'; exit;
				}
				//echo 'u: ' . $u; exit;
				//print_r(FormValidation::$session -> data); exit;
				if( FormValidation::$session -> exists and $u )
				{
					//echo time().'a'; exit;
					FormValidation::$Results = FormValidation::$session -> data;
					//print_r( FormValidation::$Results ); exit;
				}else{
					//echo time(); exit;
					// session expired.
					// Form::End() was called or session limit time exceded or UID returned false or empty
					self::$GetInit = false;
				}

			}else{

				if( FormValidation::$request )
				{
					if( Form::keyFieldValue() == Form::Steps_Back )
					{
						FormValidation::$session -> Get( Form::$sindex );
						//print_r( FormValidation::$session ); exit;
						//echo time(); exit;

						FormValidation::$Results = FormValidation::$session -> data;
					}else{
						//print_r( FormValidation::$Results ); exit;
					}
				}

			}
		}
		return null;
	}

	function RequestExists()
	{
		if( FormValidation::$request and Form::keyFieldValue() == Form::Steps_New or Form::keyFieldValue() == Form::Steps_Confirm or ( FormValidation::$request and !FormValidation::$steps ) )
		{
			return true;
		}
		return false;
	}

	function Get( $p, $direct = false )
	{

		!$direct ? self::SetResults() : '';

		if( isset( FormValidation::$Results[$p] ) )
		{
			return FormValidation::$Results[$p];
		}
		
		return false;
	}

	function Set( $p, $v, $session = false )
	{
		FormValidation::$form[$p]['value'] = $v;
		FormValidation::$Results[$p]       = $v;

		if( $session )
		{
			FormValidation::$session -> Get( self::$sindex );
			if( FormValidation::$session -> exists )
			{
				//print_r( FormValidation::$session -> data ); exit;
				FormValidation::$session -> data[$p] = $v;
				FormValidation::$session -> Set( self::$sindex, FormValidation::$session -> data );
			}
		}

		return null;
	}
	function SetOpt( $p, $v, $opt = 'value' )
	{
		FormValidation::$form[$p][$opt] = $v;
		return null;
	}
	function GetOpt( $p, $opt = 'value' )
	{
		return FormValidation::$form[$p][$opt];
	}

	function KeyFieldValid()
	{
		return in_array( self::keyFieldValue(), array( 1, 2, 3, 4 ) );
	}

    function SessionGet()
    {
		//Form::Steps_Confirm
		//echo gettype( self::$data ); exit;
		//print_r( FormValidation::$Results ); exit;

		if( !self::$ini )
		{

			self::Init();
			//self::$data = FormValidation::$form; 
			//echo 2;
		}

        FormValidation::$session -> Get( self::$sindex );
        if( FormValidation::$session -> exists )
        {

			//echo self::$sindex; exit;
            //print_r( FormValidation::$session ); exit;
            //if( self::$key == 'PageNum' ){echo time(); exit;}
            //if( self::$key == 'PageNum' ){
            //    echo FormValidation::$session -> data[ self::$key ]; exit;
            //}
			//print_r( FormValidation::$session -> data ); exit;
            if( isset( FormValidation::$session -> data[ self::$key ] ) )
            {
				//if( self::$key == 'ID' )
				//{
					//echo '<hr>:'; print_r( FormValidation::$session -> data[ self::$key ] );
				//}

				// force load all array index
				//self::$data[self::$key] = FormValidation::$form[self::$key];


				//print_r(self::$data[self::$key]); exit;
                self::$data[self::$key]['value'] = FormValidation::$session -> data[ self::$key ];
				
				//print_r(self::$data[self::$key]); exit;
				if( !self::$data[self::$key]['value'] )
				{
					if( isset( FormValidation::$Results[self::$key] ) and !empty( FormValidation::$Results[self::$key] ) )
					{
						self::$data[self::$key]['value'] = FormValidation::$Results[self::$key];
					}
				}
				//if( self::$key == 'w' )
				//{
					//echo mb_convert_encoding( self::$data[self::$key]['value'], 'utf-8', 'sjis' );; exit;
				//}
            }
			/*
			if( self::$key != PARAM_NAME ) 
			{
				//echo self::$key; exit;
				print_r( FormValidation::$session ); exit;
			}
			*/
        }
		return null;
    }

    function keyFieldSet( $v )
    {
		FormValidation::$form[FormValidation::$keyField]['value'] = $v;
		return null;
	}

    function keyFieldValue()
    {

        return FormValidation::$form[FormValidation::$keyField]['value'];

        /*
        //if( FormValidation::$method == 'GET' ){
            return FormValidation::$form[FormValidation::$keyField]['value'];
        //}

        if( !FormValidation::$steps )
        {
            FormValidation::$form[FormValidation::$keyField]['value'] = self::Steps_End;
        }
        return FormValidation::$form[FormValidation::$keyField]['value'];
        */

    }
	
	function Clear()
	{
		// used for steps_new
		if( FormValidation::$session )
		{
			( !Form::$sindex or Form::$sindex == '' ) ? Form::$sindex = get_class( $this ) : '' ;
			//FormValidation::$session -> Get( Form::$sindex );
			//echo Form::$sindex; exit;
			
			( Form::$sindex and Form::$sindex != '' ) ? FormValidation::$session -> Del( Form::$sindex ) : '' ;
			//print_r( FormValidation::$session ); exit;
		}
		return null;
	}

    function End()
    {
		// used for steps_end


		/*
		if( FormValidation::$session and Form::$sindex )
		{
			FormValidation::$session -> Del( Form::$sindex );
		}
		*/
		self::Clear();
		self::UID( 'end' );
        return null;
    }
	
	function ValidUID()
	{
		if( !Form::UID( 'check' ) )
		{
			//echo time(); exit;
			Form::End();
			return false;
		}
		return true;
	}

    function Init()
    {
	
		//echo time(); exit;
		if( Form::keyFieldValue() < Form::Steps_End )
		{
			self::$ini      = true;
			self::$data     = FormValidation::$form;
		}

        if( self::keyFieldValue() == self::Steps_Back )
        {
            self::$data[FormValidation::$keyField]['value'] = self::Steps_New;
			self::keyFieldSet( self::Steps_New );
            //FormValidation::$form[FormValidation::$keyField]['value'] = self::Steps_New;
        }
    }

    function SessionExpires()
    {
        if( FormValidation::$SessionTimeLimit )
        {
            return FormValidation::$SessionTimeLimit;
        }
		//echo self::SESSION_TIME_LIMIT; exit;
        return self::SESSION_TIME_LIMIT;
    }

    // Gera um valor de sessão temporário quando a página é executada sem envio de dados via Get/POST (especificamente o parametro "act" FormValidation)
	// generate session value based on timestamp.
	// this is necessary to save first entrance on form without submitted data.
	// must be clear and every first entrance on page that have no $request or session stored or even if session exists and had expired
    function UID( $action = false )
    {

        $r = false;
        $s = new Session;
		$s -> init();
        $s -> Get( self::UID );
        //$s -> Del( self::UID ); exit;
		//echo 'self::UID: ' . self::UID; exit;
		//var_dump($s); exit;
        switch( $action )
        {
            case 'ini':
				$r = array( self::UID => time() );
				$s -> Set( self::UID, $r );
				
				/*
                if( !$s -> exists )
                {
                    $r = array( self::UID => time() );
                    $s -> Set( self::UID, $r );
                }else{
                    
                    // for debug purposes only
                    $s -> Get( self::UID );
                    echo 'ok: ' . time() . ' ';
                    //print_r( $s -> data );
                    echo ' : ' . $s -> data[self::UID];
                    
                }
				*/
            break;
            case 'end':
                $s -> Del( self::UID );
            break;
            case 'get':
                if( $s -> exists )
                {
					$r = $s -> data[self::UID];
				}else{
					//echo 'UID not exists'; exit;
					$r = false;
				}
            break;
            default:
			case 'check':
					//print_r( $s );
					//echo self::UID; exit;
                if( $s -> exists )
                {
					$r = $s -> data[self::UID];

                    //if( floor( ( strtotime( '+' . Form::SessionExpires() . ' minutes' ) - $r ) / 60 ) > Form::SessionExpires() )
                    //{

                    if( strtotime( '+' . Form::SessionExpires() . ' minutes', $r ) < time() )
                    {
                        $r = false;
                        //$s -> Del( self::UID );
                        //echo 'expired session'; exit;
                    }else{
						//echo 'ok'; exit;
						// for debug purposes
						//echo $r; exit;
						//echo floor( ( strtotime( '+' . Form::SessionExpires() . ' minutes' ) - $s -> data[self::UID] ) / 60 ); exit;
					}

                }
            break;
        }

        unset( $s );
        return $r;
    }
	
	function tag_params_add()
	{
		$tag = '';
		if( self::$tag_params )
		{
			if( is_array( self::$tag_params ) )
			{
				foreach( self::$tag_params as $k => $v )
				{
					$tag .= ' ' . $k . '="' . $v . '"';
				}
			}else{
				$tag = ' ' . self::$tag_params;
			}
		}
		self::$tag_params = false;

		return $tag;
	}

    function HTMLField( )
    {
//print_r(self::$data);
        if( self::$sindex and self::$key != FormValidation::$keyField )
        {
			//echo time(); exit;
            self::SessionGet();
        }
		//echo self::$key; exit;
        $data = self::$data[self::$key];
		//if( self::$key == 'p' ){
		//print_r( self::$data[self::$key] ); exit;
		//}
        $rs = null;
		//print_r( $data ); exit;

		// [2013-05-03 23:11] bug of undefined indexes when steps is null and act parameter is false
		if( !isset( $data['type'] ) )
		{
			FormValidation::$form[self::$key]['value'] = $data['value'];
			$data = FormValidation::$form[self::$key];
			self::keyFieldSet( Form::Steps_New );
		}
		//print_r( $data ); exit;

        if( is_array( $data['type'] ) )
        {

            $rs = '';

            foreach( $data['type'] as $k => $v )
            {
                $name                = self::$key . '[' . $data['names'][$k] . ']';
                $arr['type']        = $data['type'][$k];
                $arr['size']        = $data['size'][$k];
                $arr['MaxLength']   = $data['MaxLength'][$k];
                $arr['MinLength']   = $data['MinLength'][$k];
                $arr['value']       = $data['value'][$k];
                $arr['default']     = $data['default'][$k];
                $arr['options']     = $data['options'][$k];
                $arr['validation']  = $data['validation'];
                $rs .= self::InputType( $v, $arr, $name );
            }

        }else{
            $rs = self::InputType( $data['type'], $data );
        }

        return $rs;
    }



    function InputType( $type, $data, $name = false )
    {

        switch( $type )
        {

            default:
                $rs = false;
            break;

            case 'hidden':

                $rs = self::InputHidden( $data, $name );

            break;
            case 'text':
            case 'password':

                $rs = self::InputText( $data, $name );

            break;

            case 'textarea':

                $rs = self::InputTextarea( $data, $name );

            break;

            case 'select':

                $rs = self::InputSelect( $data, $name );

            break;
            case 'radio':

                $rs = self::InputRadio( $data, $name );

            break;
            case 'checkbox':
		//if( self::$key == 'ID' )
		//{
			//print_r( $data['value'] ); exit; //options
		//}
                $rs = self::InputCheckbox( $data, $name );

            break;
            case 'file':

                $rs = self::InputFile( $data, $name );

            break;

        }

        return $rs;

    }

    function InputHidden( $data, $name = false )
    {

        !$name  ? $name   = self::$key      : '';
        return '<input name="' . $name . '" type="hidden" value="' . Strings::Escape( $data['value'], 'quotes' ) . '">';

    }

    function InputFile( $data, $name = false )
    {

        !$name  ? $name   = self::$key      : '';

		if( self::keyFieldValue() < self::Steps_Confirm ){
			/*
			$rs = '<input name="' . $name . '" type="file" value="" size="' . $data['size'] . '">';
			if( isset( $data['value']['name'] ) ){
				$rs .= '<br />' . $data['value']['name'];
			}
			return $rs;
			*/

			return '<input name="' . $name . '"' . self::tag_params_add() . ' type="file" value="" size="' . $data['size'] . '">';
		}else{
			if( isset( $data['value']['name'] ) )
			{
				return $data['value']['name'];
			}else{
				return '---';
			}
		}

    }

    function InputTextarea( $data, $name = false )
    {
        !$name  ? $name   = self::$key      : '';

        $rs  = '<textarea' . self::tag_params_add() . ' name="' . $name;
		if( self::$keyAdd )
		{
			if( !is_array( self::$keyAdd  ) )
			{
				$rs .= '[' . self::$keyAdd . ']';
			}else{
				foreach( self::$keyAdd as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}
		}
		$rs .= '" cols="' . $data['cols'] . '" rows="' . $data['rows'] . '"';

        if( self::$cssname  )
        {
            $rs .= ' class="' . self::$cssname . '"';
        }

        if( self::$readonly or self::keyFieldValue() >= self::Steps_Confirm )
        {
            $rs .= ' readonly="readonly"';
        }
        $rs .= '>';

		if( !is_array( $data['value'] ) )
		{
			if( $data['value'] != '' )
			{
				$rs .= $data['value'];
			}else{
				$rs .= $data['default'];
			}
		}else{
		
			if( self::$keyAdd )
			{
				//echo time();
				//print_r( $data['value'] ); exit;
				self::$ArrayVal = '';
				self::ArrayVal( $data['value'] );
				//echo self::$ArrayVal; //exit;
				//print_r( self::$ArrayVal ); exit;
				
				if( self::$ArrayVal == '' )
				{
					if( isset( $data['default'] ) )
					{
						$rs .= $data['default'];
					}
					//print_r( $data ); exit;
					//$rs .= 'cc';
				}else{
					//$rs .= 'bb';
					
					$rs .= self::$ArrayVal;
				}
				//print_r( $r ); exit;

			}else{
				$rs .= $data['default'];
			}
			
		}
			
        $rs .= '</textarea>';

		if( self::$keyAdd and FormValidation::$MultiError )
		{
			$k = implode( '/', self::$keyAdd );
			//echo $k; exit;
			if( isset( FormValidation::$Error[self::$key] ) and isset( FormValidation::$MultiError[self::$key][$k] ) )
			{
				if( self::$MultiErrorPrepend )
				{
					$rs .= self::$MultiErrorPrepend;
				}
				$rs .= FormValidation::$MultiError[self::$key][$k];
				if( self::$MultiErrorAppend )
				{
					$rs .= self::$MultiErrorAppend;
				}
			}
		}
        return $rs;
    }
	
	function ArrayVal( $data, $key = 0 )
	{
		if( isset( self::$keyAdd[$key] ) )
		{
			//echo  self::$keyAdd[$key]. '<br />'; exit;
			//print_r( $data[self::$keyAdd[$key]] ); exit;
			/*
			if( !isset( $data[self::$keyAdd[$key]] ) )
			{
				//echo 'key: ' . gettype( self::$keyAdd[$key] ); exit;
				self::$ArrayVal = $data[self::$keyAdd[$key]];
			}else{
				self::$ArrayVal = '';
				//return null;
			}
			*/
			self::$ArrayVal = $data[self::$keyAdd[$key]];
			//echo  self::$ArrayVal. '<br />';
			//echo '<hr>'; print_r( self::$ArrayVal ); //exit;
			$key++;
			self::ArrayVal( self::$ArrayVal, $key );
		}else{
			//echo '<hr>val: '; print_r( self::$ArrayVal ); //exit;
			//return self::$ArrayVal;
		}
		return null;
	}

    function InputText( $data, $name = false )
    {

        !$name  ? $name   = self::$key      : '';
//echo self::keyFieldValue(); exit;
        // se for menor que "3", ou seja, se for ação "new" ou "back"
        if( self::keyFieldValue() < self::Steps_Confirm )
        {

            $rs  = '<input' . self::tag_params_add() . ' name="' . $name;
            //if( isset( $data['multiple'] ) )
            //{
			if( self::$keyAdd )
			{
				if( !is_array( self::$keyAdd  ) )
				{
					$rs .= '[' . self::$keyAdd . ']';
				}else{
					foreach( self::$keyAdd as $k => $v )
					{
						$rs .= '[' . $v . ']';
					}
				}
			}
			//}
			$rs .= '" type="' . $data['type'] . '" value="';

			//print_r( $data['value'] ); exit;
            if( !is_array( $data['value'] ) )
            {
				if( $data['value'] != '' )
				{
					$rs .= Strings::Escape( $data['value'], 'quotes' );

				}else{
					$rs .= Strings::Escape( $data['default'], 'quotes' );

				}
            }else{
				if( self::$keyAdd )
				{
					//echo time();
					//print_r( $data['value'] ); exit;
					self::$ArrayVal = '';
					self::ArrayVal( $data['value'] );
					//echo self::$ArrayVal; //exit;
					//print_r( self::$ArrayVal ); exit;
					
					if( self::$ArrayVal == '' )
					{
						if( isset( $data['default'] ) )
						{
							$rs .= $data['default'];
						}
						//print_r( $data ); exit;
						//$rs .= 'cc';
					}else{
						//$rs .= 'bb';
						
						$rs .= Strings::Escape( self::$ArrayVal, 'quotes' );
					}
					//print_r( $r ); exit;

				}else{
					$rs .= Strings::Escape( $data['default'], 'quotes' );

				}
            }
			$length = ( isset( $data['MaxLength'] ) ) ? $data['MaxLength'] : $data['ExactLength'];
            $rs .= '" size="' . $data['size'] . '" maxLength="' . $length . '"';

            if( self::$cssname  )
            {
                $rs .= ' class="' . self::$cssname . '"';
            }
            if( self::$readonly  )
            {
                $rs .= ' readonly';
            }
            $rs .= '>';
			
			if( self::$keyAdd and FormValidation::$MultiError )
			{
				$k = implode( '/', self::$keyAdd );
				//echo $k; exit;
				if( isset( FormValidation::$Error[self::$key] ) and isset( FormValidation::$MultiError[self::$key][$k] ) )
				{
					if( self::$MultiErrorPrepend )
					{
						$rs .= self::$MultiErrorPrepend;
					}
					$rs .= FormValidation::$MultiError[self::$key][$k];
					if( self::$MultiErrorAppend )
					{
						$rs .= self::$MultiErrorAppend;
					}
				}
			}
        }else{
			if( is_array( $data['value'] ) )
			{
				self::$ArrayVal = '';
				self::ArrayVal( $data['value'] );
				$rs = self::$ArrayVal;
			}else{
				$rs = $data['value'];
			}
            if( $data['validation'] == 'date' )
            {
                if( $name == self::$key . '[0]' )
                {
                    $rs .= '/';
                }
            }

        }


        return $rs;

    }


    function InputSelect( $data, $name = false )
    {

        !$name  ? $name   = self::$key      : '';

        if( self::keyFieldValue() < self::Steps_Confirm )
        {

            $rs  = '<select' . self::tag_params_add() . ' name="' . $name . '">';

            $check = false;

            if( $data['value'] != '' )
            {

                $check = (string)$data['value'];
                //echo gettype($check); exit;
            }else{

                if( $data['default'] )
                {
                    $check = (string)$data['default'];
                }

            }

			if( isset( $data['options'] ) and is_array($data['options']) and count($data['options']) > 0 )
			{
				//print_r($data['options']);
				foreach( $data['options'] as $k => $v )
				{
					if( !is_array( $v ) )
					{
						$rs .= '<option';
						$rs .= ' value="' . $k . '"';
					}else{
						if( isset( $v['optgroup'] ) )
						{
							$rs .= '<optgroup label="' . $v['optgroup']  . '">';
						}
					}

					if( !is_array( $v ) and gettype($check) != 'boolean' and $check == $k )
					{
						$rs .= ' selected';
					}

					if( !is_array( $v ) )
					{
						$rs .= '>';
						$rs .= $v;
						$rs .= '</option>';
					}else{
						if( isset( $v['optgroup'] ) )
						{
							if( isset( $v['options'] ) and is_array( $v['options'] ) and count( $v['options'] ) > 0 )
							{
								foreach( $v['options'] as $k1 => $v1 )
								{
									$rs .= '<option';
									$rs .= ' value="' . $k1 . '"';
									if( gettype($check) != 'boolean' and $check == $k1 )
									{
										$rs .= ' selected';
									}
									$rs .= '>';
									$rs .= $v1;
									$rs .= '</option>';
								}
							}
							$rs .= '</optgroup>';
						}
					}
				}
			}

            $rs .= '</select>';

        }else{

			// FormValidation::$keyField cannot be "false", set as:
			// FormValidation::$keyField  = Form::KEY_FIELD_NAME;
			$rs = '';
            if( $data['validation'] == 'date' )
            {
                if( $name == self::$key . '[0]' )
                {
                    $rs = DateAndTimeLabels::DateMask( self::$data[self::$key]['value'] );
                }
				
				//self::$data[self::$key]['options'][0]
				//self::$data[self::$key]['options'][1]
				//self::$data[self::$key]['options'][2]
				//print_r( self::$data[self::$key]['value'] ); exit;
            }else{
				if( count( $data['options'] ) > 0 )
				{
					$rs = isset( $data['options'][ $data['value'] ] ) ? $data['options'][ $data['value'] ] : '---';
				}else{
					if( isset( $data['after_post_options'] ) )
					{
						$cal[0] = $data['after_post_options'][0];
						$cal[1] = array(Form::Get($data['after_post_options'][1][0]));
						$data['options'] = Functions::Call($cal);
						//echo $data['options'][$data['value']]; exit;
						$rs = $data['options'][$data['value']];
					}else{
						$rs = '---';
					}
				}
			}

        }

        return $rs;

    }


    function InputRadio( $data, $name = false )
    {

        $name  ? self::$key = $name : '';

        if( self::keyFieldValue() < self::Steps_Confirm )
        {

            $rs = self::FieldInTable( $data, $data['options'] );

        }else{

            if( isset( $data['multiple'] ) )
            {
                $rs = self::FieldInTable( $data, $data['value'], true );
            }else{
                $rs = $data['options'][ $data['value'] ];
            }

        }

        return $rs;

    }

    function InputCheckbox( $data, $name = false )
    {

        $name  ? self::$key = $name : '';

        if( self::keyFieldValue() < self::Steps_Confirm )
        {
			//echo time() . 'b'; 
            if( !isset( $data['multiple'] ) )
            {
			
				if( !isset( $data['options'] ) )
				{
					$rs = '<input' . self::tag_params_add() . ' type="' . $data['type'] . '" name="' . self::$key . '"';
					
					if( self::$cssname  )
					{
						$rs .= ' class="' . self::$cssname . '"';
					}
					//echo self::$key; exit;
					if( $data['value'] )
					{
						$rs .= ' checked';
					}

					$rs .= ' value="';

					if( $data['value'] != '' )
					{
						$rs .= $data['value'];
					}else{
						$rs .= $data['default'];
					}

					$rs .= '">';
//print_r( $data['value'] ) . ':';
				}else{
					$rs = self::FieldInTable( $data, $data['options'] );
				}

			}else{	
//echo self::keyFieldValue(); exit;			
				if( self::keyFieldValue() == self::Steps_New )
				{
					$rs = self::FieldInTable( $data, $data['value'] );
				}else{
					//echo time() . 'b';
					$rs = self::FieldInTable( $data, $data['value'], true );
				}
				//$rs = self::FieldInTable( $data, $data['value'] );
			}

        }else{
			//echo time() . 'a'; 
			$rs = '';
            if( isset( $data['multiple'] ) )
            {
                $rs = self::FieldInTable( $data, $data['value'], true );
            }else{
			
				//echo strlen( $data['value'] ); exit;
			
				if( isset( $data['options'][ $data['value'] ] ) )
				{
					$rs = $data['options'][ $data['value'] ];
				}
            }

        }

        return $rs;

    }

    function FieldInTable( $data, $arr = false, $view = false )
    {

        if( !$arr )
        {
            $arr = $data['options'];
        }

		//print_r( $arr  ); exit;
		
		if( self::$keyAdd )
		{
			if( isset( $arr[self::$keyAdd] ) )
			{
				$arr = $arr[self::$keyAdd];
			}
		}
		//print_r( $arr  ); exit;

		if( self::$keyAdd )
		{
			//echo self::$keyAdd; exit;
			//$tmp = str_replace( '[', "", self::$keyAdd );
			//$tmp = str_replace( "]", "", $tmp );
			//echo $tmp; exit;
			//print_r( $v ); exit;
			//print_r( $data['key'][self::$keyAdd] ); exit;
			//$rs .= $data['key']{self::$keyAdd}[$k];
			//$arr = $data['key'][self::$keyAdd];
			if( isset( $data['value'][self::$keyAdd] ) )
			{
				$data['value'] = $data['value'][self::$keyAdd];
			}
		}
		//print_r( $data ); exit;
		//print_r( $data['key'][self::$keyAdd] ); exit;

		//print_r( $data['value'] ); exit;
        $check = false;

        if( isset( $data['multiple'] ) )
        {

            if( is_array( $data['value'] ) )
            {
                foreach( $data['value'] as $k => $v )
                {
					//print_r( $data['value'] ); exit;
                    $check[$k] = $v;
                }
				//print_r( $check ); exit;
            }
			//print_r( $arr ); exit;
        }else{

            if( $data['value'] != '' )
            {
                $check = $data['value'];
				//echo $check; exit;
            }else{
                if( $data['default'] )
                {
                    $check = $data['default'];
					settype( $check, 'string' );
                }
            }

        }

        if( !self::$TableCols )
        {
            self::$TableCols = 3;
        }
		
		//print_r( $data['options'] ); exit;
		if( Form::keyFieldValue() < Form::Steps_Confirm )
		{

			$arr = $data['options'];
			//echo time(); exit;
		}else{
			if( isset( $data['value'] ) and !empty( $data['value'] ) )
			{
				$arr = $data['value'];
			}else{
				return null;
			}
		}
		//print_r( $data['options'] ); exit;
		//print_r( $data['label'] ); exit;
        $size = count( $arr );
        $i    = 0;
        $j    = 0;
        $rs   = PHP_EOL . '<table class="frm">';
		//print_r( $arr ); exit;
		//print_r( $data['options'] ); exit;
		//$arr = ;
		//foreach( $arr as $k => $v )
        foreach( $arr as $k => $v )
        {

            if( $j == 0 )
            {
                $rs .= PHP_EOL . '<tr>';
            }

			if( !self::$FieldInTableCSS )
			{
				$rs .= '<td>';
			}else{
				$rs .= '<td class="' . self::$FieldInTableCSS . '">';
			}

            if( !$view )
            {
				if( $data['type'] == 'checkbox' or $data['type'] == 'radio' )
				{
					$rs .= '<label>';
				}
			
                $rs .= '<input' . self::tag_params_add() . ' type="' . $data['type'] . '" name="' . self::$key;
				if( self::$keyAdd )
				{
					$rs .= '[' . self::$keyAdd . ']';
				}

                if( isset( $data['multiple'] ) )
                {
					$rs .= '[' . $k . ']';
                }

                $rs .= '"';
                if( isset( $data['multiple'] ) )
                {
					$rs .= ' value="' . $v . '"';
				}else{
					$rs .= ' value="' . $k . '"';
				}
    
                if( $check )
                {
					settype( $k, 'string' );
					//echo self::$key . ':' . get( $check ); exit;
                    if( !is_array( $check ) and $check == $k )
                    {
                        $rs .= ' checked';
                    }else{

                        if( isset( $data['multiple'] ) )
                        {
                            if( isset( $check[$k] ) )
                            {
                                $rs .= ' checked';
                            }
                        }

                    }
                }
    
                if( self::$cssname  )
                {
                    $rs .= ' class="' . self::$cssname . '"';
                }
    
                $rs .= '>';

				if( isset( $data['label'][$k] ) )
				{
					$rs .= $data['label'][$k];
				}else{
					$rs .= $data['options'][$k];
				}
				
				if( $data['type'] == 'checkbox' or $data['type'] == 'radio' )
				{
					$rs .= '</label>';
				}

            }else{
				if( isset( $data['label'][$k] ) )
				{
					$rs .= $data['label'][$k];
				}else{
					//$rs .= 'NG - ' . $k; // for debug purposes
					$rs .= '';
				}
//[stack 1]
/*
                //if( !isset( $data['multiple'] ) )
                //{
					//print_r($data['label']); exit;
					if( !self::$keyAdd and isset( $data['label'][$k] ) )
					{
						$rs .= $data['label'][$k];
					}else{
						//echo self::$keyAdd; exit;
						//$tmp = str_replace( '[', "", self::$keyAdd );
						//$tmp = str_replace( "]", "", $tmp );
						//echo $tmp; exit;
						print_r( $v ); exit;
						print_r( $data['key'][self::$keyAdd] ); exit;
						$rs .= $data['key']{self::$keyAdd}[$k];
					}
                //}else{
                //    $rs .= $data['label'][$k];
                //}
				*/

            }


            $rs .= '</td>';

            if( ( $j + 1 ) == self::$TableCols or ( $i + 1 ) == $size )
            {
                $rs .= PHP_EOL . '</tr>';
                $j   = 0;
            }else{
                $j++;
            }

            $i++;

        }
        $rs .= PHP_EOL . '</table>';

        return $rs;
    }

}
?>