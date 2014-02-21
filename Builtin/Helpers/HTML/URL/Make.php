<?php

/**
* @class  Make
* @file   Make.php
* @brief  Make HTML URL functions.
* @date   2013-09-30 01:39:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-02-02 00:37:00
*/

namespace Tipui\Builtin\Helpers\HTML\URL;

class Make
{

	/**
	* Handles array of results (url settings, parameters, values)
	*/
	protected $data;

	/**
	* Handles array of URL environment data.
	*/
	protected $env_url;

	/**
	* Handles array of LANGUAGES environment data.
	*/
	protected $env_languages = null;

	/**
	* Handles array of BOOTSTRAP environment data.
	*/
	protected $env_bootstrap;

	/**
	* Handles the language code stored on cache.
	*/
	protected $cached_lang_code_from_param = null;

	/**
	* Handles the language code passed as parameter.
	*/
	protected $lang_code = null;

	/**
	* Mount and return local domain URL
	*
	* Usage escope
	*
	* URL::Make( [url_mode], [protocol], [url_base] ) -> Parameters( 'id', 'other' ) -> Values( model_name, 1, 2 );
	*
	* @param $url_mode: 'normal or 'mod_rewrite'
	* @param $url_base: '/'
	* @param $protocol: 'http:', 'https:'
	*/
	public function Exec( $url_mode = false, $url_base = false, $protocol = false  )
	{

		if( $this -> cached_lang_code_from_param === null )
		{
			/**
			* Get Language Code value from cached data.
			*/
			$this -> cached_lang_code_from_param = \Tipui\Core::GetConf() -> GetMethodDataCache( 'LanguageCodeFromParameters' );

			/**
			* Debug purposes
			* Must print without "exit".
			*/
			//echo time(); exit;
			//print_r( $_COOKIE ); exit;
			//var_dump( $this -> cached_lang_code_from_param ); //exit;
		}

		if( empty( $this -> env_url ) )
		{
			/**
			* Get URL env settings
			*/
			$this -> env_url = \Tipui\Core::GetConf() -> URL -> _all;

			/**
			* Debug purposes
			*/
			//print_r( $this -> env_url ); exit;
		}

		/**
		* Set URL mode (normal or mod rewrite)
		*/
		$this -> data['t'] = $url_mode ? $url_mode : $this -> env_url['MODE'];

		/**
		* Set base as relative URL
		*/
		if( $url_base )
		{
			$this -> data['b'] = $url_base;
		}else{
			$this -> data['b'] = $this -> env_url['HREF_BASE'];
		}

		if( $protocol )
		{

			if( empty( $this -> env_bootstrap ) )
			{
				/**
				* Get BOOTSTRAP env settings
				*/
				$this -> env_bootstrap = \Tipui\Core::GetConf() -> BOOTSTRAP -> _all;
			}

			/**
			* Set base as absolute URL including protocol + subdomain + domain
			*/
			$this -> data['b'] = $protocol . $this -> env_bootstrap['SUBDOMAIN'] . $this -> env_bootstrap['DOMAIN'] . $this -> data['b'];

		}

        return $this;

	}



	/**
	* Set the parameters and return object
	* @see self::Values()
	*/
	public function Parameters()
	{
		$this -> data['k'] = func_get_args();
		array_unshift( $this -> data['k'], $this -> env_url['PARAM_NAME'] );
        return $this;
	}



	/**
	* Set the parameters values and check for the language parameter, if existis.
	* Returns the method self::Mount() that will compose the arrays.
	*/
	public function Values()
	{
		$this -> data['v'] = func_get_args();

		/**
		* Looking for language code parameter
		*/
		if( !in_array( $this -> env_url['PARAM_LANG'], $this -> data['k'] ) )
		{

			/**
			* Regardless of "missing" $this -> env_url['PARAM_LANG'], must look for value parameters.
			* If the first parameter have exact 2 characters, may validate if is a valid and registered language code. 
			*/
			if( isset( $this -> data['v'][0] ) && strlen( $this -> data['v'][0] ) == 2 )
			{

				/**
				* Loading the languages codes defined on environment configuration files.
				*/
				if( empty( $this -> env_languages ) )
				{
					$this -> env_languages = \Tipui\Core::GetConf() -> LANGUAGES -> _all;
				}

				/**
				* Checking if the language parameter is valid.
				*/
				$this -> lang_code = isset( $this -> env_languages[$this -> data['v'][0]] ) ? $this -> data['v'][0] : null;

				if( !empty( $this -> lang_code ) )
				{
					/**
					* Appending the language parameter name to the first index
					*/
					array_unshift( $this -> data['k'], $this -> env_url['PARAM_LANG'] );
				}else{
					/**
					* The language parameter is not valid. Must remove it from array of values.
					*/
					unset( $this -> data['v'][0] );
				}

			}

			/**
			* In language code parameter is not valid, will be assigned the current language code handled from cache of Core methods.
			* @see: Core::SetLanguageCodeFromParameters()
			*/
			if( empty( $this -> lang_code ) && !empty( $this -> cached_lang_code_from_param ) )
			{
				/**
				* Appending the language parameter name and value to the first index
				* [review] Maybe necesssary create conditional for switch the user needs.
				* Example, if the user do not want to display the language parameter when was the same as default. For this case, must check against [env]::TEMPLATE -> DEFAULT_LANGUAGE
				*/
				array_unshift( $this -> data['k'], $this -> env_url['PARAM_LANG'] );
				array_unshift( $this -> data['v'], $this -> cached_lang_code_from_param );
			}

		}else{
			/**
			* The language parameter name was explicited.
			* For this case, don't need to check if is valid or not. Let the Core request do the validations when the user send the URL.
			*/
		}

        return $this -> Mount();

	}



	/**
	* Mount and return local domain URL
	* @see self::Parameters()
	* @see self::Values()
	*/
	private function Mount()
	{

        $r = '';

		/**
		* Debug purposes
		*/
		//print_r( $this -> data ); //exit;
		//var_dump( func_get_args() ); exit;
		//echo $this -> data['b'] . 'aaaa'; //exit;

		/**
		* URL arguments (parameters)
		* k represents parameters names
		* v represents respective values of parameters
		* both must have same size.
		*
		* If Parameters and Values are empty, ie: HTML\URL::Make() -> Parameters() -> Values();
		* or, if size of arrays doesn't match, then, returns the env HREF_BASE
		*/
        if( count( $this -> data['k'] ) <> count( $this -> data['v'] ) )
        {
			return $this -> data['b'];
            //return null;
        }

        switch( $this -> data['t'] )
        {
            default:
            case 'normal':

				$r = $this -> data['b'] . $this -> env_url['PARAM_ARGUMENTOR'];

				if( $this -> data['k'] and is_array( $this -> data['k'] ) )
				{
					foreach( $this -> data['k'] as $k => $v )
					{

						settype( $this -> data['v'][$k], 'string' ); // prevent 0 value as boolean, null or empty

						if( $k > 0 )
						{
							$r .= $this -> env_url['PARAM_SEPARATOR'];
						}
						if( !empty( $v ) )
						{
							$r .= $v . '=';
						}
						$r .= $this -> data['v'][$k];
					}
				}
				if( ini_get( 'session.use_trans_sid' ) == 1 )
				{
					if( !$this -> data['k'] or !is_array( $this -> data['k'] ) or isset( $this -> data['SID'] ) )
					{
						$r .= $this -> env_url['PARAM_SEPARATOR'];
						$r .= ini_get('session.name') . '=' . session_id();
					}
				}

            break;

            case 'mod_rewrite':

                $r = $this -> data['b'];
				if( $this -> data['k'] and is_array( $this -> data['k'] ) )
				{
					foreach( $this -> data['v'] as $k => $v )
					{

						settype( $v, 'string' ); // prevent 0 value as boolean, null or empty

						if( $k > 0 )
						{
							$r .= $this -> env_url['PFS'];
						}

						$r .= $v;

					}
				}
				if( ini_get( 'session.use_trans_sid' ) == 1 )
				{
					if( $this -> data['k'] and is_array( $this -> data['k'] ) )
					{
						//$r .= $this -> env_url['PFS'];
						//$r .= ini_get('session.name') . '=' . session_id();
						//$r .= $this -> env_url['PFS'];
						$r .= $this -> env_url['PFS'];
						$r .= $this -> env_url['PARAM_ARGUMENTOR'];
						//$r .= 'x';
					}else{
						$r .= $this -> env_url['PARAM_ARGUMENTOR'];
						$r .= 'x';
						$r .= ini_get('session.name') . '=' . session_id();
					}
				}
					
				
            break;
        }
		//echo $r; exit;
        return $r;

	}

}