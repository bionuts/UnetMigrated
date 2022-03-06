<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config/main_config.php');
include 'util/util.php';

class user_accounts_class
{
    private $conn;

    //Public Functions
    private function get_values()
    {

    }

    //connect to db
    private function connect()
    {
        $this->conn = mysqli_connect(MainConfigClass::$dbserver, MainConfigClass::$user, MainConfigClass::$pass, MainConfigClass::$dbname);
        // Check connection
        if ($this->conn->connect_error) {
            //database connection failed!
            return -1;
        }
        mysqli_set_charset($this->conn, "utf8");
        //return $conn;
    }

    //disconnect from DB
    private function close_connect()
    {
        mysqli_close($this->conn);
    }

		
    //Retrun :
    public function login($username, $password)
    {
        $this->connect();
        $rows = null;
		$username = mysqli_real_escape_string($this->conn,$username);
		$password = mysqli_real_escape_string($this->conn,$password);
        $result = mysqli_query($this->conn, "CALL sp_login('" . $username . "','" . $password . "');");

        if (mysqli_num_rows($result) == 1) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
			session_start();
            $_SESSION["userid"] = $row["users_id"];
            $_SESSION["username"] = $row["users_username"];
            $_SESSION["fname"] = $row["users_fname"];
            $_SESSION["lname"] = $row["users_lname"];
            $_SESSION["semat_name"] = $row["semat_name"];            
			
			$util = new UtilClass();
			$_SESSION['hashuser'] = $util->hashuser();			
			$this->close_connect();
            return 1;
        } else {
            return 0;
        }
    }
}