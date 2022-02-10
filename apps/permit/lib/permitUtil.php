<?php
class permitUtil
{
    private $conn = null;

    public function getTblHeader_shaghol($roleid,$bayegani = false)
    {
        $output = '';
        $output .= '<tr class="permit_header_row" style="visibility:hidden;">';
        $output .= '<td style="width:4%;position:relative;padding:0;"></td>';
        $output .= '<td style="width:4%;"></td>';
        $output .= '<td style="width:8%;"></td>';
        $output .= '<td style="width:8%;"></td>';
        $output .= '<td style="width:8%;"></td>';
        $output .= '<td style="width:8%;"></td>';
        $output .= '<td style="width:8%;"></td>';
        $output .= '<td style="width:10%;"></td>';
        $output .= '<td style="width:10%;"></td>';
        $output .= '<td style="width:10%;"></td>';
        $output .= '<td style="width:9%;"></td>';

        $nazer_col = false;
        switch ($roleid) {
            case 1: // peimankar sakht
            case 2: //peimankar Bahrebardari
            case 5: //Nazer Bahrebardari
            case 7: //Nazer Bahrebardari - Green
            case 9: //Karbare Darkhast Dahande Sakht
                $nazer_col = true;
                break;
            /*case 6: //Nazer sakht
                break;*/
            /*case 8: //Nazer Sakht - Green
            break;*/
            /*case 3: //OCC Signed
            case 4: //OCC Un-Signed*/

        }
        if ($nazer_col) {
            $output .= ' <td style="width:7%;vertical-align:bottom;"></td>';
        }

        $output .= '<td style="width:4%;vertical-align:bottom;"></td>';

        if (($roleid == 3 || $roleid == 11) && !$bayegani) //OCC Signed or omooristgah signed
        {
            $output .= '<td style="width:4%;vertical-align:bottom;"></td>';
        }
        else if($bayegani)
        {
            $output .= '<td style="width:4%;vertical-align:bottom;"></td>';
        }
						
        $output .= '</tr>';
        return $output;
    }

    public function getTblHeader($roleid,$bayegani = false)
    {
        $output = '';
        $output .= '<tr class="permit_header_row">';
        $output .= '<td style="width:4%;vertical-align:bottom;position:relative;padding:0;"><div>جزئیات</div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div>شماره</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>توضیحات</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>شرح عملیات</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>واحد نظارت</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div> واحد کشیک</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>پیمانکار</div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div>زمان انجام فعالیت</div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div>حوزه کاری</div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div>محل کار</div></td>';
        $output .= '<td style="width:9%;vertical-align:bottom;"><div>نوع مجوز</div></td>';

        $nazer_col = false;
        switch ($roleid) {
            case 1: // peimankar sakht
            case 2: //peimankar Bahrebardari
            case 5: //Nazer Bahrebardari
            // case 7: //Nazer Bahrebardari - Green
            case 9: //Karbare Darkhast Dahande Sakht
                $nazer_col = true;
                break;
            /*case 6: //Nazer sakht
                break;*/
            /*case 8: //Nazer Sakht - Green
            break;*/
            /*case 3: //OCC Signed
            case 4: //OCC Un-Signed*/

        }
        if ($nazer_col) {
            $output .= ' <td style="width:7%;vertical-align:bottom;"><div>توضیحات ناظر</div></td>';
        }

        $output .= '<td style="width:4%;vertical-align:bottom;"><div>قطع برق</div></td>';

        if (($roleid == 3 || $roleid == 11) && !$bayegani) //OCC Signed or omooristgah signed
        {
            $output .= '<td style="width:4%;vertical-align:bottom;"><div>تائید/رد</div></td>';
        }
        else if($bayegani)
        {
            $output .= '<td style="width:4%;vertical-align:bottom;"><div>تاریخ</div></td>';
        }
		
		if ($roleid == 1 || $roleid == 2) // peimankar can delete permit
        {
            $output .= '<td style="width:4%;vertical-align:bottom;"><div>حذف</div></td>';
        }
		
        $output .= '</tr>';
        return $output;
    }

    public function getTblHeader_greenToday_permits_shaghol()
    {
        $output = '';
        $output .= '<tr class="permit_header_row">';
        $output .= '<td style="width:4%;vertical-align:bottom;position:relative;padding:0;"><div></div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:9%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div></div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div></div></td>';
        $output .= '</tr>';
        return $output;
    }

    public function getTblHeader_greenToday_permits()
    {
        $output = '';
        $output .= '<tr class="permit_header_row">';
        $output .= '<td style="width:4%;vertical-align:bottom;position:relative;padding:0;"><div>جزئیات</div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div>شماده</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>توضیحات OCC</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>شرح عملیات</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>واحد نظارت</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div> واحد کشیک</div></td>';
        $output .= '<td style="width:8%;vertical-align:bottom;"><div>پیمانکار</div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div>زمان انجام فعالیت</div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div>حوزه کاری</div></td>';
        $output .= '<td style="width:10%;vertical-align:bottom;"><div>محل کار</div></td>';
        $output .= '<td style="width:9%;vertical-align:bottom;"><div>نوع مجوز</div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div>قطع برق</div></td>';
        $output .= '<td style="width:4%;vertical-align:bottom;"><div>تاریخ</div></td>';
        $output .= '</tr>';
        return $output;
    }

    public function getPermitSetting($name)
    {
        $this->connect();
        $ret = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_get_permit_setting('$name');");

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $ret = $row['permit_tbl_settings_value'];
        }
        $this->close_connect();
        return $ret;
    }
	
	public function GetPermitSetting_occStatus()
    {
        $this->connect();
        $row = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_get_permit_setting('publish_status');");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);            
        }
        $this->close_connect();
        return $row;
    }
	
	public function SetPermitSetting_occStatus($status,$status_date)
    {
        $this->connect();        
		// name : publish_status
        $result = mysqli_query($this->conn, "CALL permit_sp_set_permit_setting_occ_status('$status','$status_date');");
		$r='';
		if (mysqli_affected_rows($this->conn) > 0) {
            $r = 'updated';
        } else {
            $r = 'failed,try again';
        }
        $this->close_connect();
        return $r;
    }
	
	public function set_curdate_for_occStatus()
    {
        $this->connect();        		
        $result = mysqli_query($this->conn, "call permit_sp_occ_status_date_setter();");
		$r = '';
		if (mysqli_affected_rows($this->conn) > 0) {
            $r = 'updated';
        } else {
            $r = 'failed,try again';
        }
        $this->close_connect();
        return $r;
    }
	
	public function OwnerPermit($pid,$roleid,$userid)
	{	
		$ret = false;
		$this->connect();
		
		$result = mysqli_query($this->conn, "CALL permit_sp_delete_check_owner_for_permit($pid,$userid,$roleid);");
		if (mysqli_num_rows($result) == 1)
		{			
			$row = mysqli_fetch_assoc($result);			
			if($row['resulttotal'] > 0)
				$ret = true;
		}
		$this->close_connect();
		return $ret;
	}
	
	public function delete_permit($permit_id)
    {
		$ret=false;
        $this->connect();        		
        $result = mysqli_query($this->conn, "call permit_sp_delete_permit($permit_id);");
		if (mysqli_affected_rows($this->conn) > 0)
            $ret=true;        
        $this->close_connect();
        return $ret;
    }

    public function getUserRoleID($userid)
    {
        $this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_get_user_roleid($userid);");

        if (mysqli_num_rows($result) >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row['ptur_roleid'];
            }
        }
        $this->close_connect();
        return $rows;
    }

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
}