<?php

   # ========================================================================#
   #
   #  Author:    Jarrod Oberto
   #  Version:	 1.0
   #  Date:      17-Jan-10
   #  Purpose:   Resizes and saves image
   #  Requires : Requires PHP5, GD library.
   #  Usage Example:
   #                     include("classes/resize_class.php");
   #                     $resizeObj = new resize('images/cars/large/input.jpg');
   #                     $resizeObj -> resizeImage(150, 100, 0);
   #                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
   #
   #
   # ========================================================================#


		Class resizeImg
		{
			// *** Class variables
			private $image;
		   private $width;
		   private $height;
			private $imageResized;
			private $filepath;
			private $max_height = 600;
    		private $max_width = 600;
    		private const IMAGE_FLIP_HORIZONTAL = 1;
			private const IMAGE_FLIP_VERTICAL = 2;
			private const IMAGE_FLIP_BOTH = 3;

			function __construct($fileName, $max_height = 600, $max_width = 600)
			{
				// *** Open up the file
				$this->filepath = $fileName;
				$this->image = $this->openImage($fileName);

				if (!empty($max_height)) {
					$this->max_height = $max_height;
				}
				if (!empty($max_width)) {
					$this->max_width = $max_width;
				}

			   // *** Get width and height
			   @$this->width  = imagesx($this->image);
			   @$this->height = imagesy($this->image);

			}

			public function getWith()
			{
				return $this->width;
			}

			public function getHeight()
			{
				return $this->height;
			}

			public function needResize($overwrite = true, $newPath = null)
			{
				$w = $this->getWith();
				$h = $this->getHeight();
				if ($w > $this->max_width || $h > $this->max_height)
				{
					$this->resizeImage($this->max_width, $this->max_height, "auto");

					if ($overwrite){
						$newPath = null;
					}

					$this->reOrientation();
					$this->saveImage($newPath, "100");
				}
			}

			## --------------------------------------------------------

			private function openImage($file)
			{
				// *** Get extension
				$extension = strtolower(strrchr($file, '.'));

				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						$img = @imagecreatefromjpeg($file);
						break;
					case '.gif':
						$img = @imagecreatefromgif($file);
						break;
					case '.png':
						$img = @imagecreatefrompng($file);
						break;
					default:
						$img = false;
						break;
				}
				return $img;
			}

			## --------------------------------------------------------

			public function resizeImage($newWidth, $newHeight, $option="auto")
			{
				// *** Get optimal width and height - based on $option
				$optionArray = $this->getDimensions($newWidth, $newHeight, $option);

				$optimalWidth  = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];

				// *** Resample - create image canvas of x, y size
				$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
				imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);


				// *** if option is 'crop', then crop too
				if ($option == 'crop') {
					$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
				}
			}

			## --------------------------------------------------------
			
			private function getDimensions($newWidth, $newHeight, $option)
			{

			   switch ($option)
				{
					case 'exact':
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
						break;
					case 'portrait':
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
						break;
					case 'landscape':
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
						break;
					case 'auto':
						$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
					case 'crop':
						$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
				}
				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getSizeByFixedHeight($newHeight)
			{
				$ratio = $this->width / $this->height;
				$newWidth = $newHeight * $ratio;
				return $newWidth;
			}

			private function getSizeByFixedWidth($newWidth)
			{
				$ratio = $this->height / $this->width;
				$newHeight = $newWidth * $ratio;
				return $newHeight;
			}

			private function getSizeByAuto($newWidth, $newHeight)
			{
				if ($this->height < $this->width)
				// *** Image to be resized is wider (landscape)
				{
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				}
				elseif ($this->height > $this->width)
				// *** Image to be resized is taller (portrait)
				{
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
				}
				else
				// *** Image to be resizerd is a square
				{
					if ($newHeight < $newWidth) {
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
					} else if ($newHeight > $newWidth) {
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
					} else {
						// *** Sqaure being resized to a square
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
					}
				}

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getOptimalCrop($newWidth, $newHeight)
			{

				$heightRatio = $this->height / $newHeight;
				$widthRatio  = $this->width /  $newWidth;

				if ($heightRatio < $widthRatio) {
					$optimalRatio = $heightRatio;
				} else {
					$optimalRatio = $widthRatio;
				}

				$optimalHeight = $this->height / $optimalRatio;
				$optimalWidth  = $this->width  / $optimalRatio;

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
			{
				// *** Find center - this will be used for the crop
				$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
				$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

				$crop = $this->imageResized;
				//imagedestroy($this->imageResized);

				// *** Now crop from center to exact requested size
				$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
				imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
			}

			## --------------------------------------------------------

			public function saveImage($savePath = null, $imageQuality="100")
			{
				if (empty($savePath)) {
					$savePath = $this->filepath;
				}

				// *** Get extension
        		$extension = strtolower(strrchr($savePath, '.'));

				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						if (imagetypes() & IMG_JPG) {
							imagejpeg($this->imageResized, $savePath, $imageQuality);
						}
						break;

					case '.gif':
						if (imagetypes() & IMG_GIF) {
							imagegif($this->imageResized, $savePath);
						}
						break;

					case '.png':
						// *** Scale quality from 0-100 to 0-9
						$scaleQuality = round(($imageQuality/100) * 9);

						// *** Invert quality setting as 0 is best, not 9
						$invertScaleQuality = 9 - $scaleQuality;

						if (imagetypes() & IMG_PNG) {
							 imagepng($this->imageResized, $savePath, $invertScaleQuality);
						}
						break;

					// ... etc

					default:
						// *** No extension - No save.
						break;
				}

				imagedestroy($this->imageResized);
			}

			## --------------------------------------------------------

			public function reOrientation()
			{
				$exif = @exif_read_data($this->filepath);

				if (!$exif) {
					return;
				}

				$ort = null;
				foreach ($exif as $key => $section) {
					if (!is_array($section)) {
						if ($key == "Orientation") {
							$ort = $section;
							break;
						}
					   // echo "$key: $section<br />\n";
					} else {
						foreach ($section as $name => $val) {
					      if ($name == "Orientation") {
								$ort = $section;
								break;
							}
					      // echo "$key.$name: $val<br />\n";
					   }
					}
				}

				if (is_null($ort)) {
					return;
				}

				switch($ort)
				{
					case 1: // nothing
						break;

					case 2: // horizontal flip
						$this->flipImage(1);
						break;

					case 3: // 180 rotate left
						$this->rotateImage(180);
						break;

					case 4: // vertical flip
						$this->flipImage(2);
						break;

					case 5: // vertical flip + 90 rotate right
						$this->flipImage(2);
						$this->rotateImage(-90);
						break;

					case 6: // 90 rotate right
						$this->rotateImage(-90);
						break;

					case 7: // horizontal flip + 90 rotate right
						$this->flipImage(1);   
						$this->rotateImage(-90);
						break;

					case 8:    // 90 rotate left
						$this->rotateImage(90);
						break;
				}
			}

			public function rotateImage($angle)
			{
				$this->imageResized = imagerotate($this->imageResized, $angle, 0);
			}

			public function flipImage($mode)
			{
				$imgsrc = $this->imageResized;

				$width                        =    imagesx ( $imgsrc );
				$height                       =    imagesy ( $imgsrc );

				$src_x                        =    0;
				$src_y                        =    0;
				$src_width                    =    $width;
				$src_height                   =    $height;

				switch ( (int) $mode )
				{

					case self::IMAGE_FLIP_HORIZONTAL:
						$src_y                =    $height;
						$src_height           =    -$height;
						break;

					case self::IMAGE_FLIP_VERTICAL:
						$src_x                =    $width;
						$src_width            =    -$width;
						break;

					case self::IMAGE_FLIP_BOTH:
						$src_x                =    $width;
						$src_y                =    $height;
						$src_width            =    -$width;
						$src_height           =    -$height;
						break;

					default:
						return $imgsrc;

				}

				$this->imageResized = imagecreatetruecolor ( $width, $height );

				if ( imagecopyresampled ( $this->imageResized, $imgsrc, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height ) )
				{
					return $this->imageResized;
				}

				return $this->imageResized;
			}

		}
