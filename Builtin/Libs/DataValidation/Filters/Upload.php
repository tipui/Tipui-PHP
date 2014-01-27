<?php

/**
* @class  Upload
* @file   Upload.php
* @brief  Upload Builtin DataValidation Filters functions.
* @date   2014-01-05 02:35:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-23 23:06:00
*/

namespace Tipui\Builtin\Libs\DataValidation\Filters;

use Tipui\Builtin\Libs\DataRules as DataRules;

class Upload
{

	/**
	* Validates the uploaded file
	*/
	public function Exec( $data )
	{
		$r = new \stdClass();

		/**
		* Debug purposes
		*/
		//print_r( $data ); exit;

		/**
		* Check the upload error code
		*/
		if( $data[DataRules::VALUE]['error'] > 0 )
		{
			$r -> error = 'upload_error_code:' . $data[DataRules::VALUE]['error'];
		}else{

			/**
			* Check if content-type is permitted
			*/
			if( !in_array( $data[DataRules::VALUE]['type'], $data['content_types'] ) )
			{
				$r -> error = DataRules::CONTENT_TYPES;
			}
		
			if( !isset( $r -> error ) )
			{
				/**
				* Check file size limits
				*/
				if( $data[DataRules::MAX_SIZE] < $data[DataRules::VALUE]['size'] )
				{
					/**
					* Maximum size limit was exceeded
					*/
					$r -> error = DataRules::MAX_SIZE;
				}else{
					if( $data[DataRules::MIN_SIZE] > $data[DataRules::VALUE]['size'] )
					{
						/**
						* Minimum size expected
						*/
						$r -> error = DataRules::MIN_SIZE;
					}
				}
			}

			if( !isset( $r -> error ) )
			{

				/**
				* For images only
				*/
				if( isset( $data[DataRules::EXACT_HEIGHT] ) || isset( $data[DataRules::EXACT_WIDTH] ) || isset( $data[DataRules::MAX_WIDTH] ) || isset( $data[DataRules::MIN_WIDTH] ) || isset( $data[DataRules::MAX_HEIGHT] ) || isset( $data[DataRules::MIN_HEIGHT] ) )
				{

					$arr = getimagesize( $data[DataRules::VALUE]['tmp_name'] );
					$data[DataRules::VALUE]['width']  = $arr[0];
					$data[DataRules::VALUE]['height'] = $arr[1];

					if( isset( $data[DataRules::EXACT_HEIGHT] ) && $data[DataRules::EXACT_HEIGHT] <> $arr[1] )
					{
						/**
						* Must have exact height
						*/
						$r -> error = DataRules::EXACT_HEIGHT;
					}
					if( !isset( $r -> error ) && isset( $data[DataRules::EXACT_WIDTH] ) && $data[DataRules::EXACT_WIDTH] <> $arr[0] )
					{					
						/**
						* Must have exact width
						*/	
						$r -> error = DataRules::EXACT_WIDTH;
					}
					if( !isset( $r -> error ) && isset( $data[DataRules::MIN_WIDTH] ) && $data[DataRules::MIN_WIDTH] > $arr[0] )
					{
						/**
						* Minimum width expected
						*/
						$r -> error = DataRules::MIN_WIDTH;
					}
					if( !isset( $r -> error ) && isset( $data[DataRules::MAX_WIDTH] ) && $data[DataRules::MAX_WIDTH] < $arr[0] )
					{
						/**
						* Maximum width exceeded
						*/
						$r -> error = DataRules::MAX_WIDTH;
					}
					if( !isset( $r -> error ) && isset( $data[DataRules::MIN_HEIGHT] ) && $data[DataRules::MIN_HEIGHT] > $arr[1] )
					{
						/**
						* Minimum height expected
						*/
						$r -> error = DataRules::MIN_HEIGHT;
					}
					if( !isset( $r -> error ) && isset( $data[DataRules::MAX_HEIGHT] ) && $data[DataRules::MAX_HEIGHT] < $arr[1] )
					{
						/**
						* Maximum height exceeded
						*/
						$r -> error = DataRules::MAX_HEIGHT;
					}
					unset( $arr );
				}
			}
		}

		$r -> rs = $data[DataRules::VALUE];

		return $r;

    }

}