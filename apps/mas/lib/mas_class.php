<?php
include 'mas-config.php';

class MASClass
{
    private $dbcon = null;

    public function __construct()
    {

    }

    public function get_sys_from_line($line_id)
    {
        $this->connect();
        $sql = "CALL mas_sp_show_all_system($line_id);";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    public function get_metro_line()
    {
        $this->connect();
        $sql = "CALL mas_sp_show_line();";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    public function get_serial_from_tajhiznp($taj_id,$np_id)
    {
        $this->connect();
        $sql = "CALL mas_sp_show_serial($taj_id,$np_id);";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    public function get_nplace_from_tajhiz($taj_id)
    {
        $this->connect();
        $sql = "CALL mas_sp_get_tajhiz_show_makan($taj_id);";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    public function getsysitems()
    {
        $this->connect();
        $sql = "CALL mas_sp_show_all_system();";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    public function get_equip_frm_subsys($subsysid)
    {
        $this->connect();
        $sql = " CALL mas_sp_show_tajhizat($subsysid);";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    public function get_subsys_items($sysid)
    {
        $this->connect();
        $sql = "CALL mas_sp_show_subsystem($sysid);";
        $res = mysqli_query($this->dbcon, $sql);
        $rows = null;
        $i = 0;
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[$i++] = $row;
            }
        }
        $this->close_connect();
        return $rows;
    }

    private function connect()
    {
        $this->dbcon = mysqli_connect(MASConfigClass::$dbserver, MASConfigClass::$user, MASConfigClass::$pass, MASConfigClass::$dbname);
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

    public function login($user, $pass)
    {
        //connect database
        //call sp
        //free result
        //close
        //return $row or null
    }

    public function __destruct()
    {

    }
}

?>