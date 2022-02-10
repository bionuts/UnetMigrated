<?php
include 'permit-config.php';

class print_permit_class
{
    //Variables list
    /*private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name= 'unetdb';*/

    private $conn;

    //connect to db
    private function connect()
    {
        $this->conn = mysqli_connect(PermitConfigClass::$dbserver, PermitConfigClass::$user, PermitConfigClass::$pass, PermitConfigClass::$dbname);
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

    public function show_new_request_2($mojavez_id)
    {
        $this->connect();
        $row = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_show_request_2($mojavez_id);");
        $row = null;
        echo mysqli_num_rows($result);
        if (mysqli_num_rows($result) == 1) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $row;
    }

    public function show_request($mojavez_id)
    {
        $this->connect();
        $row = null;
        $result = mysqli_query($this->conn, "CALL aaa_permit_sp_permit_data($mojavez_id);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        mysqli_free_result($result);
        $this->close_connect();
        return $row;
    }

    public function show_list_nazer($mojavez_id)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_get_selected_nazers($mojavez_id);");
        $str = '';
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                $str .= $row['users_fname'] . " " . $row['users_lname'] . " (کد ملی : " . $row['userdetail_codemelli'] . ", شماره تماس : " . $row['userdetail_mobile'] . ") <br />";
            }
        } else {
            //return -1;
        }
        mysqli_free_result($result);
        $this->close_connect();

        return $str;
    }

    public function show_list_peimankar($mojavez_id)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_get_selected_nafarat_peimankar($mojavez_id);");
        $row = '';
        $str = '';
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                $str .= $row['peimankar_listnafarat_fname'] . " " . $row['peimankar_listnafarat_lname'] . " (کد ملی : " . $row['peimankar_listnafarat_codemelli'] . ", شماره تماس : " . $row['peimankar_listnafarat_mobile'] . ") <br />";
            }
        } else {
            //	return -1;
        }
        mysqli_free_result($result);
        $this->close_connect();
        //echo $str;
        return $str;
    }

    //TEST OK
    public function show_new_request($mojavez_id)
    {
        $this->connect();
        $row = '';
        $result = mysqli_query($this->conn, "CALL permit_sp_show_request($mojavez_id);");

        if (mysqli_num_rows($result) == 1) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
        } else {
            return -1;
        }

        $this->close_connect();

        return $row;
    }
}

?>