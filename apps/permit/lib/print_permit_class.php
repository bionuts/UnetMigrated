<?php
include 'permit-config.php';
include 'permitUtil.php';

class print_permit_class
{
    private $conn;
    private function connect()
    {
        $this->conn = mysqli_connect(PermitConfigClass::$dbserver, PermitConfigClass::$user, PermitConfigClass::$pass, PermitConfigClass::$dbname);
        if ($this->conn->connect_error) {
            return -1;
        }
        mysqli_set_charset($this->conn, "utf8");
    }

    private function close_connect()
    {
        mysqli_close($this->conn);
    }

	public function doprint($pid,$userid)
	{
		if(!$this->check_permit_isgreen($pid)) {return false;}
		if(!$this->is_owner_of_permit($pid,$userid)) return false;
		return true;
	}
	
	public function print_permit_get_safty_act_list($permitid)
	{
		$this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "select hint_value from permit_tbl_permit_activity_hints inner join permit_tbl_safty_activity_hints on permit_tbl_permit_activity_hints.fk_hint_id = permit_tbl_safty_activity_hints.hint_idwhere permit_tbl_permit_activity_hints.fk_permit_id = $permitid");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
        $this->close_connect();        
        return $rows;
	}
	
	private function is_owner_of_permit($pid,$userid)
	{	
		$ret = false;
		$this->connect();
		$putil = new permitUtil();
		$roleid = $putil->getUserRoleID($userid);
		$roleid = $roleid[0];		
		$result = mysqli_query($this->conn, "CALL permit_sp_is_owner_of_permit($pid,$userid,$roleid);");
		if (mysqli_num_rows($result) == 1)
		{			
			$row = mysqli_fetch_assoc($result);			
			if($row['resulttotal'] > 0)
				$ret = true;
		}
		$this->close_connect();
		return $ret;
	}
	
	
	private function check_permit_isgreen($pid)
	{
		$ret = true;
		$this->connect();
		$result = mysqli_query($this->conn, "CALL permit_sp_check_permit_isgreen($pid);");
		if (mysqli_num_rows($result) == 0)
		{			
			$ret = false;
		}
		$this->close_connect();
		return $ret;
	}
	
	
	
    public function show_new_request_2($mojavez_id)
    {
        $this->connect();
        $rows = -1;
        $result = mysqli_query($this->conn, "CALL permit_sp_show_request_2($mojavez_id);");
        $row = null;
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $rows;
    }

    public function show_request($mojavez_id)
    {

        $this->connect();
        $result = mysqli_query($this->conn, "CALL aaa_permit_sp_permit_data($mojavez_id);");

        $row = '';
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

        } else {
            return -1;
        }
        mysqli_free_result($result);
        $this->close_connect();
        return $row;
    }
	
	
    public function get_activity_date($mojavez_id)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_activity_date($mojavez_id);");

        $row = '';
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

        } else {
            return -1;
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