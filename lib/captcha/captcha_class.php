<?php
class confirm_image
{
	private $showLine = true;
	private $applyWave = true;
	private $winHeight = 50;
	private $winWidth = 140;
	
	private $Characters; // random characters
	
	private $Colors =  array (	'0' => '145',
								'1' => '204',
								'2' => '177',
								'3' => '184',
								'4' => '199',
								'5' => '255');

////////////////////////////////////////////////////////////////////////////////
	public function __construct($ConfirmCode)
	{
		$this -> Characters = $ConfirmCode;
	}

////////////////////////////////////////////////////////////////////////////////
	public function ShowImage()
	{
		//detect server operation system
		if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )	//windows detected
			$this -> win();
		else	//linux detected
			$this -> linux();
	}

////////////////////////////////////////////////////////////////////////////////
	private function win()
	{
		////////////////////////////////////
		//background image
		$image = imagecreatetruecolor($this -> winWidth, $this -> winHeight) 
				or die("<b>" . __FILE__ . "</b><br />" . __LINE__ . " : " ."Cannot Initialize new GD image stream");
		$bg = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 10, 10, $bg);

		for ($x=0; $x < $this -> winWidth; $x++)
		{
			for ($y=0; $y < $this -> winHeight; $y++)
			{
				$random = mt_rand(0 , 5);
				$temp_color = imagecolorallocate($image, $this -> Colors["$random"], $this -> Colors["$random"], $this -> Colors["$random"]);
				imagesetpixel( $image, $x, $y , $temp_color );
			}
		}

		$char_color = imagecolorallocatealpha($image, 0, 0, 0, 60);

		//Font
		$font = "tahomabd.ttf";
		$font_size = 33;
		////////////////////////////////////
		//Image characters

		$char = "";

		$char = $this -> Characters[0];
		$random_x =2;;// mt_rand(10 , 20);
		$random_y = mt_rand(35 , 45);
		$random_angle = mt_rand(-20 , 20);
		imagettftext($image, $font_size, $random_angle, $random_x, $random_y, $char_color, $font, $char);
		


		$char = $this -> Characters[1];
		$random_x += 35;//mt_rand(50 , 70);
		$random_y = mt_rand(35 , 45);
		$random_angle = mt_rand(-20 , 20);
		imagettftext($image, $font_size, $random_angle, $random_x, $random_y, $char_color, $font, $char);



		$char = $this -> Characters[2];
		$random_x += 35;//mt_rand(100 , 120);
		$random_y = mt_rand(35 , 45);
		$random_angle = mt_rand(-20 , 20);
		imagettftext($image, $font_size, $random_angle, $random_x, $random_y, $char_color, $font, $char);


		$char = $this -> Characters[3];
		$random_x += 35;//mt_rand(150 , 170);
		$random_y = mt_rand(35 , 45);
		$random_angle = mt_rand(-20 , 20);
		imagettftext($image, $font_size, $random_angle, $random_x, $random_y, $char_color, $font, $char);

/*
		$char = $this -> Characters[4];
		$random_x += 35;//mt_rand(200 , 220);
		$random_y = mt_rand(35 , 45);
		$random_angle = mt_rand(-20 , 20);
		imagettftext($image, $font_size, $random_angle, $random_x, $random_y, $char_color, $font, $char);


		$char = $this -> Characters[5];
		$random_x += 35;//mt_rand(250 , 270);
		$random_y = mt_rand(35 , 45);
		$random_angle = mt_rand(-20 , 20);
		imagettftext($image, $font_size, $random_angle, $random_x, $random_y, $char_color, $font, $char);
*/
		////////////////////////////////////
		if ($this -> applyWave)
			$image = $this -> apply_wave($image, $this -> winWidth, $this -> winHeight);
			
		////////////////////////////////////
		//lines
		if ($this -> showLine)
		{
			for ($i=0; $i<$this->winWidth; $i++ )
			{
				if ($i%10 == 0)
				{
					imageline ( $image, $i, 0, $i+10, 50, $char_color );
					imageline ( $image, $i, 0, $i-10, 50, $char_color );
				}
			}
		}
			
		////////////////////////////////////
		return imagepng($image);
		imagedestroy($image);
	}

////////////////////////////////////////////////////////////////////////////////
	private function linux()
	{
		////////////////////////////////////
		//Background image
		$image = imagecreatetruecolor(150, 50) or die("<b>" . __FILE__ . "</b><br />" . __LINE__ . " : " ."Cannot Initialize new GD image stream");
		$bg = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 10, 10, $bg);

		for ($x=0; $x < 150; $x++)
		{
			for ($y=0; $y < 50; $y++)
			{
				$random = mt_rand(0 , 5);
				$temp_color = imagecolorallocate($image, $this -> Colors["$random"], $this -> Colors["$random"], $this -> Colors["$random"]);
				imagesetpixel( $image, $x, $y , $temp_color );
			}
		}

		$char_color = imagecolorallocatealpha($image, 0, 0, 0, 60);

		////////////////////////////////////
		//Image Info
		$font = 5;

		////////////////////////////////////
		//Image characters
		$char = $this -> Characters[0];
		$random_x = mt_rand(10 , 20);
		$random_y = mt_rand(15,25);
		imagestring($image, $font, $random_x, $random_y, $char, $char_color);



		$char = $this -> Characters[1];
		$random_x = mt_rand(30 , 40);
		$random_y = mt_rand(15,25);
		imagestring($image, $font, $random_x, $random_y, $char, $char_color);



		$char = $this -> Characters[2];
		$random_x = mt_rand(50 , 60);
		$random_y = mt_rand(15,25);
		imagestring($image, $font, $random_x, $random_y, $char, $char_color);


		$char = $this -> Characters[3];
		$random_x = mt_rand(70 , 80);
		$random_y = mt_rand(15,25);
		imagestring($image, $font, $random_x, $random_y, $char, $char_color);

/*
		$char = $this -> Characters[4];
		$random_x = mt_rand(90 , 100);
		$random_y = mt_rand(15,25);
		imagestring($image, $font, $random_x, $random_y, $char, $char_color);


		$char = $this -> Characters[5];
		$random_x = mt_rand(110 , 120);
		$random_y = mt_rand(15,25);
		imagestring($image, $font, $random_x, $random_y, $char, $char_color);*/

		///////////////////////
		return imagepng($image);
		imagedestroy($image);
		imagedestroy($image);
	}

////////////////////////////////////////////////////////////////////////////////
	private function apply_wave($image, $width, $height)
	{		
		$x_period = 10;
		$y_period = 10;
		$y_amplitude = 5;
		$x_amplitude = 5;
		
		$xp = $x_period*rand(1,3);
		$k = rand(0,100);$k=0;
		for ($a = 0; $a<$width; $a++)
			imagecopy($image, $image, $a-1, 1, $a, 0, 1, $height);
			
		$yp = $y_period*rand(1,2);
		$k = rand(0,100);
		for ($a = 0; $a<$height; $a++)
			imagecopy($image, $image, 1, $a-1, 0, $a, $width, 1);
		
		return $image;
	}
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class random_char
{
	private $id;
	private $key_str;
	private $Code;//Orginal string code

////////////////////////////////////////////////////////////////////////////////

	public function __construct()
	{
		$this -> create();
	}

////////////////////////////////////////////////////////////////////////////////

	public function get_code()
	{
		return $this -> Code;
	}

////////////////////////////////////////////////////////////////////////////////

	private function create()
	{
		$string = "";
		$string = md5(rand(0, microtime() * 1000000));
		$this-> id = $this->Code   = substr($string, 3, 4);
		$this-> key_str = md5(rand(0, 999));
	}

////////////////////////////////////////////////////////////////////////////////

	private function get_rnd_iv($iv_len)
	{
	    $iv = '';
	    while ($iv_len-- > 0) {
	        $iv .= chr(mt_rand() &0xff);
	    }
	    return $iv;
	}

////////////////////////////////////////////////////////////////////////////////

	public function get_id()
	{

		return urlencode($this -> md5_encrypt($this->id, $this->key_str));
	}

////////////////////////////////////////////////////////////////////////////////

	public function get_key()
	{
		return $this -> key_str;
	}

////////////////////////////////////////////////////////////////////////////////
	//encrypt id 
	private function md5_encrypt($plain_text, $password, $iv_len = 16)
	{
	    $plain_text .= "x13";
	    $n = strlen($plain_text);
	    if ($n % 16) $plain_text .= str_repeat("0", 16 - ($n % 16));
	    $i = 0;
	    $enc_text = $this -> get_rnd_iv($iv_len);
	    $iv = substr($password ^ $enc_text, 0, 512);
	    while ($i < $n)
		{
	        $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
	        $enc_text .= $block;
	        $iv = substr($block . $iv, 0, 512) ^ $password;
	        $i += 16;
	    }
	    return base64_encode($enc_text);
	}

////////////////////////////////////////////////////////////////////////////////

	public function md5_decrypt($enc_text, $password, $iv_len = 16)
	{
	    $enc_text = base64_decode($enc_text);
	    $n = strlen($enc_text);
	    $i = $iv_len;
	    $plain_text = '';
	    $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
	    while ($i < $n)
		{
	        $block = substr($enc_text, $i, 16);
	        $plain_text .= $block ^ pack('H*', md5($iv));
	        $iv = substr($block . $iv, 0, 512) ^ $password;
	        $i += 16;
	    }
	    return preg_replace('/\\x13\\x00*$/', '', $plain_text);
	}
}

?>