<?php
include 'optplan-config.php';

class OPTPlanAjaxManager
{
    private $dbcon = null;
    private $func;

    private function connect()
    {
        $this->dbcon = mysqli_connect(OPTPlanConfigClass::$dbserver, OPTPlanConfigClass::$user, OPTPlanConfigClass::$pass, OPTPlanConfigClass::$dbname);
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

    public function __construct()
    {
        //$this->masObj = new MASClass();
    }

    public function ProcReq()
    {
        if ($this->CheckFuncExist()) {
            switch ($this->func) {
                case 'file_upload';
                    echo $this->UploadTrafficImg();
                    break;
                case 'fupload_tomorrow';
                    echo $this->UploadTrafficImg_tomorrow();
                    break;
                case 'note_today';
                    echo $this->note_for_today($_POST['notes']);
                    break;
                case 'note_tomorrow';
                    echo $this->set_note_for_tomorrow($_POST['notes']);
                    break;
                case 'opt_for_today';
                    echo $this->set_opt_for_today($_POST['notes']);
                    break;
                case 'opt_for_tomorrow';
                    echo $this->set_opt_for_tomorrow($_POST['notes']);
                    break;
                case 'save_hint_txt';
                    echo $this->set_hint_txt($_POST['notes']);
                    break;
            }
        }
    }

    public  function canedit($userid)
    {
        $this->connect();
        $ret = false;
        $sql = "Call optplan_sp_edit_permit('$userid');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_num_rows($res)==1) {
            $ret = true;
        }
        $this->close_connect();
        return $ret;
    }
    public function UploadTrafficImg_tomorrow()
    {
        if (0 < $_FILES['fupload_tom_tag']['error']) {
            return false;//'Error: ' . $_FILES['fupload']['error'] . '<br>';
        } else {
            $check = true;// getimagesize($_FILES["fupload"]["tmp_name"]);
            if ($check) {
                $arrtmp = explode('.', $_FILES["fupload_tom_tag"]["name"]);
                $imageFileType = strtolower($arrtmp[1]);
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    $imgname = time() . '.' . $imageFileType;
                    if (move_uploaded_file($_FILES['fupload_tom_tag']['tmp_name'], '../img/' . $imgname)) {
                        return $this->saveimgfortoday_tomorrow($imgname);
                    }
                }
            }
        }
        return '';
    }

    private function saveimgfortoday_tomorrow($imgname)
    {
        $this->connect();
        $ret = '';
        $sql = "Call optplan_sp_save_img_tommorow('$imgname');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = $imgname;
        }
        $this->close_connect();
        return $ret;
    }

    public function get_saved_img_tomorrow()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_tomorrow_info();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['imgname_optplan_tbl_main'];
    }

    public function get_saved_img_today()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_today_note();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['imgname_optplan_tbl_main'];
    }

    public function UploadTrafficImg()
    {
        if (0 < $_FILES['fupload']['error']) {
            return false;//'Error: ' . $_FILES['fupload']['error'] . '<br>';
        } else {
            $check = true;// getimagesize($_FILES["fupload"]["tmp_name"]);
            if ($check) {
                $arrtmp = explode('.', $_FILES["fupload"]["name"]);
                $imageFileType = strtolower($arrtmp[1]);
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    $imgname = time() . '.' . $imageFileType;
                    if (move_uploaded_file($_FILES['fupload']['tmp_name'], '../img/' . $imgname)) {
                        return $this->saveimgfortoday($imgname);
                    }
                }
            }
        }
        return '';
    }

    private function saveimgfortoday($imgname)
    {
        $this->connect();
        $ret = '';
        $sql = "Call optplan_sp_save_img_today('$imgname');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = $imgname;
        }
        $this->close_connect();
        return $ret;
    }

    public function set_hint_txt($text)
    {
        $this->connect();
        $sql = "Call optplan_sp_set_hint_txt('$text');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = true;
        } else {
            $ret = false;
        }
        $this->close_connect();
        return $ret;
    }

    public function get_hint_txt()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_today_hint();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['txt_optplan_tbl_hints'];
    }

		
	private function normalizetext($text)
	{		
		//&lt;![if !vml]&gt;
		$aa = array("&lt;![if !vml]&gt;","&lt;![if !supportLists]&gt;","&lt;![endif]&gt;","&lt;script","&lt;/script&gt;","<script","</script>");
		$bb   = array(" "," "," ","<lol","</lol>","<lol","</lol>");
		return addslashes(str_replace($aa, $bb, $text));
	}
    public function set_opt_for_today($text)
    {
        $this->connect();
		$text =  $this->normalizetext($text);
        $sql = "Call optplan_sp_upsert_for_opt('$text');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = true;
        } else {
            $ret = false;
        }
        $this->close_connect();
        return $ret;
    }

    public function set_opt_for_tomorrow($text)
    {
        $this->connect();
		$text =  $this->normalizetext($text);
        $sql = "Call optplan_sp_upsert_for_opt_tomorrow('$text');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = true;
        } else {
            $ret = false;
        }
        $this->close_connect();
        return $ret;
    }

    public function get_opt_for_today()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_today_note();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['opt_plan_txt_optplan_tbl_main'];
    }
    public function get_opt_for_tomorrow()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_tomorrow_info();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['opt_plan_txt_optplan_tbl_main'];
    }
	
	public function get_note_for_print_permit($permit_date)
	{
		$this->connect();
        $sql = "Call optplan_sp_get_note_for_print_permit_date('$permit_date');";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['optnotes_optplan_tbl_main'];
	}
	
	public function get_opt_for_print_permit($permit_date)
	{
		$this->connect();
        $sql = "Call optplan_sp_get_opt_for_print_permit_date('$permit_date');";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['opt_plan_txt_optplan_tbl_main'];
	}

    public function get_note_for_today()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_today_note();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['optnotes_optplan_tbl_main'];
    }

    public function get_note_for_tomorrow()
    {
        $this->connect();
        $sql = "Call optplan_sp_get_tomorrow_info();";
        $res = mysqli_query($this->dbcon, $sql);
        $cell = mysqli_fetch_assoc($res);
        $this->close_connect();
        return $cell['optnotes_optplan_tbl_main'];
    }

    public function set_note_for_tomorrow($text)
    {
        $this->connect();
		$text =  $this->normalizetext($text);
        $sql = "Call optplan_sp_upsert_note_tomorrow('$text');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = true;
        } else {
            $ret = false;
        }
        $this->close_connect();
        return $ret;
    }

    public function note_for_today($text)
    {
		//return $text;
        $this->connect();		
		$text =  $this->normalizetext($text);
        $sql = "Call optplan_sp_upsert_note('$text');";
        $res = mysqli_query($this->dbcon, $sql);
        if (mysqli_affected_rows($this->dbcon)) {
            $ret = true;
        } else {
            $ret = false;
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