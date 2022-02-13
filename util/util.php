<?php
//include '../config/main_config.php';

class UtilClass
{
	private $db = null;
	private function contoDB()
	{
		$this->db = mysqli_connect('localhost', 'root', '', 'unetdb');
        if ($this->db->connect_error) {            
            return 'nice day...';
        }
        mysqli_set_charset($this->db, "utf8");
	}
	public function haveAcces($appname,$userid)
	{		
		$acs = false;		
		$this->contoDB();
		$result = mysqli_query($this->db, "CALL sp_have_acces_app('$appname',$userid)");			
		if($result)
		{
			if (mysqli_num_rows($result) == 1) 
			{
				$row = mysqli_fetch_assoc($result);
				if($row['htaccess'] == 1) $acs = true;
			}		
			mysqli_close($this->db);
		}
		return $acs;
	}
	
    public function hashuser($str = null)
    {
        if (session_status() == PHP_SESSION_NONE) 
		{
            session_start();
        }
        if (is_null($str)) {
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['agent'] = $_SERVER['HTTP_USER_AGENT'];
            $str = $_SESSION["userid"] . $_SESSION["username"] . $_SESSION['ip'] . $_SESSION['agent'];
        }
        for ($i = 0; $i < 61; $i++) {
            $str = md5($str);
        }
        return $str;
    }

    function format_phone($string)
    {
        $position = strlen($string);
        $insert = " ";
        if ($position >= 5 && $position < 8) {
            return substr_replace($string, $insert, $position - 4, 0);
        } else if ($position >= 8) {
            $str = substr_replace($string, $insert, $position - 4, 0);
            return substr_replace($str, $insert, $position - 7, 0);
        } else
            return $string;
    }
}

?>