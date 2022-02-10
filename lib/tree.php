<?php

class Tree
{
    private $mysql_host;
    private $mysql_user;
    private $mysql_pass;
    private $mysql_dbname;
    private $mysql_con = null;
    private $mysql_result = null;

    public function __construct()
    {
        $this->mysql_host = TreeConfigClass::$HOST;
        $this->mysql_user = TreeConfigClass::$USER;
        $this->mysql_pass = TreeConfigClass::$PASS;
        $this->mysql_dbname = TreeConfigClass::$DbNAME;
    }

    private function cleanFilterData($data)
    {
        return $data;
    }

    public function handleRquest($func)
    {
        $func = $this->cleanFilterData($func);
        switch ($func) {
            case 'get_node_children':
                echo json_encode($this->getChildren($_POST['nid'], $_POST['newnode']));
                break;
            case 'editnodelbl':
                echo($this->editNode($_POST['nid'], $_POST['nname']) ? 'ok' : 'err');
                break;
        }
    }


    public function editNode($nid, $nodelbl)
    {
        $this->conToDB();
        if (!is_null($this->mysql_con)) {
            $sql = "call food_sp_edit_node($nid,'$nodelbl');";
            $res = mysqli_query($this->mysql_con, $sql);
            if ($res) {
                return true;
            }
        }
        return false;
    }

    private function conToDB()
    {
        $this->mysql_con = mysqli_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass, $this->mysql_dbname);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {
            mysqli_set_charset($this->mysql_con, "utf8");
        }
    }

    public function __destruct()
    {

    }

    public function getRootNode()
    {
        $node = null;
        $this->conToDB();
        if (!is_null($this->mysql_con)) {
            $sql = "call food_sp_getrootnode();";
            $this->mysql_result = mysqli_query($this->mysql_con, $sql);
            if ($this->mysql_result) {
                if (mysqli_num_rows($this->mysql_result) == 1) {
                    $row = mysqli_fetch_assoc($this->mysql_result);
                    $node = new Node($row['item_id'], $row['name'], $row['parent_id'], $row['item_order']);
                    $this->closeFreeDBcon($this->mysql_result);
                }
            }
        }
        return $node;
    }

    private function closeFreeDBcon(&$result = null)
    {
        if (!is_null($result)) {
            mysqli_free_result($result);
        }
        mysqli_close($this->mysql_con);
    }

    public function nodeLevel($nodeid)
    {
        $this->conToDB();
        if (!is_null($this->mysql_con)) {
            $sql = "call food_sp_get_node_level($nodeid);";
            $res = null;
            $res = mysqli_query($this->mysql_con, $sql);
            if (!$res) echo mysqli_error($this->mysql_con);
            if ($res) {
                if (mysqli_num_rows($res) == 1) {
                    $row = mysqli_fetch_assoc($res);
                    return $row['depth'];
                }
            }
        }
        return 0;
    }

    public function isLeaf($nodeid)
    {
        $this->conToDB();
        if (!is_null($this->mysql_con)) {
            $sql = "call food_sp_check_is_leaf($nodeid);";
            $res = null;
            $res = mysqli_query($this->mysql_con, $sql);
            if (!$res) echo mysqli_error($this->mysql_con);
            if ($res) {
                if (mysqli_num_rows($res) == 1) {
                    $row = mysqli_fetch_assoc($res);
                    if ($row['isleaf'] == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return false;
    }

    public function addNode($parentid, $name)
    {
        $sql = "INSERT INTO `food_tbl_item`( `name`, `parent_id`, `item_isvis`, `item_order`) VALUES ('$name',$parentid,1,1)";
        if( mysqli_query($this->mysql_con, $sql))
            return $this->mysql_con->insert_id;
        return 0;
    }

    public function deleteNode($node)
    {

    }

    public function getChildren($nodeid, $newnode)
    {
        $onetry = true;
        $nodes = null;
        $depth = 0;
        $this->conToDB();
        $new_item = array();
        if ($newnode) {
            $new_item['new_item'] = $this->addNode($nodeid, 'آیتم جدید');
            if (!$new_item['new_item']) {
                $this->closeFreeDBcon($this->mysql_result);
                return '';
            }else{
                $nodes[] =  $new_item;
            }
        }
        if (!is_null($this->mysql_con)) {
            $sql = "call food_sp_getchilds($nodeid);";
            $this->mysql_result = mysqli_query($this->mysql_con, $sql);
            if ($this->mysql_result) {
                if (mysqli_num_rows($this->mysql_result) > 0) {
                    while ($row = mysqli_fetch_assoc($this->mysql_result)) {
                        if ($onetry) {
                            $depth = $this->nodeLevel($nodeid);
                            $onetry = false;
                        }
                        $tmp_nodeid = $row['item_id'];
                        $row['leaf'] = 0;
                        $row['depth'] = $depth;
                        if ($this->isLeaf($tmp_nodeid)) $row['leaf'] = 1;
                        $nodes[] = $row;
                    }
                    $this->closeFreeDBcon($this->mysql_result);
                }
            }
        }
        return $nodes;
    }


    public function moveNode($src_node, $dst_node)
    {

    }
}