<?php
include 'setuser-config.php';

class SetUserAjaxManager
{
    private $dbcon = null;
    private $func;

    private function connect()
    {
        $this->dbcon = mysqli_connect(SetUSERConfigClass::$dbserver, SetUSERConfigClass::$user, SetUSERConfigClass::$pass, SetUSERConfigClass::$dbname);
        // Check connection
        if ($this->dbcon->connect_error) {
            return mysqli_error($this->dbcon);
        }
        mysqli_set_charset($this->dbcon, "utf8");
    }

    private function close_connect()
    {
        mysqli_close($this->dbcon);
    }

    public function ProcReq()
    {
        if ($this->CheckFuncExist()) {
            switch ($this->func) {
                case 'reset_pass';
					$oldpass = trim($_POST['op']);
					$newpass = trim($_POST['np']);
					session_start();
                    echo $this->resetpass($_SESSION["userid"],$oldpass,$newpass);
                    break;                
            }
        }
    }
	
	private function resetpass($uid,$op,$np)
	{
		$this->connect();
		$op = mysqli_real_escape_string($this->dbcon,addslashes($op));
		$np = mysqli_real_escape_string($this->dbcon,addslashes($np));
		$ret = 'false';
		$sql = "Call sp_change_pass($uid,'$op','$np');";
		$res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = 'true';
        }		
        $this->close_connect();
		return $ret;
	}

    private function CheckFuncExist()
    {
        if (!isset($_POST['func'])) {
            return false;
        }
        $this->func = trim($_POST['func']);
        return true;
    }
}