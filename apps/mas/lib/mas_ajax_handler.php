<?php
//$masajaxObj = new MASAjaxManager();
include 'mas_class.php';

class MASAjaxManager
{
    private $masObj = null;
    private $func;

    public function __construct()
    {
        $this->masObj = new MASClass();
    }

    public function getAllSysItems_ui()
    {
        $rows = $this->masObj->getsysitems();
        $tmp = '';
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['system_id'] . '">' . $row['system_name'] . '</option>';
            }
        }
        return $tmp;
    }

    public function ProcReq()
    {
        if ($this->CheckFuncExist()) {
            switch ($this->func) {
                case 'get_subsys_list';
                    echo $this->get_subsys_from_sysid($_POST['sys_id']);
                    break;
                case 'get_equip_from_subsys';
                    echo $this->get_equip_from_subsys($_POST['subsys_id']);
                    break;
                case 'get_nplace_from_tajhiz';
                    echo $this->get_nplace_from_tajhiz_list($_POST['taj_id']);
                    break;
                case 'get_serial_from_np_tajhiz';
                    echo $this->get_serial_from_tajhiz_np($_POST['taj_id'],$_POST['np_id']);
                    break;
                case 'get_sys_from_line';
                    echo $this->get_sys_from_line_list($_POST['line_id']);
                    break;
            }
        }
    }

    public function get_sys_from_line_list($line_id=105)
    {
        $rows = $this->masObj->get_sys_from_line($line_id);
        $tmp = '';
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['sys_pkid'] . '">' . $row['sys_name'] . '</option>';
            }
        }
        return $tmp;
    }
    public function get_serial_from_tajhiz_np($taj_id,$np_id)
    {
        //ID-Number-Name
        $rows = $this->masObj->get_serial_from_tajhiznp($taj_id,$np_id);
        $tmp = '';
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['serial_id'] . '">' . $row['serial_name'] . '</option>';
            }
        }
        return $tmp;
    }

    public function get_nplace_from_tajhiz_list($taj_id)
    {
        $rows = $this->masObj->get_nplace_from_tajhiz($taj_id);
        $tmp = '';
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['unet_makan_pkid'] . '">' . $row['unet_makan_name'] . '</option>';
            }
        }
        return $tmp;
    }

    public function get_metro_line_list()
    {
        //unet_makan_pkid	unet_makan_assign_number	unet_makan_name
        $rows = $this->masObj->get_metro_line();
        $tmp = '';
        //subsystem_id	subsystem_name
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['sys_pkid'] . '">' . $row['sys_name'] . '</option>';
            }
        }
        return $tmp;
    }

    private function get_subsys_from_sysid($sysid)
    {
        $rows = $this->masObj->get_subsys_items($sysid);
        $tmp = '';
        //subsystem_id	subsystem_name
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['subsystem_id'] . '">' . $row['subsystem_name'] . '</option>';
            }
        }
        return $tmp;
    }

    private function get_equip_from_subsys($subsysid)
    {
        $rows = $this->masObj->get_equip_frm_subsys($subsysid);
        $tmp = '';
        //equipID	equipName
        if ($rows != null) {
            foreach ($rows as $row) {
                $tmp .= '<option value="' . $row['equipID'] . '">' . $row['equipName'] . '</option>';
            }
        }
        return $tmp;
    }

    private function login($user, $pass)
    {
        //class.func() => normalize the args (security,SQL Injection,...)
        $row = $this->permitClass->login($user, $pass);
        if (!is_null($row)) {
            //create session
            //redirect the user to portal
        } else {
            //announce login: user , pass failed
            //we need captcha for spammer
        }
    }

    private function refresh()
    {

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