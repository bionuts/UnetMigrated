<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config/main_config.php');
class new_request_class
{
    private $conn;

    function __construct()
    {
        if (session_id() == '') session_start();
    }

	public function permit_check_peim_can_request_permit($peimid)
	{
		$this->connect();
        $row = null;
        $result = mysqli_query($this->conn, "CALL permit_check_peimankar_can_request_permit($peimid);");

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $row['peim_can_request_permit'];
	}
	
	public function get_safty_hint_list_multicheckbox($permit_id)
    {
        $this->connect();
		$selected_result = mysqli_query($this->conn, "select fk_hint_id from permit_tbl_permit_activity_hints where fk_permit_id = $permit_id order by fk_hint_id asc;");
		
		$selected_rows = [];
		while($row = mysqli_fetch_assoc($selected_result)) {
			$selected_rows[] = $row['fk_hint_id'];
		}
		
        $safty_hints = mysqli_query($this->conn, "select * from permit_tbl_safty_activity_hints;");
        $str = '';
        $rows = null;
        if (mysqli_num_rows($safty_hints) > 0) {            
            while ($row = mysqli_fetch_assoc($safty_hints)) {                
                $str .= '<input name="safty_checkbox" id="permit_chkbx_safty_edit_' . $row['hint_id'] . '" type="checkbox" ' . ((in_array($row['hint_id'], $selected_rows)) ? 'checked' : '') .
                    ' value="' . $row['hint_id'] . '"><label for="permit_chkbx_safty_edit_' . $row['hint_id'] . '">' . $row['hint_value'] . '</label><br style="margin:0px;"/>';
            }
        }

        $this->close_connect();
        return $str;
    }
	
	public function permit_get_safty_act_list()
	{
		$this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "select * from permit_tbl_safty_activity_hints;");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
        $this->close_connect();        
        return $rows;
	}
    
	public function permit_sp_check_nazer_for_edit_permit($userid, $permitid)
    {
        $this->connect();
        $row = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_check_nazer_for_edit($userid,$permitid);");

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }

        $this->close_connect();
        return $row;
    }

    private function connect()
    {
        $this->conn = mysqli_connect(MainConfigClass::$dbserver, MainConfigClass::$user, MainConfigClass::$pass, MainConfigClass::$dbname);
        if ($this->conn->connect_error) {
            return -1;
        }
        mysqli_set_charset($this->conn, "utf8");
    }

    private function close_connect()
    {
        mysqli_close($this->conn);
    }

    public function get_nazers_list($nezarat_id)
    {
        $this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_GetNazerShowUser(" . $nezarat_id . ");");

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return json_encode($rows);
    }

    public function get_nazers_list_edit($nezarat_id)
    {
        $this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_GetNazerShowUser(" . $nezarat_id . ");");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return $rows;
    }

    public function get_nezarati_vahed_list($userid)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_Show_nazer($userid);");
        $str = '';

        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                $str .= $option_start . $row['vahednezarat_id'] . "'>" . $row['vahednezarat_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_peymankars_list_rolebase($roleid, $id_nezarat, $userid)
    {
        $option_start = "<option value='";
        $option_end = "</option>";
        $str = '';

        $this->connect();

        switch ($roleid) {
            case 1: // peimankar sakht
            case 2: //peimankar Bahrebardari
                //khodesho bebine
                $result = mysqli_query($this->conn, " CALL permit_sp_GetUserNazerTakePeimankar('peimankar',$id_nezarat,$userid);");
                break;
            case 3: //OCC Signed
            case 4: //OCC Un-Signed
                $result = mysqli_query($this->conn, " CALL permit_sp_GetUserNazerTakePeimankar('occ',$id_nezarat,$userid);");
                break;
            case 5: //Nazer Bahrebardari
                //all bahrebardari peimankar
                $result = mysqli_query($this->conn, " CALL permit_sp_GetUserNazerTakePeimankar('bahrebardari',$id_nezarat,$userid);");
                break;
            case 7: //Nazer Bahrebardari - Green
                $result = mysqli_query($this->conn, " CALL permit_sp_GetUserNazerTakePeimankar('security',$id_nezarat,$userid);");
                break;
            case 6: //Nazer sakht
            case 8: //Nazer Sakht - Green
            case 9: //Karbare Darkhast Dahande Sakht
                //all sakht peimankar
                $result = mysqli_query($this->conn, " CALL permit_sp_GetUserNazerTakePeimankar('sakht',$id_nezarat,$userid);");
                break;
        }
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                $str .= $option_start . $row['users_id'] . "'>" . $row['users_fname'] . " " . $row['users_lname'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }
        $this->close_connect();
        return $str;
    }

    public function get_peymankars_nafarat_list($peymankar_id)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_GetPeimankarTakeListOfp(" . $peymankar_id . ");");
        $rows = null;
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return json_encode($rows);
    }

    public function get_selected_peimankar_list($permit_id)
    {
        $this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_get_selected_nafarat_peimankar($permit_id);");

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row['fkusers_mojavez_peimankar_id'];
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return $rows;
    }

    public function get_peymankars_nafarat_list_edit($peymankar_id)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_GetPeimankarTakeListOfp(" . $peymankar_id . ");");
        $rows = null;
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return $rows;
    }
	
	public function get_peim_supervisor_list( $peimid , $selindex = 0 )
	{
		$this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_GetPeimankarTakeListOfp(" . $peimid . ");");
        $str = '';
        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $str .= $option_start . $row['peimankar_listnafarat_id'] . "'" . ($selindex == $row['peimankar_listnafarat_id'] ? 'selected' : '') . ">" . $row['peimankar_listnafarat_fname'] .' '. $row['peimankar_listnafarat_lname'].'('.$row['peimankar_listnafarat_mobile'].')' . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
	}

    public function get_activity_do_time_list($selindex = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_ShowDoActivity();");
        $str = '';
        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $str .= $option_start . $row['do_activity_id'] . "'" . ($selindex == $row['do_activity_id'] ? 'selected' : '') . ">" . $row['do_activity_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_Lines_list($selindex = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_ShowListOfLine();");
        $str = '';
        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $khatsele = '';
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['linenumber_id'] == 1) {
                    if ($selindex == 0) {
                        $khatsele = 'selected';
                    } else {
                        $khatsele = 'selected';
                    }
                } else {
                    if ($row['linenumber_id'] == $selindex) {
                        $khatsele = 'selected';
                    } else {
                        $khatsele = '';
                    }
                }
                $str .= $option_start . $row['linenumber_id'] . "' $khatsele>" . $row['linenumber_name'] . $option_end;
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return ($str);
    }

    public function get_kari_hoze_list($activity_time, $index = 0)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_GetActivityFilterHozekari($activity_time);");
        $str = '';
        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                $str .= $option_start . $row['hozekari_id'] . "' " . ($index == $row['hozekari_id'] ? 'selected' : '') . ">" . $row['hozekari_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_kari_place_list($hozekari, $line, $index = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_get_hoze_line_take_mahal($hozekari , $line);");
        $str = '';
        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $str .= $option_start . $row['mahal_kar_id'] . "' " . ($index == $row['mahal_kar_id'] ? 'selected' : '') . ">" . $row['mahal_kar_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_kari_place_list_json($hozekari, $line, $index = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_get_hoze_line_take_mahal($hozekari , $line);");
        $str = '';
        $rows = null;
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                if ($index == $row['mahal_kar_id']) {
                    $row['chk'] = 1;
                } else {
                    $row['chk'] = 0;
                }
                $rows[] = $row;
            }
        }

        $this->close_connect();
        return $rows;
    }

    public function get_kari_place_list_multicheckbox($hozekari, $line, $index)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_get_hoze_line_take_mahal($hozekari , $line);");
        $str = '';
        $rows = null;
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $placeid = explode("-", $index);
            while ($row = mysqli_fetch_assoc($result)) {
                //mahal_kar_id	mahal_kar_name
                $str .= '<input id="permit_place_edit_chk_' . $row['mahal_kar_id'] . '" type="checkbox" ' . ((in_array($row['mahal_kar_id'], $placeid)) ? 'checked' : '') .
                    ' value="' . $row['mahal_kar_id'] . '"><label for="permit_place_edit_chk_' . $row['mahal_kar_id'] . '">' . $row['mahal_kar_name'] . '</label><br/>';
            }
        }

        $this->close_connect();
        return $str;
    }

    public function get_mojavez_type_list($index = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_ShowTypeOfMojavez();");
        $str = '';
        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $str .= $option_start . $row['type_mojavez_id'] . "' " . ($index == $row['type_mojavez_id'] ? 'selected' : '') . ">" . $row['type_mojavez_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_trains_list($index = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_ShowTrains();");
        $str = '';

        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                $str .= $option_start . $row['trains_id'] . "' " . ($index == $row['trains_id'] ? 'selected' : '') . ">" . $row['trains_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_komaki_trains_list($index = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_ShowKomakiTrains();");
        $str = '';

        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                $str .= $option_start . $row['komaki_trains_id'] . "' " . ($index == $row['komaki_trains_id'] ? 'selected' : '') . ">" . $row['komaki_trains_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_mabda_maghsad($index = 0)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_mabdae_maghsad();");
        $str = '';

        $option_start = "<option value='";
        $option_end = "</option>";

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                $str .= $option_start . $row['mabdae_maghsad_id'] . "' " . ($index == $row['mabdae_maghsad_id'] ? 'selected' : '') . ">" . $row['mabdae_maghsad_name'] . $option_end;
            }
        } else {
            echo $option_start . "0'>اطلاعات مورد نظر یافت نشد" . $option_end;
        }

        $this->close_connect();
        return ($str);
    }

    public function get_requester_user($mojavez_id)
    {
        $this->connect();
        $row = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_get_requester_of_permit($mojavez_id);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "0";
        }
        $this->close_connect();
        return $row;
    }

    public function show_new_request($mojavez_id)
    {
        $this->connect();

        $result = mysqli_query($this->conn, "CALL permit_sp_show_request($mojavez_id);");

        if (mysqli_num_rows($result) == 1) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "0";
        }

        $this->close_connect();

        return $row;
    }

    private function save_places_permit($arr_place, $pid)
    {
        if ($arr_place != 'nosel') {
            $this->connect();
            $arrp = explode("-", $arr_place);
            $sql = '';
            foreach ($arrp as $item) {
                $sql .= "insert into permit_tbl_permit_places(permit_tbl_permit_places_permitid,permit_tbl_permit_places_palceid) values ($pid,$item); ";
            }
            mysqli_multi_query($this->conn, $sql);
            $this->close_connect();
        }
    }
	
	private function save_safty_hint_permit($arr_hints, $pid)
    {
        if ($arr_hints != 'nosel') {
            $this->connect();
            $arrp = explode("-", $arr_hints);
            $sql = '';
            foreach ($arrp as $item) {
                $sql .= "insert into permit_tbl_permit_activity_hints(fk_permit_id,fk_hint_id) values ($pid,$item); ";
            }
            mysqli_multi_query($this->conn, $sql);
            $this->close_connect();
        }
    }

    public function insert_new_request($request,$ispeim=false)
    {
        $sql = "CALL permit_sp_insert_request(" . $_SESSION["userid"] . ",'" .
            addslashes($request['permit_desc']) . "'," . $request['nezarat_unit_id'] . ",'" .
            $request['keshik_tell'] . "'," . $request['act_time'] . "," .
            $request['line_number_metro'] . "," . $request['working_scope'] . "," .
            /*$request['working_place']*/
            "0," . $request['permit_type_id'] . "," .
            $request['vehicle_id'] . "," . $request['train_id'] . ",'" .
            $request['opt_desc'] . "'," . $request['power_cut'] . "," .$request['non_critical'] . "," .$request['first_peim_supervisor'] . "," .$request['second_peim_supervisor'] . "," .
            $request['opt_start_id'] . "," . $request['opt_end_id'] . "," .
            $request['peimankar_id'] . ");";
			// 
        //$this->close_connect();
        $this->connect();

        $res = mysqli_query($this->conn, $sql);

        $perid = mysqli_fetch_assoc($res);
        $this->close_connect();
        $sql = '';


        $permit_id = $perid["last_id"];		
		$this->save_safty_hint_permit($request['safty_hints'], $permit_id);		
        $this->save_places_permit($request['working_place'], $permit_id);


        $listof_nazer = explode(",", $request['listof_nazer']);
        $listof_worker = explode(",", $request['listof_worker']);

        $len = count($listof_nazer);

        // Contractor can not select supervisor during applying new Request
        // so this block should not run by Contractor Users
        if(!$ispeim) {
            for ($index = 0; $index < $len; $index++) {
                $sql .= "INSERT INTO permit_tbl_mojavez_nazer_list (mojavez_nazer_id, fkpermit_main_mojavez_nazer_id, fkusers_mojavez_nazer_id) " .
                    "VALUES (NULL, $permit_id, " . $listof_nazer[$index] . ");";
            }
            $this->connect();
            mysqli_multi_query($this->conn, $sql);
            $this->close_connect();
        }

        $sql = '';
        $len = count($listof_worker);
        for ($index = 0; $index < $len; $index++) {
            $sql .= "INSERT INTO permit_tbl_mojavez_peimankar_worker_list (fkpermit_main_mojavez_peimankar_id, fkusers_mojavez_peimankar_id) VALUES ($permit_id," . $listof_worker[$index] . ");";
        }

        $this->connect();

        mysqli_multi_query($this->conn, $sql);
        $sql = '';
        if (mysqli_affected_rows($this->conn)) {
            $this->close_connect();
            return "0";
        } else {
            $this->close_connect();
            return "3";
        }
    }

    public function radnazer($permitid, $dalidnazer, $userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_insert_radnazer_dalil($permitid,'$dalidnazer',$userid);");

        if (mysqli_affected_rows($this->conn) > 0) {
            echo 'updated';
        } else {
            echo 'failed,try again';
        }
        $this->close_connect();
    }

    public function radtaeed_occ($permitid, $taeed_rad, $whyocc, $userid,$roleid)
    {
		$this->connect();
				
		if($roleid == 3){ // signed occ
			$result = mysqli_query($this->conn, "CALL permit_sp_insert_occ_taeed_rad_dalil($permitid,$taeed_rad,'$whyocc',$userid);");
		}
		else if($roleid == 11){ // signed omooristgah
			$result = mysqli_query($this->conn, "CALL permit_sp_insert_st_taeed_rad_dalil($permitid,$taeed_rad,'$whyocc',$userid);");
		}
		
		
        
        // $result = mysqli_query($this->conn, "CALL permit_sp_insert_occ_taeed_rad_dalil($permitid,$taeed_rad,'$whyocc',$userid);");

        if (mysqli_affected_rows($this->conn) > 0) {
            $r = 'updated';
        } else {
            $r = 'failed,try again';
        }
        $this->close_connect();
        return $r;
    }
	
	public function radtaeed_publish_occ($status)
    {
		$this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_occ_do_publish('$status');"); // "CALL permit_sp_occ_do_publish('$status');"

        if (mysqli_affected_rows($this->conn) > 0) {
            $r = 'updated';
        } else {
            $r = 'failed,try again';
        }
        $this->close_connect();
        return $r;
    }

    public function show_new_request_2($mojavez_id)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL aaa_permit_sp_permit_data($mojavez_id);");
        $row = null;
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        } else {
            $row['status'] = 'failed';
        }

        $this->close_connect();
        return $row;
    }

    public function show_new_request_data_peimankar_requested($mojavez_id)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL aaa_permit_sp_permit_data($mojavez_id);");
        $row = null;
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        } else {
            $row['status'] = 'failed';
        }

        $this->close_connect();
        return $row;
    }

    public function get_selected_nazers_list($permit_id)
    {
        $this->connect();
        $rows = null;
        $result = mysqli_query($this->conn, "CALL permit_sp_get_selected_nazers($permit_id);");

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row['users_id'];
            }
        } else {
            echo "0";
        }

        $this->close_connect();
        return $rows;
    }

    public function get_request_info($permit_id)
    {
        $this->connect();
        $row = "";
        $result = mysqli_query($this->conn, "CALL permit_sp_get_request_info($permit_id);");
        if (mysqli_num_rows($result) == 1) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
        } else {
            $row['failed'] = 0;
        }

        $this->close_connect();
        return $row;
    }

    public function update_new_request($request)
    {
        $sql = "CALL permit_sp_update_request(" . $request['permitid'] . ",'" .
            addslashes($request['permit_desc']) . "','" .
            $request['keshik_tell'] . "'," . $request['act_time'] . "," .
            $request['line_number_metro'] . "," . $request['working_scope'] . ","
            /*$request['working_place']*/ . "0," . $request['permit_type_id'] . "," .
            $request['vehicle_id'] . "," . $request['train_id'] . ",'" .
            $request['opt_desc'] . "'," . $request['power_cut'] . "," .$request['non_critical'] . "," . $request['with_supervisor'] . ",'" .addslashes($request['supervisor_hint']) . "'," .
            $request['opt_start_id'] . "," . $request['opt_end_id'] . "," . $_SESSION["userid"] . ");";
        //echo $sql;exit;

        $this->connect();
        $res = mysqli_query($this->conn, $sql);
        $this->close_connect();

        //del permits places
        $pid = $request['permitid'];
        $this->connect();
        $sql = "CALL permit_sp_del_permit_places($pid);";
        mysqli_query($this->conn, $sql);
        $this->close_connect();
        //insert nazer permits places
        $this->save_places_permit($request['working_place'], $pid);

        //drop sp call
        $sql = "CALL  permit_sp_drop_selected_nazer_and_peimankar_by_permit_id(" . $request['permitid'] . ")";
        $this->connect();
        $res = mysqli_query($this->conn, $sql);
        $this->close_connect();

        //Insert To selected nazer and peimankar tbl
        $listof_nazer = explode(",", $request['listof_nazer']);
        $listof_worker = explode(",", $request['listof_worker']);
        //echo print_r($listof_worker);exit;

        $len = count($listof_nazer);
        $sql = '';
        $permit_id = $request['permitid'];
        for ($index = 0; $index < $len; $index++) {
            $sql .= "INSERT INTO permit_tbl_mojavez_nazer_list (mojavez_nazer_id, fkpermit_main_mojavez_nazer_id, fkusers_mojavez_nazer_id) VALUES (NULL, $permit_id, " . $listof_nazer[$index] . ");";
        }

        $this->connect();
        mysqli_multi_query($this->conn, $sql);
        $this->close_connect();

        $sql = '';
        $len = count($listof_worker);
        for ($index = 0; $index < $len; $index++) {
            $sql .= "INSERT INTO permit_tbl_mojavez_peimankar_worker_list (fkpermit_main_mojavez_peimankar_id, fkusers_mojavez_peimankar_id) VALUES ($permit_id," . $listof_worker[$index] . ");";
        }

        $this->connect();
        mysqli_multi_query($this->conn, $sql);

        if (mysqli_affected_rows($this->conn)) {
            $this->close_connect();
            return "0";
        } else {
            $this->close_connect();
            return "3";
        }
    }
}
