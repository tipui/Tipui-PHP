<?php

/**
* @class  Sanitize
* @file   Sanitize.php
* @brief  Sanitize Builtin DataValidation functions.
* @date   2014-01-03 05:30:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-22 20:30:00
*/

namespace Tipui\Builtin\Libs\DataValidation;

use \Tipui\Builtin\Libs\Form as Form;
use \Tipui\Builtin\Libs\DataRules as DataRules;
use \Tipui\Builtin\Libs\Strings as Strings;

class Sanitize extends \Tipui\Builtin\Libs\DataValidation
{

	/**
	* Holds the parameters
	*/
	private $parameters;

	/**
	* Holds current parameter name
	*/
	private $current_name;

	/**
	* Holds the results
	*/
	private $rs;

	/**
	* Holds array indexes for ArrayBaseTree method
	*/
	private $value_array_tree;
	private $value_array_keys;

	/**
	* Sanitizes single parameter
	* @see: Start.php file
	*/
	public function Exec()
	{

		/**
		* The required method, defined on method Form() of model class.
		*/
		$method = Form::GetMethod();
		//echo $method;

		/**
		* Gets parameters from cached session
		*/
		$routing = \Tipui\Core::GetConf() -> GetMethodDataCache( 'Routing' );

		/**
		* Requested method must be equal to request method defined on model Form() method.
		* If $method is false, then, allows any method.
		*/
		if( $method !== false and $routing['method'] != $method )
		{
			return false;
		}

		/**
		* The parameters defined on method Form() of model class.
		*/
		$this -> parameters = Form::GetElement();
		//print_r( $this -> parameters ); exit;

		/**
		* Handles values.
		*/
		$this -> rs[DataRules::VALUE] = array();

		/**
		* Handles errors.
		*/
		$this -> rs['error'] = array();

		if( !empty( $this -> parameters ) )
		{

			/**
			* Debug purposes
			*/
			//print_r( $this -> parameters ); exit;

			/**
			* Debug purposes
			*/
			//var_dump( $routing ); exit;
			//print_r( $routing ); exit;

			/**
			* Counter, mainly used for identify friendly url parameters
			*/
			$i = 0;

			/**
			* Iterates the array of registered parameters.
			*/
			foreach( $this -> parameters as $k => $v )
			{

				/**
				* For mod_rewrite, the array indexes as numeric
				* $i holds the counter that represents the numeric indexes
				* $k holds the name of parameters
				*/
				$idx = ( $routing['mode_rewrite'] === true ) ? $i++ : $k;

				/**
				* Hold the current parameter name.
				* Is necessary to avoid some "spaghetti codes" with ArrayBaseTree and BasicFilters
				*/
				$this -> current_name = $k;

				/**
				* Debug purposes
				*/
				/*
				if( $k == 'foo' )
				{
					var_dump( $_FILES ); exit;
					var_dump( $routing['params'] ); exit;
				}
				*/


				/**
				* Check uploaded files
				*/
				if( $v[DataRules::TYPE] == 'file' )
				{

					/**
					* Check uploaded files
					* The variable $k is used because multipart form data always is sent from POST method.
					* Avoid use the variable $idx, that is used for GET method, especially for URL Rewrite.
					*/
					if( isset( $_FILES[$k] ) && is_array( $_FILES[$k] ) && isset( $_FILES[$k]['name'] ) )
					{
						$v[DataRules::VALUE] = $_FILES[$k];

						/**
						* Builds the filter class name
						*/
						//$clss = \Tipui\Builtin\Libs\DataValidation\Filters\Upload;

						/**
						* Calling the filter (validation rule parameter)
						*/
						$filter = \Tipui\Builtin\Libs\DataValidation\Filters\Upload::Exec( $v );

						/**
						* Check if filter returned error property
						*/
						if( isset( $filter -> error ) )
						{
							//$this -> rs['error'][$k][DataRules::VALIDATION] = $filter -> error;
							$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::VALIDATION => $filter -> error ) );
						}

						/**
						* Setting the parameter value with filtered/sanitized data.
						*/
						//print_r( $filter -> rs ); exit;
						Form::SetElementRule( $k, DataRules::VALUE, $filter -> rs );

					}else{
						if( $v[DataRules::REQUIRED] === true )
						{
							/**
							* Error! The parameter is required!
							*/
							$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::REQUIRED => true ) );
						}
					}

					/**
					* Debug purposes
					*/
					//print_r( $this -> rs ); exit;

				}else if( isset( $routing['params'][$idx] ) ){

					/**
					* Check if received parameter exists from requested parameters (url, cli), except uploaded files ($_FILES)
					*/

					$this -> rs[DataRules::VALUE][$k] = $routing['params'][$idx];

					/**
					* Debug purposes
					*/
					/*
					if( $k == 'foo' )
					{
						echo __FILE__ . ':' . __LINE__ . PHP_EOL;
						var_dump( empty( $this -> rs ) ); exit;
					}
					*/

					/**
					* If not empty os required on rules, must go to validations.
					*/
					if( $v[DataRules::TYPE] != 'file' && ( !empty( $this -> rs[DataRules::VALUE][$k] ) or $v[DataRules::REQUIRED] === true ) )
					{
						/**
						* Check if is required an exact value
						* EXACT_VALUE have high precedence about OPTIONS
						*/
						if( !is_array( $this -> rs[DataRules::VALUE][$k] ) && isset( $v[DataRules::EXACT_VALUE] ) )
						{

							if( $v[DataRules::EXACT_VALUE] != $this -> rs[DataRules::VALUE][$k] )
							{
								//$this -> rs['error'][$k][DataRules::EXACT_VALUE] = true;
								$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::EXACT_VALUE => true ) );
							}

						}else if( isset( $v[DataRules::OPTIONS] ) ){

							/**
							* Option not found
							* Triggering error
							*/
							if( !$this -> CheckOptions( $k, $v[DataRules::OPTIONS] ) )
							{
								$rs['error'][$k] = $this -> SetError( $k, array( DataRules::OPTIONS => true ) );
							}else{

								/**
								* Check if have rules for options quantities
								*/
								$options_length = count( $this -> rs[DataRules::VALUE][$k] );

								/**
								* Check for exact quantity of selected options
								*/
								if( isset( $this -> parameters[$k][DataRules::SELECT_EXACT] ) )
								{
									if( $this -> parameters[$k][DataRules::SELECT_EXACT] != $options_length )
									{
										$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::SELECT_EXACT => true ) );
									}
								}

								/**
								* Check for minimum quantity of selected options
								*/
								if( !isset( $this -> rs['error'][$k] ) && isset( $this -> parameters[$k][DataRules::SELECT_MIN] ) )
								{
									if( $this -> parameters[$k][DataRules::SELECT_MIN] > $options_length )
									{
										$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::SELECT_MIN => true ) );
									}
								}

								/**
								* Check for maximum quantity of selected options
								*/
								if( !isset( $this -> rs['error'][$k] ) && isset( $this -> parameters[$k][DataRules::SELECT_MAX] ) )
								{
									if( $this -> parameters[$k][DataRules::SELECT_MAX] < $options_length )
									{
										$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::SELECT_MAX => true ) );
									}
								}

							}

							/**
							* Debug purposes
							*/
							//print_r( $v[DataRules::OPTIONS] ); exit;


						}else{

							/**
							* If value is an array, must convert with self::ArrayBaseTree.
							*/
							if( is_array( $this -> rs[DataRules::VALUE][$k] ) )
							{
								/**
								* note: BasicFilters is called into ArrayBaseTree
								*/
								$this -> ArrayBaseTree( $this -> rs[DataRules::VALUE][$k] );

								/**
								* In self::BasicFilters, $this -> rs[DataRules::VALUE][$k] must be string.
								* In this point, is array, therefore is necessary to convert temporarily to string.
								*/
								//$this -> rs[DataRules::VALUE][$k] = current( $this -> value_array_keys );

								if( !empty( $this -> value_array_keys ) )
								{
									/**
									* Replacing with
									*/
									//$this -> value_array_keys[key( $this -> value_array_keys )] = $this -> rs[DataRules::VALUE][$k];

									/**
									* Reconverting to self::ArrayBaseTree mode.
									*/
									$this -> rs[DataRules::VALUE][$k] = $this -> value_array_keys;

									/**
									* Must be reseted to avoid conflicts with others parameters.
									*/
									$this -> value_array_keys = null;
								}

							}else{
								/**
								* Aplying basic filters
								* The value must be string
								* [review] do not allow select, radio and checkbox (static values)
								*/
								$this -> rs[DataRules::VALUE][$k] = $this -> BasicFilters( $k, $this -> rs[DataRules::VALUE][$k] );
							}
							

							/**
							* Debug purposes
							*/
							//print_r( $this -> rs[DataRules::VALUE][$k] ); exit;

						}

					}

					/**
					* Setting the parameter value with filtered/sanitized data.
					*/
					Form::SetElementRule( $k, DataRules::VALUE, $this -> rs[DataRules::VALUE][$k] );

				}else if( $v[DataRules::TYPE] != 'file' ){

					/**
					* Clearing the default values to display the exact value received from the user input
					*/
					Form::SetElementRule( $k, DataRules::DEFAULTS, null );

					if( $v[DataRules::REQUIRED] === true )
					{
						/**
						* Error! The parameter is required!
						*/
						//var_dump($this -> rs[DataRules::VALUE][$k]); exit;
						$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::REQUIRED => true ) );
					}

				}

				/**
				* Debug purposes
				* Setting some other rule parameter
				*/
				//Form::SetElementRule( $k, 'default', $this -> rs[DataRules::VALUE][$k] );

				/**
				* Clearing used variable
				*/
				unset( $idx );
			}

			/**
			* Resetting "current_name" property
			*/
			$this -> current_name = null;

			/**
			* Debug purposes
			*/
			//print_r( $this -> rs ); exit;
			//exit;
		}

		return $this -> rs;
    }


	/**
	* Log the errors to array and set to rules
	* @see: \Tipui\Builtin\Libs\Form::SetElementRule()
	*/
	private function SetError( $name, $error )
	{
		/**
		* Setting the parameter value with filtered/sanitized data.
		*/
		Form::SetElementRule( $name, DataRules::ERROR, $error );

		return $error;
	}


	/**
	* Basic filters for strings
	* @param $k is the name
	*/
	private function BasicFilters( $k, $v )
	{

		/**
		* Pre-filtering if required.
		*/
		if( isset( $this -> parameters[$k][DataRules::PRE_FILTER] ) )
		{
			/**
			* Passing through PreFilter Class
			*/
			$v = $this -> PreFilter( $this -> parameters[$k][DataRules::PRE_FILTER], $v );
		}

		/**
		* Check the minimum length
		*/
		$value_length = Strings::StrLen( $v );

		/**
		* Check the exact length
		*/
		if( isset( $this -> parameters[$k][DataRules::EXACT_LENGTH] ) )
		{
			if( $this -> parameters[$k][DataRules::EXACT_LENGTH] != $value_length )
			{
				//$this -> rs['error'][$k][DataRules::EXACT_LENGTH] = true;
				$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::EXACT_LENGTH => true ) );
				return $v;
			}
		}

		/**
		* Check the minimum length
		*/
		if( isset( $this -> parameters[$k][DataRules::MIN_LENGTH] ) )
		{
			if( $this -> parameters[$k][DataRules::MIN_LENGTH] > $value_length )
			{
				//$this -> rs['error'][$k][DataRules::MIN_LENGTH] = true;
				$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::MIN_LENGTH => true ) );
				return $v;
			}
		}

		/**
		* Check the maximum length
		*/
		if( isset( $this -> parameters[$k][DataRules::MAX_LENGTH] ) )
		{
			if( $this -> parameters[$k][DataRules::MAX_LENGTH] < $value_length )
			{
				//$this -> rs['error'][$k][DataRules::MAX_LENGTH] = true;
				$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::MAX_LENGTH => true ) );
				return $v;
			}
		}

		/**
		* Clearing used variable
		*/
		unset( $value_length );



		/**
		* Optional validation filter
		* (number, int, float, date, datetime, email)
		* Allows override and customized filters under app folders
		*/
		if( isset( $this -> parameters[$k][DataRules::VALIDATION] ) )
		{

			/**
			* Builds the filter class name
			*/
			$clss = __NAMESPACE__ . '\Filters\\' . ucfirst( $this -> parameters[$k][DataRules::VALIDATION] );

			/**
			* Calling the filter (validation rule parameter)
			*/
			$filter = $clss::Exec( $v );

			/**
			* Check if filter returned error property
			*/
			if( isset( $filter -> error ) )
			{
				//$this -> rs['error'][$k][DataRules::VALIDATION] = $filter -> error;
				$this -> rs['error'][$k] = $this -> SetError( $k, array( DataRules::VALIDATION => $filter -> error ) );
			}

			/**
			* Returns the filtered result
			*/
			return $filter -> str;

		}

		return $v;
	}



	private function CheckOptions( $k, $options )
	{

		if( is_array( $this -> rs[DataRules::VALUE][$k] ) )
		{

			/**
			* Value is an array, so, must be converted to ArrayBaseTree format.
			* @see \Builtin\Helpers\HTML\Form::SetNameAsArray()
			*/
			$this -> ArrayBaseTree( $this -> rs[DataRules::VALUE][$k] );
			$this -> rs[DataRules::VALUE][$k] = $this -> value_array_keys;

			/**
			* Must be reseted to avoid conflicts with others parameters.
			*/
			$this -> value_array_keys = null;

		}

		/**
		* Identifies if option was found (For parameters with options / multiple values)
		*/
		$option_found = false;

		/**
		* Validates from the options array
		* The array index must exists
		*/
		if( !empty( $options ) )
		{
			//print_r( $options ); exit;
			foreach( $options as $k1 => $v1 )
			{
				/**
				* If OPTIONS array key is array(), is an OPTGROUP
				*/
				if( is_array( $v1 ) && !empty( $v1 ) )
				{

					/**
					* Debug purposes
					*/
					//print_r( key( $v1 ) ); exit;
					//print_r( $v1[key( $v1 )][1] ); exit;
					//print_r( $v1[key( $v1 )][$this -> rs[DataRules::VALUE][$k]] ); exit;

					/**
					* Value was found!
					* [review] must check status for value not found. flag
					*/
					if( isset( $v1[key( $v1 )][$this -> rs[DataRules::VALUE][$k]] ) )
					{
						/**
						* An exact value is expected.
						* Don't need to proceed with others filters.
						*/
						$option_found = true;
						break;
					}
				}else{

					/**
					* [review]
					* An exact value is expected.
					* Don't need to proceed with others filters, but must verify the select min, max and exact
					* Note: The "exact value" is different of DataRules::EXACT_VALUE. 
					* In this case, the exact value is obtained from options indexes.
					*/
					if( $this -> rs[DataRules::VALUE][$k] == $k1 )
					{
						/**
						* For value as string
						*/
						$option_found = true;
						break;

					}else if( is_array( $this -> rs[DataRules::VALUE][$k] ) && in_array( $k1, $this -> rs[DataRules::VALUE][$k] ) ){

						/**
						* For value as array, after been converted with ArrayBaseTree()
						*/
						$option_found = true;
						break;

					}
				}
				
			}

		}else{
			throw new \Exception('Options must be array and cannot be empty. Check the element rules.');
		}

		return $option_found;

	}



	/**
	* Converts multidimentional array keys into strings
	* Example, the array below:
	*
	array(
		'index1'=>array(
					'index2'=>array(
								1 => 'a',
								2 => 'b',
								3 => 'c',
							)
				)
		);
	*
	* will be converted to:
	*
	Array
	(
		[index1/index2/1] => a
		[index1/index2/2] => b
		[index1/index2/3] => c
	)

	*/
	private function ArrayBaseTree( $array, $index = 0 )
	{

		if( is_array( $array ) )
		{
			$index++;
			foreach( $array as $k => $v )
			{
				//$this -> value_array_tree[$index] .= '[' . $index . ' ' . count( $this -> value_array_tree ) . ']';  // for debug purposes
				$this -> value_array_tree[$index] = $k;
				if( !is_array( $v ) )
				{
					$id = ( $index + 1 );
					$this -> value_array_tree[$id]  = '';
					//$this -> value_array_tree[$id] .= '[' . $index . ' ' . count( $this -> value_array_tree ) . ']';  // for debug purposes

					//$this -> value_array_tree[$id] .= $v; // may use validation filter here
					$this -> value_array_tree[$id] .= $this -> BasicFilters( $this -> current_name, $v );

					$j = count( $this -> value_array_tree );
					if( $id < $j )
					{
						while( $j > $id )
						{
							unset( $this -> value_array_tree[$j] );
							$j--;
						}
					}
				}
				$this -> ArrayBaseTree( $v, $index );
			}
			//echo $index . '<br>'; // for debug purposes
		}else{

			 $val = array_pop( $this -> value_array_tree );
			 $this -> value_array_keys[ implode( '/', $this -> value_array_tree ) ] = $val;
			 //$this -> value_array_tree = null;

		}
	}

}