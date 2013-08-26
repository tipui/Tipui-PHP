<?php
/** Images Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-03-31 16:20:00 - Daniel Omine
 *
 *   Methods
		Reset
		resizeIntoFrame
		createFrom
		setInfo
		getInfo
*/

class Images
{

	private $img;
	private $imgInfo;

	public function Reset( )
	{
		$this -> imgInfo = null;
		$this -> img     = null;
	}

	public function resizeIntoFrame( $image, $frame_width, $frame_height, $path_to_save, $quality = 100, $mode = 0777 )
	{

		$this -> setInfo( $image );
		$this -> createFrom( $image );

		$frame = imageCreateTrueColor( $frame_width, $frame_height ); 
		$preto = imagecolorallocate( $frame, 0, 0, 0 );

		if( ( $frame_width >= $this -> imgInfo[0] ) and ( $frame_height >= $this -> imgInfo[1] ) )
		{
			$w     = $this -> imgInfo[0];
			$h     = $this -> imgInfo[1];
			$pos_x = intval( ( ( $frame_width / 2 ) - ( $this -> imgInfo[0] / 2 ) ) );
			$pos_y = intval( ( ( $frame_height / 2 ) - ( $this -> imgInfo[1] / 2 ) ) );
		 	imagecopyresized( $frame, $this -> img, $pos_x, $pos_y, 0, 0, $this -> imgInfo[0], $this -> imgInfo[1], $w, $h );
		}else{
			if( $this -> imgInfo[0] > $this -> imgInfo[1] ){
				$w     = $frame_width;
				$pos_x = 0;
				$h     = floor( $frame_width * $this -> imgInfo[1]/$this -> imgInfo[0] );
				$pos_y = round( ( $frame_height / 2 )-( $h / 2 ) );
			}else{
				$h     = $frame_height;
				$pos_y = 0;
				$w     = floor( $frame_width * $this -> imgInfo[0]/$this -> imgInfo[1] );
				$pos_x = round( ( $frame_height / 2 ) - ( $w / 2 ) );
			}
			ImageCopyResized( $frame, $this -> img, $pos_x, $pos_y, 0, 0, $w, $h, $this -> imgInfo[0], $this -> imgInfo[1] );
		}

	 	imagejpeg( $frame, $path_to_save, $quality );
		imagedestroy( $frame );
		imagedestroy( $this -> img );

		FileSystem::setCHMOD( $path_to_save, $mode );

		/*
			$img = new Images;
			$img -> resizeIntoFrame( '/path/to/foo.jpg', 500, 500, '/path/to/foo_bigger.jpg' );
		*/

	}

	private function createFrom( $image )
	{
		//http://www.php.net/manual/en/function.image-type-to-mime-type.php
		
		if( empty( $this -> imgInfo ) or !isset( $this -> imgInfo[2] ) )
		{
			$this -> setInfo( $image );
		}

		switch( $this -> imgInfo[2] )
		{
			case IMAGETYPE_JPEG:
				$this -> img = imagecreatefromjpeg( $image );
			break;
			case IMAGETYPE_PNG:
				$this -> img = imagecreatefrompng( $image );
			break;
			case IMAGETYPE_GIF:
				$this -> img = imagecreatefromgif( $image );
			break;
		}
	}

	private function setInfo( $path )
	{
		$this -> imgInfo = getimagesize( $path );
	}

	public function getInfo( $path )
	{

		if( empty( $this -> imgInfo ) or !isset( $this -> imgInfo[2] ) )
		{
			$this -> setInfo( $path );
		}

		return $this -> imgInfo;

	}

}
?>