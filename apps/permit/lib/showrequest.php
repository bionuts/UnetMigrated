<?php
include_once 'jdf.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/config/main_config.php');
$putil = new permitUtil();

class show_request
{
    private $conn = null;
    private $total_row_perpage = 12;
    private $persian_digits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    private $english_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    public function get_today_request_signed_occ($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_occ_request($userid);");
        if (mysqli_num_rows($result) > 0) {			
            return $this->show_permit_rows_today($result, 3); // signed_occ
        }
    }

    public function get_today_request_unsigned_occ($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_occ_request($userid);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today($result, 4); // un_signed_occ
        }
    }

    public function get_today_permitions_sakhtuser($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_today_permitions_for_sakhtuser($userid);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_permitions($result, 9);
        }
    }

    public function get_today_permitions_forpeimankar($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_today_permitions_for_peimankar($userid);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_permitions($result, 3);
        }
    }

    public function get_today_permitions()
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_today_permitions();");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_permitions($result, 3);
        }
    }

    public function show_permit_rows_today_permitions($result, $roleid, $bayegani = false)
    {
        $str = '';
        $util = new UtilClass();
        while ($row = mysqli_fetch_assoc($result)) {
            $nazer_tmp = $row['permit_main_taeedyaradnazer'];
            $occ_tmp = $row['permit_main_taeedyaradocc'];

            $colorname = $this->getcolor($nazer_tmp, $occ_tmp);
            $str .=
                '<tr class="data_row ' . $colorname . '">' .
                '<td>';
            if ($occ_tmp == 1 && $occ_tmp == 1) {
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details show_printable_green" src="img/p3.png" style="width:26px;" title="پرینت مجوز"/>';
            }
            $str .= '</td>' .
                '<td style="text-align:center;">' . str_replace($this->english_digits, $this->persian_digits, $row['permit_main_id']) . '</td>' .
                '<td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">';
            if (!is_null($occ_tmp)) {
                if ($occ_tmp == 1) {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                } else {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                }
                if ($occ_tmp == 1) {
                    //$str .= '<span style="display:block;">شرح شرايط انجام كار</span>';
                } else {
                    //$str .= '<span style="display:block;">مجوز توسط OCC رد شد</span>';
                }

                $str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
                    '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                    '<div class="rightarrow"></div>' .
                    '</div>' .
                    '<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
                    '<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">';
                if ($occ_tmp == 1) {
                    $str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
                } else {
                    $str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
                }
                $str .= '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
                    '</div>' .
                    '<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .= 'مجوز توسط ناظر رد شد';
                } else {
                    $str .= 'مجوز در حال بررسی می باشد';
                }
            }
            $str .=
                '</td>';
            $str .=
                '<td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">' .
                '<img vhid="true" class="permit_img_show_info_req_cmnt btnpointer" style="width:28px;height:28px;" src="img/mail.png" title="مشاهده شرح عملیات"/>' .
                '<div class="permit_info_req_hidden" style="position:absolute;top:0px;display:none;">' .
                '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                '<div class="rightarrowblue"></div>' .
                '</div>' .
                '<div style="float:left;width:400px;" class="cmnt_desc_req radius5 box_sized">' .
                '<div class="cmnt_desc_req_header box_sized" style="overflow:auto;">' .
                '<div style="display:inline-block;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/mail.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح عملیات</div>' .
                '</div>' .
                '<div class="cmnt_desc_req_body box_sized">' . $row['permit_main_sharhamaliat'] . '</div>' .
                '</div>' .
                '</div>' .
                '</td>' .
                '<td style="text-align:center;">' . $row['vahednezarat_name'] . '</td>' .
                '<td style="text-align:center;direction:ltr;">' . $util->format_phone($row['tel_vahedkeshik_permit_main']) . '</td>' .
                '<td style="text-align:center;">' . $row['users_fname'] . ' ' . $row['users_lname'] . '</td>' .
                '<td style="text-align:center;">' . $row['do_activity_name'] . '</td>' .
                '<td style="text-align:center;">' . $row['hozekari_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            $str .=
                '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/places6.png" style="margin:0px;width:30px;height:30px;position: relative;top:3px;" title="مشاهده محل کار فعالیت"/>' .
                '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                '<div class="leftarrow"></div>' .
                '</div>' .
                '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/places6.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;display:inline-block;width:80%;">لیست محل کار</div>' .
                '</div>' .
                '<div class="cmnt_nazer_body box_sized">' . $row['mahal_kar_name'] . '</div>' .
                '</div>' .
                '</div>';
            $str .= '</td>';

            //$str .= '<td style="text-align:center;font-size:12px !important;">' . $row['mahal_kar_name'] . '</td>' .
            $str .= '<td style="text-align:center;">' . $row['type_mojavez_name'] . '</td>';

            $str .= '<td style="text-align:center;">';
            if ($row['permit_main_niazbeghaatbargh']) {
                $str .= 'دارد';
            } else {
                $str .= 'ندارد';
            }
            $str .= '</td>';
            //gregorian_to_jalali(2011,2,11,' / ');
            $arrdt = explode(' ', $row['act_time_date']);
            $arrdate = explode('-', $arrdt[0]);
            $fadate = gregorian_to_jalali($arrdate[0], $arrdate[1], $arrdate[2], '/');
            $str .= '<td>';
            $tmp = $fadate;
            $str .= str_replace($this->english_digits, $this->persian_digits, $tmp);
            $str .= '</td>';
            $str .= '</tr>';
        }
        return $str;
    }

    public function get_request_occ_bayegani($userid, $pindex)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL  permit_sp_show_occ_request_baygani($userid,$pindex,$trppage);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today($result, 3, true);
        }
    }

    public function get_total_page_occ($userid)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_occ_total_page($userid,$trppage);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $this->close_connect();
            return $row['total_page'];
        }

    }

    public function get_today_request_karbare_sakhat($usersakhtid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_Show_sakhtuser_request($usersakhtid);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_for_peimankar($result);
        }
    }

    public function get_today_request_peimankar($idpeimankar)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_Show_peimankar_request($idpeimankar);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_for_peimankar($result);
        }
    }
	
	//omoor istgah unsigned user
    public function get_today_request_greenuser($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_Show_greenuser_request($userid);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today($result,4);
        }
    }
	
	//omoor istgah signed user role = 11
	public function get_today_request_omooristgah_signeduser($userid)
    {		
		// shabihe markaze farman faghat non_critical=1 ro taeed ya rad mikunan
		$this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_Show_greenuser_request($userid);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today($result, 11);
        }
    }
	
    public function get_today_request_greenuser_bayegani($userid, $pindex)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL  permit_sp_Show_greenuser_baygani($userid,$pindex,$trppage);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_bayegani_peimankar($result);
        }
    }
	
	public function get_today_request_greenuser_bayegani_signeduser($userid, $pindex)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL  permit_sp_Show_greenuser_baygani($userid,$pindex,$trppage);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_bayegani_peimankar($result);
        }
    }
	
    public function get_request_greenuser_bayegani_total_page($userid)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_greenuser_total_page($userid,$trppage);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $row['total_page'];
    }

    public function get_request_peimankar_bayegani($idpeimankar, $pindex)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_Show_peimankar_baygani($idpeimankar,$pindex,$trppage);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_bayegani_peimankar($result);
        }
    }

    public function get_request_sakhtuser_bayegani($sakhtid, $pindex)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_Show_sakhtuser_baygani($sakhtid,$pindex,$trppage);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_bayegani_peimankar($result);
        }
    }

    public function get_total_page_peimankar($pemankarid)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_peimankar_total_page($pemankarid,$trppage);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $row['total_page'];
    }

    public function get_total_page_sakhtuser($sakhtid)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_sakhtuser_total_page($sakhtid,$trppage);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $row['total_page'];
    }

    public function get_today_request_nazer($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_nazer_request($userid);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_for_nazer($result);
        }
    }

    public function get_today_permitions_for_nazer($userid)
    {
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_today_permitions_for_nazer($userid);");
        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_today_for_nazer($result, true);
        }
    }

    public function get_request_nazer_bayegani($userid, $pindex)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_show_nazer_baygani($userid,$pindex,$trppage);");

        if (mysqli_num_rows($result) > 0) {
            return $this->show_permit_rows_bayegani_for_nazer($result);
        }
    }

    public function show_permit_rows_bayegani_for_nazer($result)
    {
        $str = '';
        $i = 0;
        $util = new UtilClass();
        while ($row = mysqli_fetch_assoc($result)) {
            $nazer_tmp = $row['permit_main_taeedyaradnazer'];
            $occ_tmp = $row['permit_main_taeedyaradocc'];

            $colorname = $this->getcolor($nazer_tmp, $occ_tmp);
            $str .=
                '<tr class="data_row ' . $colorname . '">' .
                '<td>';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details show_printable_green" src="img/p3.png" style="width:26px;" title="پرینت مجوز"/>';
            } else {
                $tmp_details = 'permit_img_show_details_noedit_info';
                $imgname = 'info2.png';
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details ' . $tmp_details . '" style="width:24px;" src="img/' . $imgname . '" title="جزئیات"/>';
            }
            $str .= '</td>' .
                '<td style="text-align:center;">' . str_replace($this->english_digits, $this->persian_digits, $row['permit_main_id']) . '</td>' .
                '<td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">';
            if (!is_null($occ_tmp)) {
                if ($occ_tmp == 1) {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                } else {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                }

                $str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
                    '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                    '<div class="rightarrow"></div>' .
                    '</div>' .
                    '<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
                    '<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">';
                if ($occ_tmp == 1) {
                    $str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
                } else {
                    $str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
                }
                $str .= '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
                    '</div>' .
                    '<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .= 'مجوز توسط ناظر رد شد';
                } else {
                    $str .= 'مجوز در حال بررسی می باشد';
                }
            }
            $str .=
                '</td>';
            $str .=
                '<td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">' .
                '<img vhid="true" class="permit_img_show_info_req_cmnt btnpointer" style="width:28px;height:28px;" src="img/mail.png" title="مشاهده شرح عملیات"/>' .
                '<div class="permit_info_req_hidden" style="position:absolute;top:0px;display:none;">' .
                '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                '<div class="rightarrowblue"></div>' .
                '</div>' .
                '<div style="float:left;width:400px;" class="cmnt_desc_req radius5 box_sized">' .
                '<div class="cmnt_desc_req_header box_sized" style="overflow:auto;">' .
                '<div style="display:inline-block;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/mail.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح عملیات</div>' .
                '</div>' .
                '<div class="cmnt_desc_req_body box_sized">' . $row['permit_main_sharhamaliat'] . '</div>' .
                '</div>' .
                '</div>' .
                '</td>' .
                '<td style="text-align:center;">' . $row['vahednezarat_name'] . '</td>' .
                '<td style="text-align:center;direction:ltr;">' . $util->format_phone($row['tel_vahedkeshik_permit_main']) . '</td>' .
                '<td style="text-align:center;">' . $row['users_fname'] . ' ' . $row['users_lname'] . '</td>' .
                '<td style="text-align:center;">' . $row['do_activity_name'] . '</td>' .
                '<td style="text-align:center;">' . $row['hozekari_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            $str .=
                '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/places6.png" style="margin:0px;width:30px;height:30px;position: relative;top:3px;" title="مشاهده محل کار فعالیت"/>' .
                '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                '<div class="leftarrow"></div>' .
                '</div>' .
                '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/places6.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;display:inline-block;width:80%;">لیست محل کار</div>' .
                '</div>' .
                '<div class="cmnt_nazer_body box_sized">' . $row['mahal_kar_name'] . '</div>' .
                '</div>' .
                '</div>';
            $str .= '</td>';

            $str .= '<td style="text-align:center;">' . $row['type_mojavez_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                $str .=
                    '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/nazer.png" style="margin:0px;width:40px;height:40px;" title="مشاهده توضیحات ناظر"/>' .
                    '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                    '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                    '<div class="leftarrow"></div>' .
                    '</div>' .
                    '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                    '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                    '<img class="permit_img_show_details" src="img/nazer.png" style="width:20px;height:20px;"/>' .
                    '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">توضیحات ناظر</div>' .
                    '</div>' .
                    '<div class="cmnt_nazer_body box_sized">' . $row['permit_main_dalilradnazer'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                $str .= '-';
            }
            $str .= '</td>';
            $str .= '<td style="text-align:center;">';
            if ($row['permit_main_niazbeghaatbargh']) {
                $str .= 'دارد';
            } else {
                $str .= 'ندارد';
            }
            $str .= '</td>';
            //gregorian_to_jalali(2011,2,11,' / ');
            $str .= '<td style="direction: ltr;text-align: center;">';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $arrdt = explode(' ', $row['act_time_date']);
                $arrdate = explode('-', $arrdt[0]);
                $fadate = gregorian_to_jalali($arrdate[0], $arrdate[1], $arrdate[2], '/');
                $tmp = $fadate;// . '<br/>' . $arrdt[1];
                $str .= str_replace($this->english_digits, $this->persian_digits, $tmp);
            } else {
                $str .= '-';
            }
            $str .= '</td>';
            $str .= '</tr>';
        }
        return $str;
    }

    public function show_permit_rows_today_for_nazer($result, $bayegani = false)
    {
        $str = '';
        $util = new UtilClass();
        while ($row = mysqli_fetch_assoc($result)) {
            $nazer_tmp = $row['permit_main_taeedyaradnazer'];
            $occ_tmp = $row['permit_main_taeedyaradocc'];
            $colorname = $this->getcolor($nazer_tmp, $occ_tmp);
            $str .=
                '<tr class="data_row ' . $colorname . '">' .
                '<td>';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details show_printable_green" src="img/p3.png" style="width:26px;" title="پرینت مجوز"/>';
            } else {
                $tmp_details = 'permit_img_show_details_nazer_info';
                $imgname = 'edit2.png';
                /*if ($row['ptunps_issign'] == 0 || !is_null($nazer_tmp)) {
                    $tmp_details = 'permit_img_show_details_noedit_info';
                    $imgname = 'info2.png';
                } else if (($row['ptunps_issign'] == 1) && is_null($nazer_tmp)) {
                    $tmp_details = 'permit_img_show_details_nazer_info';
                    $imgname = 'edit2.png';
                }*/
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details ' . $tmp_details . '" style="width:24px;" src="img/' . $imgname . '" title="جزئیات"/>';
            }
            $str .= '</td>' .
                '<td style="text-align:center;">' . str_replace($this->english_digits, $this->persian_digits, $row['permit_main_id']) . '</td>' .
                '<td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">';
            if (!is_null($occ_tmp)) {
                if ($occ_tmp == 1) {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                } else {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                }

                $str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
                    '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                    '<div class="rightarrow"></div>' .
                    '</div>' .
                    '<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
                    '<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">';
                if ($occ_tmp == 1) {
                    $str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
                } else {
                    $str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
                }
                $str .= '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
                    '</div>' .
                    '<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .= 'مجوز توسط ناظر رد شد';
                } else {
                    $str .= 'مجوز در حال بررسی می باشد';
                }
            }
            $str .=
                '</td>';
            $str .=
                '<td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">' .
                '<img vhid="true" class="permit_img_show_info_req_cmnt btnpointer" style="width:28px;height:28px;" src="img/mail.png" title="مشاهده شرح عملیات"/>' .
                '<div class="permit_info_req_hidden" style="position:absolute;top:0px;display:none;">' .
                '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                '<div class="rightarrowblue"></div>' .
                '</div>' .
                '<div style="float:left;width:400px;" class="cmnt_desc_req radius5 box_sized">' .
                '<div class="cmnt_desc_req_header box_sized" style="overflow:auto;">' .
                '<div style="display:inline-block;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/mail.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح عملیات</div>' .
                '</div>' .
                '<div class="cmnt_desc_req_body box_sized">' . $row['permit_main_sharhamaliat'] . '</div>' .
                '</div>' .
                '</div>' .
                '</td>' .
                '<td style="text-align:center;">' . $row['vahednezarat_name'] . '</td>' .
                '<td style="text-align:center;direction:ltr;">' . $util->format_phone($row['tel_vahedkeshik_permit_main']) . '</td>' .
                '<td style="text-align:center;">' . $row['users_fname'] . ' ' . $row['users_lname'] . '</td>' .
                '<td style="text-align:center;">' . $row['do_activity_name'] . '</td>' .
                '<td style="text-align:center;">' . $row['hozekari_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            $str .=
                '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/places6.png" style="margin:0px;width:30px;height:30px;position: relative;top:3px;" title="مشاهده محل کار فعالیت"/>' .
                '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                '<div class="leftarrow"></div>' .
                '</div>' .
                '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/places6.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;display:inline-block;width:80%;">لیست محل کار</div>' .
                '</div>' .
                '<div class="cmnt_nazer_body box_sized">' . $row['mahal_kar_name'] . '</div>' .
                '</div>' .
                '</div>';
            $str .= '</td>';

            $str .= '<td style="text-align:center;">' . $row['type_mojavez_name'] . '</td>';

            if (!$bayegani) {
                $str .= '<td style="text-align:center;position:relative;z-index:999;">';

                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .=
                        '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/nazer.png" style="margin:0px;width:40px;height:40px;" title="مشاهده توضیحات ناظر"/>' .
                        '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                        '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                        '<div class="leftarrow"></div>' .
                        '</div>' .
                        '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                        '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                        '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                        '<img class="permit_img_show_details" src="img/nazer.png" style="width:20px;height:20px;"/>' .
                        '</div>' .
                        '<div style="position:relative;display:inline-block;width:80%;">توضیحات ناظر</div>' .
                        '</div>' .
                        '<div class="cmnt_nazer_body box_sized">' . $row['permit_main_dalilradnazer'] . '</div>' .
                        '</div>' .
                        '</div>';
                } else {
                    $str .= '-';
                }
                $str .= '</td>';
            }
            $str .= '<td style="text-align:center;">';
            if ($row['permit_main_niazbeghaatbargh']) {
                $str .= 'دارد';
            } else {
                $str .= 'ندارد';
            }
            $str .= '</td>';
            if ($bayegani) {
                //gregorian_to_jalali(2011,2,11,' / ');
                $arrdt = explode(' ', $row['act_time_date']);
                $arrdate = explode('-', $arrdt[0]);
                $fadate = gregorian_to_jalali($arrdate[0], $arrdate[1], $arrdate[2], '/');
                $str .= '<td style="direction: ltr;text-align: center;">';
                if ($occ_tmp == 1 && $nazer_tmp == 1) {
                    $tmp = $fadate;// . '<br/>' . $arrdt[1];
                    $str .= str_replace($this->english_digits, $this->persian_digits, $tmp);
                } else {
                    $str .= '-';
                }
                $str .= '</td>';
            }
            $str .= '</tr>';
        }
        return $str;
    }

    public function get_total_page_nazer($userid)
    {
        $trppage = $this->total_row_perpage;
        $this->connect();
        $result = mysqli_query($this->conn, "CALL permit_sp_nazer_total_page($userid,$trppage);");
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        }
        $this->close_connect();
        return $row['total_page'];
    }

    public function show_permit_rows_today_bayegani_peimankar($result)
    {
        $str = '';
        $i = 0;
        $util = new UtilClass();
        while ($row = mysqli_fetch_assoc($result)) {
            $nazer_tmp = $row['permit_main_taeedyaradnazer'];
            $occ_tmp = $row['permit_main_taeedyaradocc'];

            $colorname = $this->getcolor($nazer_tmp, $occ_tmp);
            $str .=
                '<tr class="data_row ' . $colorname . '">' .
                '<td>';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details show_printable_green" src="img/p3.png" style="width:26px;" title="پرینت مجوز"/>';
            } else {
                $tmp_details = 'permit_img_show_details_noedit_info';
                $imgname = 'info2.png';
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details ' . $tmp_details . '" style="width:24px;" src="img/' . $imgname . '" title="جزئیات"/>';
            }
            $str .= '</td>' .
                '<td style="text-align:center;">' . str_replace($this->english_digits, $this->persian_digits, $row['permit_main_id']) . '</td>' .
                '<td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">';
            if (!is_null($occ_tmp)) {
                if ($occ_tmp == 1) {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                } else {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                }

                $str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
                    '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                    '<div class="rightarrow"></div>' .
                    '</div>' .
                    '<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
                    '<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">';
                if ($occ_tmp == 1) {
                    $str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
                } else {
                    $str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
                }
                $str .= '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
                    '</div>' .
                    '<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .= 'مجوز توسط ناظر رد شد';
                } else {
                    $str .= 'مجوز در حال بررسی می باشد';
                }
            }
            $str .=
                '</td>';
            $str .=
                '<td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">' .
                '<img vhid="true" class="permit_img_show_info_req_cmnt btnpointer" style="width:28px;height:28px;" src="img/mail.png" title="مشاهده شرح عملیات"/>' .
                '<div class="permit_info_req_hidden" style="position:absolute;top:0px;display:none;">' .
                '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                '<div class="rightarrowblue"></div>' .
                '</div>' .
                '<div style="float:left;width:400px;" class="cmnt_desc_req radius5 box_sized">' .
                '<div class="cmnt_desc_req_header box_sized" style="overflow:auto;">' .
                '<div style="display:inline-block;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/mail.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح عملیات</div>' .
                '</div>' .
                '<div class="cmnt_desc_req_body box_sized">' . $row['permit_main_sharhamaliat'] . '</div>' .
                '</div>' .
                '</div>' .
                '</td>' .
                '<td style="text-align:center;">' . $row['vahednezarat_name'] . '</td>' .
                '<td style="text-align:center;direction:ltr;">' . $util->format_phone($row['tel_vahedkeshik_permit_main']) . '</td>' .
                '<td style="text-align:center;">' . $row['users_fname'] . ' ' . $row['users_lname'] . '</td>' .
                '<td style="text-align:center;">' . $row['do_activity_name'] . '</td>' .
                '<td style="text-align:center;">' . $row['hozekari_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            $str .=
                '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/places6.png" style="margin:0px;width:30px;height:30px;position: relative;top:3px;" title="مشاهده محل کار فعالیت"/>' .
                '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                '<div class="leftarrow"></div>' .
                '</div>' .
                '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/places6.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;display:inline-block;width:80%;">لیست محل کار</div>' .
                '</div>' .
                '<div class="cmnt_nazer_body box_sized">' . $row['mahal_kar_name'] . '</div>' .
                '</div>' .
                '</div>';
            $str .= '</td>';

            $str .= '<td style="text-align:center;">' . $row['type_mojavez_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                $str .=
                    '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/nazer.png" style="margin:0px;width:40px;height:40px;" title="مشاهده توضیحات ناظر"/>' .
                    '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                    '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                    '<div class="leftarrow"></div>' .
                    '</div>' .
                    '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                    '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                    '<img class="permit_img_show_details" src="img/nazer.png" style="width:20px;height:20px;"/>' .
                    '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">توضیحات ناظر</div>' .
                    '</div>' .
                    '<div class="cmnt_nazer_body box_sized">' . $row['permit_main_dalilradnazer'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                $str .= '-';
            }
            $str .= '</td>';
            $str .= '<td style="text-align:center;">';
            if ($row['permit_main_niazbeghaatbargh']) {
                $str .= 'دارد';
            } else {
                $str .= 'ندارد';
            }
            $str .= '</td>';
            //gregorian_to_jalali(2011,2,11,' / ');
            $str .= '<td style="direction: ltr;text-align: center;">';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $arrdt = explode(' ', $row['act_time_date']);
                $arrdate = explode('-', $arrdt[0]);
                $fadate = gregorian_to_jalali($arrdate[0], $arrdate[1], $arrdate[2], '/');
                $tmp = $fadate;// . '<br/>' . $arrdt[1];
                $str .= str_replace($this->english_digits, $this->persian_digits, $tmp);
            } else {
                $str .= '-';
            }
            $str .= '</td>';
            $str .= '</tr>';
        }
        return $str;
    }

    public function show_permit_rows_today_for_peimankar($result, $bayegani = false)
    {
        $str = '';
        $i = 0;
        $util = new UtilClass();
        while ($row = mysqli_fetch_assoc($result)) {
            $nazer_tmp = $row['permit_main_taeedyaradnazer'];
            $occ_tmp = $row['permit_main_taeedyaradocc'];

            $colorname = $this->getcolor($nazer_tmp, $occ_tmp);
            $str .=
                '<tr class="data_row ' . $colorname . '">' .
                '<td>';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details show_printable_green" src="img/p3.png" style="width:26px;" title="پرینت مجوز"/>';
            } else {
                $tmp_details = 'permit_img_show_details_noedit_info';
                $imgname = 'info2.png';
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details ' . $tmp_details . '" style="width:24px;" src="img/' . $imgname . '" title="جزئیات"/>';
            }
            $str .= '</td>' .
                '<td style="text-align:center;">' . str_replace($this->english_digits, $this->persian_digits, $row['permit_main_id']) . '</td>' .
                '<td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">';
            if (!is_null($occ_tmp)) {
                if ($occ_tmp == 1) {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                } else {
                    $str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
                }

                $str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
                    '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                    '<div class="rightarrow"></div>' .
                    '</div>' .
                    '<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
                    '<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">';
                if ($occ_tmp == 1) {
                    $str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
                } else {
                    $str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
                }
                $str .= '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
                    '</div>' .
                    '<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .= 'مجوز توسط ناظر رد شد';
                } else {
                    $str .= 'مجوز در حال بررسی می باشد';
                }
            }
            $str .=
                '</td>';
            $str .=
                '<td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">' .
                '<img vhid="true" class="permit_img_show_info_req_cmnt btnpointer" style="width:28px;height:28px;" src="img/mail.png" title="مشاهده شرح عملیات"/>' .
                '<div class="permit_info_req_hidden" style="position:absolute;top:0px;display:none;">' .
                '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                '<div class="rightarrowblue"></div>' .
                '</div>' .
                '<div style="float:left;width:400px;" class="cmnt_desc_req radius5 box_sized">' .
                '<div class="cmnt_desc_req_header box_sized" style="overflow:auto;">' .
                '<div style="display:inline-block;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/mail.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح عملیات</div>' .
                '</div>' .
                '<div class="cmnt_desc_req_body box_sized">' . $row['permit_main_sharhamaliat'] . '</div>' .
                '</div>' .
                '</div>' .
                '</td>' .
                '<td style="text-align:center;">' . $row['vahednezarat_name'] . '</td>' .
                '<td style="text-align:center;direction:ltr;">' . $util->format_phone($row['tel_vahedkeshik_permit_main']) . '</td>' .
                '<td style="text-align:center;">' . $row['users_fname'] . ' ' . $row['users_lname'] . '</td>' .
                '<td style="text-align:center;">' . $row['do_activity_name'] . '</td>' .
                '<td style="text-align:center;">' . $row['hozekari_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            $str .=
                '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/places6.png" style="margin:0px;width:30px;height:30px;position: relative;top:3px;" title="مشاهده محل کار فعالیت"/>' .
                '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                '<div class="leftarrow"></div>' .
                '</div>' .
                '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/places6.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;display:inline-block;width:80%;">لیست محل کار</div>' .
                '</div>' .
                '<div class="cmnt_nazer_body box_sized">' . $row['mahal_kar_name'] . '</div>' .
                '</div>' .
                '</div>';
            $str .= '</td>';

            $str .= '<td style="text-align:center;">' . $row['type_mojavez_name'] . '</td>';


            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                $str .=
                    '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/nazer.png" style="margin:0px;width:40px;height:40px;" title="مشاهده توضیحات ناظر"/>' .
                    '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                    '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                    '<div class="leftarrow"></div>' .
                    '</div>' .
                    '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                    '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                    '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                    '<img class="permit_img_show_details" src="img/nazer.png" style="width:20px;height:20px;"/>' .
                    '</div>' .
                    '<div style="position:relative;display:inline-block;width:80%;">توضیحات ناظر</div>' .
                    '</div>' .
                    '<div class="cmnt_nazer_body box_sized">' . $row['permit_main_dalilradnazer'] . '</div>' .
                    '</div>' .
                    '</div>';
            } else {
                $str .= '-';
            }
            $str .= '</td>';

            $str .= '<td style="text-align:center;">';
            if ($row['permit_main_niazbeghaatbargh']) {
                $str .= 'دارد';
            } else {
                $str .= 'ندارد';
            }
            $str .= '</td>';
            if ($bayegani) {
                //gregorian_to_jalali(2011,2,11,' / ');
                $arrdt = explode(' ', $row['permit_main_tarikhsaat_darkhast_bypeimankar']);
                $arrdate = explode('-', $arrdt[0]);
                $fadate = gregorian_to_jalali($arrdate[0], $arrdate[1], $arrdate[2], '/');
                $str .= '<td style="direction: ltr;">';
                $tmp = $fadate;// . '<br/>' . $arrdt[1];
                $str .= str_replace($this->english_digits, $this->persian_digits, $tmp);
                $str .= '</td>';
            }
			
			$str .= '<td style="text-align:center;"><button data_permitid="'.$row['permit_main_id'].'" class="btn_delete_permit" style="margin:1px;">حذف</button></td>';			
			
            $str .= '</tr>';
        }
        return $str;
    }

    public function show_permit_rows_today($result, $roleid, $bayegani = false)
    {
        $str = '';
        $i = 0;
        $util = new UtilClass();
        while ($row = mysqli_fetch_assoc($result)) {
            $nazer_tmp = $row['permit_main_taeedyaradnazer'];
            $occ_tmp = $row['permit_main_taeedyaradocc'];
			$occ_tmp_status = $row['tmp_occ_status'];
			
			$colorname='';
			if($roleid == 3)				
				$colorname = $this->getcolor($nazer_tmp, $occ_tmp_status);
			else		
				$colorname = $this->getcolor($nazer_tmp, $occ_tmp);
			
				
            $str .=
                '<tr class="data_row ' . $colorname . '">' .
                '<td>';
            if ($occ_tmp == 1 && $nazer_tmp == 1) {
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details show_printable_green" src="img/p3.png" style="width:26px;" title="پرینت مجوز"/>';
            } else {
                $nezarat_ofpermit = $row['fkvahednezarat_permit_main_id'];
                //$nezarat_ofuser = $_SESSION["vahednezarat_id"];
                if ($roleid != 2 || /*$nezarat_ofpermit != $nezarat_ofuser ||*/
                    $row['permit_main_taeedyaradnazer'] != NULL
                ) {
                    $tmp_details = 'permit_img_show_details_noedit_info';
                    $imgname = 'info2.png';
                } else {
                    if (!$bayegani) {
                        $tmp_details = 'permit_img_show_details_nazer_info';
                        $imgname = 'edit2.png';
                    } else {
                        $tmp_details = 'permit_img_show_details_noedit_info';
                        $imgname = 'info2.png';
                    }
                }
                $str .= '<img permit_id="' . $row['permit_main_id'] . '" class="permit_img_show_details ' . $tmp_details . '" style="width:24px;" src="img/' . $imgname . '" title="جزئیات"/>';
            }
            $str .= '</td>' .
                '<td style="text-align:center;">' . str_replace($this->english_digits, $this->persian_digits, $row['permit_main_id']) . '</td>' .
                '<td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">';
            
			if($roleid == 3){
				if (!is_null($occ_tmp_status)) {
					if ($occ_tmp_status == 1) {
						$str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
					} else {
						$str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
					}

					/*if ($occ_tmp == 1) {
						//$str .= '<span style="display:block;">شرح شرايط انجام كار</span>';
					} else {
						//$str .= '<span style="display:block;">مجوز توسط OCC رد شد</span>';
					}*/

					$str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
						'<div style="float:right;position:absolute;top:10px;right:-9px;">' .
						'<div class="rightarrow"></div>' .
						'</div>' .
						'<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
						'<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
						'<div style="float:right;margin-left:5px;margin-right:3px;">';
					if ($occ_tmp_status == 1) {
						$str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
					} else {
						$str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
					}
					$str .= '</div>' .
						'<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
						'</div>' .
						'<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
						'</div>' .
						'</div>';
				} else {
					if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
						$str .= 'مجوز توسط ناظر رد شد';
					} else {
						$str .= 'مجوز در حال بررسی می باشد';
					}
				}
			}
			else{
				if (!is_null($occ_tmp)) {
					if ($occ_tmp == 1) {
						$str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/warn.png" style="width:35px;height:35px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
					} else {
						$str .= '<img vhid="true" class="permit_img_show_occ_cmnt btnpointer" src="img/stop1.png" style="width:30px;height:30px;margin:0px auto;" title="مشاهده توضیحات مرکز فرمان"/>';
					}

					/*if ($occ_tmp == 1) {
						//$str .= '<span style="display:block;">شرح شرايط انجام كار</span>';
					} else {
						//$str .= '<span style="display:block;">مجوز توسط OCC رد شد</span>';
					}*/

					$str .= '<div class="permit_occ_hidden" style="position:absolute;top:5px;display:none;">' .
						'<div style="float:right;position:absolute;top:10px;right:-9px;">' .
						'<div class="rightarrow"></div>' .
						'</div>' .
						'<div style="float:left;width:400px;" class="cmnt_occ radius5">	' .
						'<div class="cmnt_occ_header box_sized" style="overflow:auto;">' .
						'<div style="float:right;margin-left:5px;margin-right:3px;">';
					if ($occ_tmp == 1) {
						$str .= '<img class="permit_img_show_details" src="img/warn.png" style="width:20px;height:20px;"/>';
					} else {
						$str .= '<img class="permit_img_show_details" src="img/stop1.png" style="width:20px;height:20px;"/>';
					}
					$str .= '</div>' .
						'<div style="position:relative;display:inline-block;width:80%;">شرح شرايط انجام كار</div>' .
						'</div>' .
						'<div class="cmnt_occ_body box_sized">' . $row['permit_main_dalilradocc'] . '</div>' .
						'</div>' .
						'</div>';
				} else {
					if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
						$str .= 'مجوز توسط ناظر رد شد';
					} else {
						$str .= 'مجوز در حال بررسی می باشد';
					}
				}
			}
            $str .=
                '</td>';
            $str .=
                '<td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">' .
                '<img vhid="true" class="permit_img_show_info_req_cmnt btnpointer" style="width:28px;height:28px;" src="img/mail.png" title="مشاهده شرح عملیات"/>' .
                '<div class="permit_info_req_hidden" style="position:absolute;top:0px;display:none;">' .
                '<div style="float:right;position:absolute;top:10px;right:-9px;">' .
                '<div class="rightarrowblue"></div>' .
                '</div>' .
                '<div style="float:left;width:400px;" class="cmnt_desc_req radius5 box_sized">' .
                '<div class="cmnt_desc_req_header box_sized" style="overflow:auto;">' .
                '<div style="display:inline-block;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/mail.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح عملیات</div>' .
                '</div>' .
                '<div class="cmnt_desc_req_body box_sized">' . $row['permit_main_sharhamaliat'] . '</div>' .
                '</div>' .
                '</div>' .
                '</td>' .
                '<td style="text-align:center;">' . $row['vahednezarat_name'] . '</td>' .
                '<td style="text-align:center;direction:ltr;">' . $util->format_phone($row['tel_vahedkeshik_permit_main']) . '</td>' .
                '<td style="text-align:center;">' . $row['users_fname'] . ' ' . $row['users_lname'] . '</td>' .
                '<td style="text-align:center;">' . $row['do_activity_name'] . '</td>' .
                '<td style="text-align:center;">' . $row['hozekari_name'] . '</td>';

            $str .= '<td style="text-align:center;position:relative;z-index:999;">';
            $str .=
                '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/places6.png" style="margin:0px;width:30px;height:30px;position: relative;top:3px;" title="مشاهده محل کار فعالیت"/>' .
                '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                '<div class="leftarrow"></div>' .
                '</div>' .
                '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                '<img class="permit_img_show_details" src="img/places6.png" style="width:20px;height:20px;"/>' .
                '</div>' .
                '<div style="position:relative;display:inline-block;width:80%;">لیست محل کار</div>' .
                '</div>' .
                '<div class="cmnt_nazer_body box_sized">' . $row['mahal_kar_name'] . '</div>' .
                '</div>' .
                '</div>';
            $str .= '</td>';

            $str .= '<td style="text-align:center;">' . $row['type_mojavez_name'] . '</td>';

            if ($roleid != 3 && $roleid != 4) {
                $str .= '<td style="text-align:center;position:relative;z-index:999;">';
                if (!(is_null($nazer_tmp) || ($nazer_tmp == 1))) {
                    $str .=
                        '<img vhid="true" class="permit_img_show_nazer_cmnt btnpointer" src="img/nazer.png" style="margin:0px;width:40px;height:40px;" title="مشاهده توضیحات ناظر"/>' .
                        '<div class="permit_nazer_hidden" style="position:absolute;top:5px;left:60px;display:none;">' .
                        '<div style="float:left;position:absolute;top:10px;left:-9px;">' .
                        '<div class="leftarrow"></div>' .
                        '</div>' .
                        '<div style="float:right;width:400px;"  class="cmnt_nazer radius5 box_sized">' .
                        '<div class="cmnt_nazer_header box_sized" style="overflow:auto;">' .
                        '<div style="float:right;margin-left:5px;margin-right:3px;">' .
                        '<img class="permit_img_show_details" src="img/nazer.png" style="width:20px;height:20px;"/>' .
                        '</div>' .
                        '<div style="position:relative;display:inline-block;width:80%;">توضیحات ناظر</div>' .
                        '</div>' .
                        '<div class="cmnt_nazer_body box_sized">' . $row['permit_main_dalilradnazer'] . '</div>' .
                        '</div>' .
                        '</div>';
                } else {
                    $str .= '-';
                }
                $str .= '</td>';
            }
            $str .= '<td style="text-align:center;">';
            if ($row['permit_main_niazbeghaatbargh']) {
                $str .= 'دارد';
            } else {
                $str .= 'ندارد';
            }
            $str .= '</td>';
            if (!$bayegani) {
                if ($roleid == 3 || $roleid == 11) //OCC Signed or omooristgah signed
                {
                    $str .= '<td>';
                    if ($row['permit_main_taeedyaradnazer'] == 1) {
                        $str .= '<img permitid="' . $row['permit_main_id'] . '" title="تائید یا رد درخواست" src="img/trp6.png" style="width:34px;height:34px;" class="permit_img_trpermit_req btnpointer" vhid="true"/>';
                    }
                    $str .= '</td>';
                }
            }
            if ($bayegani) {
                $str .= '<td style="direction: ltr;text-align: center;">';
                if ($occ_tmp == 1 && $nazer_tmp == 1) {
                    $arrdt = explode(' ', $row['act_time_date']);
                    $arrdate = explode('-', $arrdt[0]);
                    $fadate = gregorian_to_jalali($arrdate[0], $arrdate[1], $arrdate[2], '/');
                    $tmp = $fadate;// . '<br/>' . $arrdt[1];
                    $str .= str_replace($this->english_digits, $this->persian_digits, $tmp);
                } else {
                    $str .= '-';
                }
                $str .= '</td>';
            }
            $str .= '</tr>';
        }
        return $str;
    }

		
    private function getcolor($nazer, $occ)
    {
	
        if (is_null($nazer) && is_null($occ)) {
            return 'permit_row_white';
        } else if ($nazer == 1 && is_null($occ)) {
            return 'permit_row_yellow';
        } else if ($nazer == 1 && $occ == 1) {
            return 'permit_row_green';
        } else if (($nazer == 0 && is_null($occ)) || ($nazer == 1 && $occ == 0)) {
            return 'permit_row_red';
        }
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
}

?>