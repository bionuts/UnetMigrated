<?php
session_start();
require '../../../config/food_config.php';
include '../../../util/util.php';
$util = new UtilClass();
if(!$util->haveAcces('food',$_SESSION["userid"]))exit;
if (!isset($_REQUEST['act'])) 
{
    // set the default timezone to use. Available since PHP 5.1
    /*date_default_timezone_set('Asia/Tehran');
    $tttt = time();
    echo $tttt . '<br>';
    echo date("y/m/d H:i:s", 0) . '<br>';
    echo date("y/m/d H:i:s", -2112 * 86400) . '<br>';
    echo milady_to_shamsi($tttt) . '<br>';*/
    die ('no act');
}

function is_name($name)
{
    if (strpos($name, "'") !== false)
        return false;
    return true;
}

$act = $_REQUEST['act'];

class details
{
    public $ctime = -1;
    public $cweek = -1;
	public $weeks = -1;
    public $selction = -1;
	public $cdayweek = -1;
	public $showmoney = -1;
}

//
//
//

function milady_to_shamsi($intdate)
{
    $s5 = 0;
    $s1 = floor(($intdate + 12600) / 86400 + 2112);
    $s2 = $s1 % 12053;
    $s1 = $s1 - $s2;
    $Y33 = $s1 / 12053;
    $s3 = $s2 % 1461;
    $s2 = $s2 - $s3;
    $Y4 = $s2 / 1461;
    if ($Y4 == 8) $s5++;
    if ($Y4 == 7 && $s3 == 1460) {
        $Y4 = 8;
        $s3 = 0;
    }
    $s4 = $s3 % 365;
    $s3 = $s3 - $s4;
    $Y1 = $s3 / 365;
    if ($Y1 == 4) {
        $Y1 = 3;
        $s4 = 365;
    }
    $s4 += $s5;
    if ($s4 > 185) {
        $s4 = $s4 - 186;
        $D = $s4 % 30;
        $s4 = $s4 - $D;
        $M = 6 + $s4 / 30;
    } else {
        $D = $s4 % 31;
        $s4 = $s4 - $D;
        $M = $s4 / 31;
    }
    return ("" . (1343 + $Y33 * 33 + $Y4 * 4 + $Y1) . "/" . ($M + 1) . "/" . ($D + 1));
}

function shamsi_to_milady_days($str)
{
    $tmp = explode('/', $str);
	if(!is_numeric($tmp[0]) || !is_numeric($tmp[0]) || !is_numeric($tmp[0]))
		 die ('error data');
    $Y = ((int)$tmp[0]) - 1343;
    $s1 = $Y % 33;
    $Y -= $s1;
    $sday = $Y / 33 * 12053;
    $s2 = floor($s1 / 4);
    if ($s2 > 7)
        $s2 = 7;
    $sday += $s1 * 365 + $s2;
    $M = ((int)$tmp[1]);
    $sday += ($M - 1) * 30;
    if ($M > 6)
        $sday += 6;
    else
        $sday += ($M - 1);
    $D = ((int)$tmp[2]);
    $sday += $D - 2113;
    return $sday;
    //echo "Created date is " . date("Y-m-d h:i:sa", $sday * 86400);
}

function getPermitMulti()
{
	global $user;
	$user = $_SESSION["userid"];
	$conn = connect();
	$sql = "SELECT `food_role_id` FROM `food_tbl_role` WHERE `user_id` = $user AND (`food_role`='multifood' OR `food_role`='admin')";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		 if ($result->num_rows) {
			$conn->close();
			return '1';
		 }
	}
	$conn->close();
	return '0';
}

function getPermitAdmin()
{
	global $user;
	$conn = connect();
	$sql = "SELECT `food_role_id` FROM `food_tbl_role` WHERE `user_id` =$user AND `food_role`='admin'";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		 if ($result->num_rows) {
			$conn->close();
			return '1';
		 }
	}
	$conn->close();
	return '0';
}

function getPermitSupervisor()
{
	global $user;
	$conn = connect();
	$sql = "SELECT `food_role_id` FROM `food_tbl_role` WHERE `user_id` =$user AND `food_role`='supervisor'";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		 if ($result->num_rows) {
			$conn->close();
			return '1';
		 }
	}
	$conn->close();
	return '0';
}

function get_details($conn, $ttt)
{
    $det = new details();
    $sql = "SELECT * FROM food_tbl_setting ";
    $result = $conn->query($sql);
	$time = time();
    if ($result == TRUE) {
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                switch ($row['key']) {
                    case 'weeks':
                        $weeks = $row['value'];
                        break;
                    case 'currentDay':
                        $ctime = $row['value'];
                        break;
                    case 'currentWeek':
                        $cweek = (int)$row['value'];
                        break;
                    case 'numberDayForSelction':
                        $selction = (int)$row['value'];
                        break;
					case 'ShowMoney':
                        $showmoney = (int)$row['value'];
                        break;
                }
            }
        }
		$mildasy = shamsi_to_milady_days($ctime);
        $todays = time();
        $days = floor(($todays + 12600) / 86400);
        $date_today = milady_to_shamsi($todays);
        $days -= $mildasy;
		$cdweek = ($mildasy - 2) % 7 ; 
        $w = date('w', $todays + 12600) + 1;
        if ($w > 6)
            $w -= 7;
        $edit = false;
        $cdweek += $days;
        if ($days > 0)
            $edit = true;
        while ($cdweek > 6) {
            $cdweek -= 7;
            $cweek++;
            if ($cweek == $weeks)
                $cweek = 0;
        }
		if ($cdweek != $w){
			$c1 = -4800;
			$tmp = $cdweek + 1;
			if($tmp > 6) $tmp -= 7 ;
			if($tmp != $w){
				$c1 = 4800;
				$tmp = $cdweek - 1;
				if($tmp < 0) $tmp += 7 ;
			}
			if($tmp == $w){
				$days = floor(($todays + 12600) / 86400);
				$date_today = milady_to_shamsi($todays);
				$days -= shamsi_to_milady_days($ctime);
				$w = date('w', $todays + 12600  + $c1 ) + 1;
				if ($w > 6)
					$w -= 7;
				$edit = false;
				$cdweek += $days;
				if ($days > 0)
					$edit = true;
				while ($cdweek > 6) {
					$cdweek -= 7;
					$cweek++;
					if ($cweek == $weeks)
						$cweek = 0;
				}
			}
		}
        if ($cdweek != $w) {
            //echo "$cdweek != $w";
        } else {
            if ($edit) {
               
                $sql = "UPDATE food_tbl_setting SET value='$date_today',timeupdate=$time WHERE `key`='currentDay'";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    $sql = "UPDATE food_tbl_setting SET value='$cdweek',timeupdate=$time WHERE `key`='currentDayOfWeek'";
                    $result = $conn->query($sql);
                    if ($result == TRUE) {
                        $sql = "UPDATE food_tbl_setting SET value='$cweek',timeupdate=$time WHERE `key`='currentWeek'";
                        $result = $conn->query($sql);
                        if ($result == TRUE) {

                        } else {
                            echo "1Error: " . $sql . "<br>" . $conn->error;//****************************************************
                        }
                    } else {
                        echo "1Error: " . $sql . "<br>" . $conn->error;//****************************************************
                    }
                } else {
                    echo "2Error: " . $sql . "<br>" . $conn->error;//****************************************************
                }
            }
        }
		$det->weeks = $weeks;
        $det->ctime = $date_today;
		$det->showmoney = $showmoney;
		$det->selction = $selction;
		 
        if ($ttt == 1 || $edit) {
            $det->cweek = $cweek;
            $det->cdayweek = $cdweek;
        }
        //echo "<br>currentTime:$ctime<br>CurrentWeek:$cweek<br>DayOfWeek:$cdweek<br>selction:$selction<br>selectFoodFromTime:$sft<br>selectFoodFromTime:$dsft<br>selectFoodFromTime$psft<br>$days<br>";
        //echo shamsi_to_milady_days($ctime);

    }
    return $det;
}

function check_can_save($conn, $det, $val)
{
	$days = shamsi_to_milady_days($det->ctime) * 3;
	if ($val < $days || $val >=  $days + $det->selction * 3)
	{
		return false;
	}
    $sql = "SELECT * FROM `food_tbl_taeed` WHERE `taeed_code` = $val AND `taeed_type` = 1";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        if ($result->num_rows) {
			return false;
		}
		return true;	
	}
	return false;
}

//
//
//

function get_list_tablecloth($time)
{
    if (!is_numeric($time))
        die ('error data');
    $conn = connect();
    //echo $time;
    if ($time == 1) {

        $sql = "SELECT food_tbl_meal_item.meal_id as id, GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, isvis, price"
            . " FROM food_tbl_meal_item"
            . " INNER JOIN food_tbl_item"
            . " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
            . " INNER JOIN food_tbl_meal"
            . " ON isvis=1 AND food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
            . " GROUP by food_tbl_meal_item.meal_id";
    } else {
        $sql = "SELECT food_tbl_meal_item.meal_id as id, GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, isvis, price"
            . ", food_tbl_meal.timeupdate FROM food_tbl_meal_item"
            . " INNER JOIN food_tbl_item"
            . " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
            . " INNER JOIN food_tbl_meal"
            . " ON food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
            . " GROUP by food_tbl_meal_item.meal_id"
            . " HAVING food_tbl_meal.timeupdate>$time OR MAX(food_tbl_meal_item.timeupdate)>$time";
    }

    $result = $conn->query($sql);
    if ($result == TRUE) {
        $json = array();
        $json['time'] = time();
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
        }
        echo json_encode($json);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function get_list_tablecloth_user($time, $det)
{
    if (!is_numeric($time))
        die ('error data');
    $conn = connect();
    if (isset($det))
        $det = get_details($conn, 0);
    else
        $det = get_details($conn, $time);
    //echo $time;
    if ($time == 1) {
        $sql = "SELECT food_tbl_meal_item.meal_id as id, GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, isvis, price"
            . ", food_tbl_meal.timeupdate as t1, MAX(food_tbl_meal_item.timeupdate) as t2 FROM food_tbl_meal_item"
            . " INNER JOIN food_tbl_item"
            . " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
            . " INNER JOIN food_tbl_meal"
            . " ON isvis=1 AND food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
            . " GROUP by food_tbl_meal_item.meal_id";
    } else {
        $sql = "SELECT food_tbl_meal_item.meal_id as id, GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, isvis, price"
            . ", food_tbl_meal.timeupdate as t1, MAX(food_tbl_meal_item.timeupdate) as t2 FROM food_tbl_meal_item"
            . " INNER JOIN food_tbl_item"
            . " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
            . " INNER JOIN food_tbl_meal"
            . " ON food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
            . " GROUP by food_tbl_meal_item.meal_id"
            . " HAVING food_tbl_meal.timeupdate>$time OR MAX(food_tbl_meal_item.timeupdate)>$time";
    }

    $result = $conn->query($sql);
    if ($result == TRUE) {
        $json = array();
        if ($det->ctime != -1) $json['ctime'] = $det->ctime;
        if ($det->cweek != -1) $json['cweek'] = $det->cweek;
        if ($det->selction != -1) $json['selction'] = $det->selction;
        if ($det->cdayweek != -1) $json['cdayweek'] = $det->cdayweek;
		if ($det->showmoney != -1) $json['showmoney'] = $det->showmoney;
        $json['time'] = time();
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
        }

        if ($time == 1) {
            $sql = "SELECT dutyplace_id as id,place_name as place FROM unet_tbl_dutyplace ";
            $result = $conn->query($sql);
            if ($result->num_rows) {
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
            }
        }
        echo json_encode($json);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function create_meal_weeks($day_part, $meal_id, $week)
{
    if (!is_numeric($day_part) || !is_numeric($meal_id) || !is_numeric($week))
        die ('error data');
    $conn = connect();
    $sql = "SELECT * FROM food_tbl_meal_week WHERE week=$week AND day_part=$day_part AND meal_id=$meal_id AND isvis=1";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        if ($result->num_rows == 0) {
            $time = time();
            $sql = "INSERT INTO food_tbl_meal_week(week, day_part, meal_id,isvis, timeupdate) "
                . " VALUES ($week,$day_part,$meal_id,1,$time)";
            $result = $conn->query($sql);
            if ($result == TRUE) {

            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function move_meal_weeks($meal_week_id, $day_part, $meal_id, $week)
{
    if (!is_numeric($day_part) || !is_numeric($meal_id) || !is_numeric($week) || !is_numeric($meal_week_id))
        die ('error data');
    $conn = connect();
    $sql = "SELECT * FROM food_tbl_meal_week WHERE week=$week AND day_part=$day_part AND meal_id=$meal_id AND isvis = 1";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        if ($result->num_rows == 0) {
            $time = time();
            $sql = "UPDATE food_tbl_meal_week "
                . " SET week=$week,day_part=$day_part,meal_id=$meal_id,timeupdate=$time"
                . " WHERE meal_weeks_id=$meal_week_id AND isvis = 1";
            $result = $conn->query($sql);
            if ($result == TRUE) {

            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function delete_meal_weeks($meal_week_id)
{
    if (!is_numeric($meal_week_id))
        die ('error data');
	$time = time();
    $conn = connect();
	 $sql = "UPDATE food_tbl_meal_week "
                . " SET isvis = 0,timeupdate=$time"
                . " WHERE meal_weeks_id=$meal_week_id";
    //$sql = "DELETE FROM food_tbl_meal_week WHERE meal_weeks_id=$meal_week_id";
	//SELECT * FROM food_tbl_meal_week WHERE week=$week AND day_part=$day_part AND meal_id=$meal_id
    $result = $conn->query($sql);
    if ($result == TRUE) {

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function get_next_list_meal_weeks($week, $next)
{
    if (!is_numeric($week))
        die ('error data');
    $conn = connect();
    $sql = "SELECT `value` FROM `food_tbl_setting` WHERE `key`='weeks'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $c = (int)$row['value'];
                if ($c < 1)
                    $c = 1;
            }
            if ($next) {
                $week++;
                if ($week >= $c)
                    $week = 0;
            } else {
                $week--;
                if ($week < 0)
                    $week = $c - 1;
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
    return $week;
}

function get_list_meal_weeks($week, $count, $lastid, $time, $isweek = false)
{
    if (!is_numeric($week) || !is_numeric($lastid) || !is_numeric($time) || !is_numeric($count))
        die ('error data');
    $conn = connect();
    $sql = "SELECT COUNT(*) as count,MAX(meal_weeks_id) AS lastid FROM food_tbl_meal_week WHERE week=$week AND isvis = 1";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $lid = (int)$row['lastid'];
                $c = (int)$row['count'];
            }

            if ($count != $c || $lid != $lastid) {
                $sql = "SELECT meal_weeks_id as id,week,day_part,meal_id FROM food_tbl_meal_week WHERE week=$week AND isvis = 1";
            } else {
                $sql = "SELECT meal_weeks_id as id,week,day_part,meal_id FROM food_tbl_meal_week WHERE week=$week AND timeupdate>$time AND isvis = 1";
            }
            $result = $conn->query($sql);
            if ($result == TRUE) {
                $json = array();
                $json['time'] = time();
                $json['lastid'] = 0;
                $json['count'] = 0;
                if ($isweek)
                    $json['week'] = $week;
                if ($result->num_rows) {
                    $json['lastid'] = $lid;
                    $json['count'] = $c;
                    while ($row = $result->fetch_assoc()) {
                        $json[] = $row;
                    }
                }
            }

        }
        echo json_encode($json);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function get_selected_food_user($user, $saturday)
{
    if (!is_numeric($user) || !is_numeric($saturday))
        die ('error data');
    //echo $date_start;
    //echo ' '.time();
    $conn = connect();
    $sql = "SELECT meal_weeks_id as id, how_many,ext,status,dutyplace_id as placeid,timeupdate FROM food_tbl_user_meal_week WHERE user_id = $user AND saturday=$saturday";
    //echo $sql;
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $json = array();
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
        }
		$t1 = $saturday * 3 ;
		$t2 = $t1 + 20;
		$sql = "SELECT `taeed_code`as `code` FROM `food_tbl_taeed` WHERE `taeed_code` BETWEEN $t1 AND $t2 AND `taeed_type` = 1";
		//echo $sql;
		$result = $conn->query($sql);
		if ($result == TRUE) {
			if ($result->num_rows) {
				while ($row = $result->fetch_assoc()) {
					$json[] = $row;
				}
			}
		}
        echo json_encode($json);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
}

function save_list_meal_weeks($user, $json, $placeid, $extra, $saturday, $dt, $permit)
{
	$extra = htmlspecialchars($extra);
    if (!is_numeric($user) || !is_numeric($saturday)|| !is_numeric($placeid)|| !is_numeric($permit)|| !is_name($dt))
        die ('error data');
    $obj = json_decode($json);
    $conn = connect();
    $time = time();
	$det = get_details($conn, 1);
	$tmp = @explode(',', $dt);
	if(isset($tmp[0]) && isset($tmp[1]))
	{
		$dsft = $tmp[0];
		$psft = (int)$tmp[1];
		$codedays = shamsi_to_milady_days($dsft) * 3 + $psft;	
		if(!check_can_save($conn, $det, $codedays))
		{
			die("dont save");
		}
	}
	else 
		die ('error data'. $dt);
	$countobjvalue = 0;
	foreach ($obj as $counter => $objvalue) {
		if (!is_numeric($counter) || !is_numeric($objvalue))
            die ('error data');
		if ($objvalue) {
			$countobjvalue += $objvalue;
		}
	}
	
	if($countobjvalue> 1 || $permit>0){
		if(getPermitMulti() =='0')
			exit;
	}
	
    foreach ($obj as $counter => $objvalue) {
        if (!is_numeric($counter) || !is_numeric($objvalue))
            die ('error data');
        if ($objvalue) {
            $sql = "SELECT meal_weeks_id FROM food_tbl_user_meal_week"
                . " WHERE meal_weeks_id=$counter AND user_id = $user AND saturday = $saturday";
			if($objvalue < 2)
				$extra = "";
            $result = $conn->query($sql);
            if ($result == TRUE) {
                if ($result->num_rows) {
					if($permit>0)
					{
						$sql = "UPDATE food_tbl_user_meal_week SET how_many=$objvalue, ext='$extra', timeupdate=$time, dutyplace_id=$placeid "
							. " WHERE meal_weeks_id=$counter AND user_id = $user AND saturday = $saturday AND status = 0";
						$result = $conn->query($sql);
						if ($result == TRUE) {

						}
					}
					else{
						$sql = "UPDATE food_tbl_user_meal_week SET how_many=0 , timeupdate=$time,dutyplace_id=$placeid "
							. " WHERE code_days=$codedays AND user_id = $user AND saturday = $saturday AND status = 0";
						$result = $conn->query($sql);
						if ($result == TRUE) {
							$sql = "UPDATE food_tbl_user_meal_week SET how_many=1 ,timeupdate=$time,dutyplace_id=$placeid "
								. " WHERE meal_weeks_id=$counter AND user_id = $user AND saturday = $saturday AND status = 0";
							$result = $conn->query($sql);
							if ($result == TRUE) {

							}
						}
					}
                } else {
                    if($permit>0)
					{
						$sql = "INSERT INTO food_tbl_user_meal_week( meal_weeks_id, user_id, dutyplace_id, code_days, saturday, how_many , ext, status, timeupdate)"
							. "VALUES ($counter,$user,$placeid,$codedays,$saturday,$objvalue,'$extra',0,$time)";
						$result = $conn->query($sql);
						if ($result == TRUE) {

						}
					}
					else{
						$sql = "UPDATE food_tbl_user_meal_week SET how_many=0 ,timeupdate=$time,dutyplace_id=$placeid "
							. " WHERE code_days=$codedays AND user_id = $user AND saturday = $saturday AND status = 0";
						$result = $conn->query($sql);
						if ($result == TRUE) {
							$sql = "INSERT INTO food_tbl_user_meal_week( meal_weeks_id, user_id, dutyplace_id, code_days, saturday, how_many, status, timeupdate)"
								. "VALUES ($counter,$user,$placeid,$codedays,$saturday,1,0,$time)";
							$result = $conn->query($sql);
							if ($result == TRUE) {

							}
						}
					}
                }
            }
        } else {
            $sql = "UPDATE food_tbl_user_meal_week SET how_many=0, ext='',timeupdate=$time,dutyplace_id=$placeid "
                . " WHERE meal_weeks_id=$counter AND user_id = $user AND saturday = $saturday AND status = 0";
            $result = $conn->query($sql);
            if ($result == TRUE) {

            }
        }
    }
    $conn->close();
}

function get_list_day_meal($day, $part, $isprint = false)
{
	$echostring = "";
	if (!is_numeric($part))
		die ('error data');
	$conn = connect();
	$det = get_details($conn, 1);
	$json = array();
	$cweek = $det->cweek;
	$cdays = shamsi_to_milady_days($det->ctime);
	$sql = "SELECT * FROM `unet_tbl_dutyplace`";
    $result = $conn->query($sql);
    if ($result == TRUE) {
		$json = array();
		$json['ctime'] = $cdays;		
		
		if($day != '0'){	
			$days = shamsi_to_milady_days($day);
			$dayofweek = ($days - 2) % 7;
			$dif = $days + $det->cdayweek - $cdays;
			$dif = $dif % 7;
			if($dif < 0) $dif += 7;
			//$saturday = $days - $dif;
			//$day_part = $dif * 3 + $part ;
			$json['selday'] = $days;	
		}
		//$json['cweek'] = $cweek;
		//$json['cdayweek'] = $dif;
		//$json['day_part'] = $day_part;
		//$json['saturday'] = $saturday;
		if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                 $json[] = $row;
            }
        }
		if($day != '0')
		{	
			if($dayofweek == $dif)
			{
				$t2  = false;
				$t1 = $days * 3 + $part;
				$sql = "SELECT `taeed_code`as `code` FROM `food_tbl_taeed` WHERE `taeed_code` = $t1 AND `taeed_type` = 1";
				//$json['query1'] = $sql;
				$result = $conn->query($sql);
				if ($result == TRUE) {
					if ($result->num_rows) {
						$json['code'] = $t1;
						$t2 = true;
					}
				}
				
				//die($t1);
				if($t2){
					$sql_g = "SELECT food_tbl_meal_week.meal_id FROM `food_tbl_user_meal_week`"
						. " INNER JOIN food_tbl_meal_week"
						. " ON food_tbl_user_meal_week.code_days=$t1 AND  food_tbl_user_meal_week.status=1 AND food_tbl_meal_week.meal_weeks_id= food_tbl_user_meal_week.meal_weeks_id"
						. " GROUP by food_tbl_meal_week.meal_id";
					//$json['sql_g'] = $sql_g;	
					$sql = "SELECT food_tbl_meal_item.meal_id , GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items"
						. " FROM food_tbl_meal_item"
						. " INNER JOIN food_tbl_item"
						. " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
						. " INNER JOIN (" . $sql_g
						. ") as selected"
						. " ON selected.meal_id = food_tbl_meal_item.meal_id"
						. " GROUP by food_tbl_meal_item.meal_id";
				}
				else{
					$sql_g = "SELECT food_tbl_meal_week.meal_id FROM `food_tbl_user_meal_week`"
						. " INNER JOIN food_tbl_meal_week"
						. " ON food_tbl_user_meal_week.code_days = $t1 AND food_tbl_meal_week.isvis=1 AND food_tbl_meal_week.meal_weeks_id= food_tbl_user_meal_week.meal_weeks_id"
						. " GROUP by food_tbl_meal_week.meal_id";
					//$json['sql_g'] = $sql_g;	
					$sql = "SELECT food_tbl_meal_item.meal_id , GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items"
						. " FROM food_tbl_meal_item"
						. " INNER JOIN food_tbl_item"
						. " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
						. " INNER JOIN (" . $sql_g
						. ") as selected"
						. " ON selected.meal_id = food_tbl_meal_item.meal_id"
						. " GROUP by food_tbl_meal_item.meal_id";

				}
				//$json['query2'] = $sql;
				$result = $conn->query($sql);
				if ($result == TRUE) {
					if ($result->num_rows) {
						while ($row = $result->fetch_assoc()) {
							 $json[] = $row;
						}
					}
					if($t2){
						$sql = "SELECT food_tbl_meal_week.meal_id,food_tbl_user_meal_week.dutyplace_id ,SUM(food_tbl_user_meal_week.how_many) as number" 
							. " FROM `food_tbl_user_meal_week`"
							. " INNER JOIN food_tbl_meal_week"
							. " ON  food_tbl_user_meal_week.status = 1 AND food_tbl_user_meal_week.code_days = $t1 AND food_tbl_meal_week.meal_weeks_id=food_tbl_user_meal_week.meal_weeks_id"
							. " GROUP by food_tbl_meal_week.meal_id , food_tbl_user_meal_week.dutyplace_id";
					}else
					{
						$sql = "SELECT food_tbl_meal_week.meal_id,food_tbl_user_meal_week.dutyplace_id ,SUM(food_tbl_user_meal_week.how_many) as number" 
							. " FROM `food_tbl_user_meal_week`"
							. " INNER JOIN food_tbl_meal_week"
							. " ON food_tbl_meal_week.isvis = 1 AND food_tbl_user_meal_week.code_days = $t1 AND food_tbl_meal_week.meal_weeks_id=food_tbl_user_meal_week.meal_weeks_id"
							. " GROUP by food_tbl_meal_week.meal_id , food_tbl_user_meal_week.dutyplace_id";
					}
					$result = $conn->query($sql);
					//$json['query3'] = $sql;
					if ($result == TRUE) {
						if ($result->num_rows) {
							while ($row = $result->fetch_assoc()) {
								 $json[] = $row;
							}
						}
						$echostring .= json_encode($json);
					} else {
						$echostring .= "Error: " . $sql . "<br>" . $conn->error;//****************************************************
					}
				} else {
					$echostring .= "Error: " . $sql . "<br>" . $conn->error;//****************************************************
				}
			}else{
				$echostring .= 'error';
			}
		}else{
			$json['ctime'] = $det->ctime;
			$echostring .= json_encode($json);
		}	
    } else {
        $echostring .= "Error: " . $sql . "<br>" . $conn->error;//****************************************************
    }
    $conn->close();
	if($isprint)
		print1();
	return $echostring;
 }
 
function print1($day, $part)
{
	echo '<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<link href="../../../font/byekan.css" type="text/css" rel="stylesheet">
		<link href="../../../css/food_tree.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../../../js/jquery-1.11.1.min.js"></script>
		<link href="../../../css/food_list_report.css" type="text/css" rel="stylesheet">	
		<script src="../../../js/food_list_report.js" type="text/javascript"></script>
	</head>
	<body style="direction:rtl;">
		<table class="food_tbl_orders" auto="true" day="'. $day.'" part="'. $part.'" style="margin:auto; width:80%;font:13px tahoma;" id="food_report_table">
		</table>
	';

	
	echo '
	</body>
</html>';
}

function set_setting_selectfood($day)
{
	if (!is_numeric($day))
		die ('error data');
	$conn = connect();
	$time = time();
	$sql = "SELECT * FROM `food_tbl_taeed` WHERE `taeed_code`=$day";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		if ($result->num_rows) {
			$sql = "UPDATE `food_tbl_taeed` SET `taeed_type`= 1 WHERE `taeed_code`=$day";
		}
		else{
			$sql = "INSERT INTO `food_tbl_taeed`(`taeed_code`, `taeed_type`) VALUES ($day,1)";
		}
		
		$result = $conn->query($sql);
		if ($result == TRUE) {
			$sql = "UPDATE `food_tbl_user_meal_week` JOIN food_tbl_meal_week"
				. " ON food_tbl_user_meal_week.meal_weeks_id = food_tbl_meal_week.meal_weeks_id AND food_tbl_meal_week.isvis=1 AND food_tbl_user_meal_week.code_days=$day"
				. " SET food_tbl_user_meal_week.status=1, food_tbl_user_meal_week.timeupdate=$time";
			//die($sql);
			$result = $conn->query($sql);
			if ($result == TRUE) {
				$items_list = array();
				 $sql = "SELECT food_tbl_meal_week.meal_weeks_id as id , it.items, it.price" 
					. "  FROM (SELECT food_tbl_meal_item.meal_id , GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, price"
					. "  FROM food_tbl_meal_item"
					. "  INNER JOIN food_tbl_item"
					. "  ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
					. "  INNER JOIN food_tbl_meal"
					. "  ON food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
					. "  GROUP by food_tbl_meal_item.meal_id) as it"
					. "  INNER JOIN food_tbl_meal_week"
					. "  ON food_tbl_meal_week.meal_id = it.meal_id AND food_tbl_meal_week.isvis=1";
				$result = $conn->query($sql);
				if ($result == TRUE) {	
					$json['time'] = time();
					if ($result->num_rows) {
						while ($row = $result->fetch_assoc()) {
							$items_list[$row['id']] = $row;
						}
					}
				}
				$temp = $day % 3;
				$vahdeh = '';
				switch($temp){
					case 0:
						$vahdeh = 'صبحانه';
						break;
					case 1:
						$vahdeh = 'نهار';
						break;
					case 2:
						$vahdeh = 'شام';
						break;
				}
				$jalali = milady_to_shamsi(($day - $temp) * 28800 + 43200);
				$sql = "SELECT * FROM `food_tbl_user_meal_week` WHERE `code_days`=$day AND how_many>0";
				$result = $conn->query($sql);
				if ($result == TRUE) {
					if ($result->num_rows) {
						$sql = "INSERT INTO `food_tbl_history`(`user_id`, `code_days`,`date_jalali`, `vahdeh`, `meal_weeks_id`, `items`, `price`, `how_many`) VALUES ";
						$help_sql = "";
						while ($row = $result->fetch_assoc()) {
							$user_id = $row['user_id'];
							//echo $row['meal_weeks_id']."\n";
							if( isset($items_list[$row['meal_weeks_id']])){
								$meal_weeks_id = $row['meal_weeks_id'];
								$items = $items_list[$row['meal_weeks_id']]['items'];
								$price = $items_list[$row['meal_weeks_id']]['price'];
								$how_many = $row['how_many'];
								$help_sql .=",($user_id,$day,'$jalali','$vahdeh',$meal_weeks_id,'$items',$price,$how_many)";	
							}							
						}
						$help_sql = substr($help_sql , 1);
						$sql.=$help_sql.';';
						$result = $conn->query($sql);
						if ($result == TRUE) {
							echo $day;
						}
						else{
							echo 'error';
						}
					}
				}
			}
		}else{
			echo $sql;
		}
	}
	$conn->close();
}

function set_setting_selectfood_tamdid($day)
{
	if (!is_numeric($day))
		die ('error data');
	$time = time();
	$conn = connect();
	$sql = "UPDATE `food_tbl_taeed` SET `taeed_type`= 0 WHERE `taeed_code`=$day";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		$sql = "UPDATE `food_tbl_user_meal_week` SET `status`= 0,`timeupdate`=$time WHERE `code_days`=$day";
		$result = $conn->query($sql);
		if ($result == TRUE) {
			$sql = "DELETE FROM `food_tbl_history` WHERE `code_days`=$day";
			$result = $conn->query($sql);
			if ($result == TRUE) {
				echo $day;
			}
		}
	}
	$conn->close();
}

function get_list_user_place($day, $part, $place)
{
	if (!is_numeric($part) || !is_numeric($place))
		die ('error data');
	$conn = connect();
	$det = get_details($conn, 1);
	$days = shamsi_to_milady_days($day);
	$dayofweek = ($days - 2) % 7;
	$cweek = $det->cweek;
	$cdays = shamsi_to_milady_days($det->ctime);
	$time = time();
	$dif = $days + $det->cdayweek - $cdays;
	$dif = $dif % 7;
	if($dif < 0) $dif += 7;
	/*while($dif > 6){
		$dif -= 7;
	}
	
	while($dif < 0){
		$dif += 7;
	}*/
	$saturday = $days - $dif;
	$day_part = $dif * 3 + $part ;
	if( $dif == $dayofweek){
		$t1 = $days * 3 + $part;
		$t2 = false;
		$sql = "SELECT `taeed_code`as `code` FROM `food_tbl_taeed` WHERE `taeed_code` = $t1 AND `taeed_type` = 1";
		$result = $conn->query($sql);
		if ($result == TRUE) {
			if ($result->num_rows) {
				$t2 = true;
			}
		}
		
		if($t2)	{		
			$sql_food = "SELECT food_tbl_meal_item.meal_id , GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, isvis, price"
					. " FROM food_tbl_meal_item"
					. " INNER JOIN food_tbl_item"
					. " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
					. " INNER JOIN food_tbl_meal"
					. " ON isvis=1 AND food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
					. " GROUP by food_tbl_meal_item.meal_id";
					
			$sql_food2 = "SELECT food_tbl_meal_week.meal_weeks_id ,fooditems.items"
				. " FROM `food_tbl_meal_week`"
				. " INNER JOIN ($sql_food) as fooditems"
				. " ON food_tbl_meal_week.meal_id=fooditems.meal_id";	
			$sql = "SELECT food_tbl_user_meal_week.user_meal_week_id as id ,food_tbl_user_meal_week.user_id ,users_lname, users_fname, foodweek.items, how_many,ext FROM food_tbl_user_meal_week"
				. " INNER JOIN ($sql_food2) as foodweek"
				. " ON foodweek.meal_weeks_id = food_tbl_user_meal_week.meal_weeks_id AND (dutyplace_id=$place OR $place=0) AND saturday=$saturday"
				. " INNER JOIN tbl_users"
				. " ON food_tbl_user_meal_week.user_id = tbl_users.users_id AND food_tbl_user_meal_week.code_days = $t1 AND food_tbl_user_meal_week.status = 1 AND food_tbl_user_meal_week.how_many>0";
		}
		else{
			$sql_food = "SELECT food_tbl_meal_item.meal_id , GROUP_CONCAT(CONCAT(food_tbl_item.name,' ')) as items, isvis, price"
					. " FROM food_tbl_meal_item"
					. " INNER JOIN food_tbl_item"
					. " ON food_tbl_item.item_id = food_tbl_meal_item.item_id"
					. " INNER JOIN food_tbl_meal"
					. " ON isvis=1 AND food_tbl_meal.meal_id = food_tbl_meal_item.meal_id"
					. " GROUP by food_tbl_meal_item.meal_id";
					
			$sql_food2 = "SELECT food_tbl_meal_week.meal_weeks_id ,fooditems.items"
				. " FROM `food_tbl_meal_week`"
				. " INNER JOIN ($sql_food) as fooditems"
				. " ON food_tbl_meal_week.meal_id=fooditems.meal_id AND food_tbl_meal_week.isvis=1";	
			$sql = "SELECT food_tbl_user_meal_week.user_meal_week_id as id ,food_tbl_user_meal_week.user_id ,users_lname, users_fname, foodweek.items, how_many,ext FROM food_tbl_user_meal_week"
				. " INNER JOIN ($sql_food2) as foodweek"
				. " ON foodweek.meal_weeks_id = food_tbl_user_meal_week.meal_weeks_id AND (dutyplace_id=$place OR $place=0) AND saturday=$saturday"
				. " INNER JOIN tbl_users"
				. " ON food_tbl_user_meal_week.user_id = tbl_users.users_id AND food_tbl_user_meal_week.code_days = $t1 AND food_tbl_user_meal_week.how_many>0";
		}
		$result = $conn->query($sql);
		//echo $sql;
		if ($result == TRUE) {
			if ($result->num_rows) {
				$json = array();
				if($t2)
					$json['code'] = $t1;
				//$json['query1'] = $sql;
				while ($row = $result->fetch_assoc()) {
					$json[] = $row;
				}
				echo json_encode($json);
			}
		}else{
			echo $sql;
		}
	}
	else{
		echo "$dif == $dayofweek";
	}
	//echo $day .' - '.$c1;
	$conn->close();
}

function print2($day, $part, $place)
{
	echo '<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<link href="../../../font/byekan.css" type="text/css" rel="stylesheet">
		<link href="../../../css/food_tree.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../../../js/jquery-1.11.1.min.js"></script>
		<link href="../../../css/food_list_report.css" type="text/css" rel="stylesheet">
		<link href="../../../css/food_list_ready.css" type="text/css" rel="stylesheet">	
		<link href="../../../css/food_list_ditail.css" type="text/css" rel="stylesheet">			
		<script src="../../../js/food_list_ready.js" type="text/javascript"></script>
	</head>
	<body style="direction:rtl;">
		<table id="food_list_detail" style="margin:auto; width:80%;font:13px tahoma;text-align:center;" class="tbl_print_food" auto="true" place="'. $place.'" day="'. $day.'" part="'. $part.'">
		</table>
	';

	
	echo '
	</body>
</html>';
}

function get_user_list()
{
	$conn = connect();
	$sql = "SELECT `users_id` as `id`,CONCAT(`users_lname`,'  ',`users_fname`)as `name`"
		." FROM tbl_user_applist"
		." inner join tbl_users"
		." on  tbl_user_applist.tualist_userid = tbl_users.users_id where tualist_appid = 3"
		." order by `name` asc";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		$json = array();
		while ($row = $result->fetch_assoc()) {
			$json[] = $row;
		}
		echo json_encode($json);
	}
	
	$conn->close();
}

function get_user_list_details($uid, $dt1, $dt2)
{
	if (!is_name($dt1) || !is_name($dt2) || !is_numeric($uid))
            die ('error data');
	$conn = connect();
	$days1 = shamsi_to_milady_days($dt1) * 3;
	$days2 = shamsi_to_milady_days($dt2) * 3 + 2;
	if($uid > 0){
		$sql = "SELECT history_id as id,date_jalali as dt,vahdeh as va, items,price,how_many as hm FROM `food_tbl_history` WHERE `user_id` = $uid AND `code_days` BETWEEN $days1 AND $days2";
		$result = $conn->query($sql);
		if ($result == TRUE) {
			$json = array();
			while ($row = $result->fetch_assoc()) {
				$json[] = $row;
			}
			echo json_encode($json);
		}
	}
	else{
		$sql = "SELECT `user_id` as id, CONCAT(`users_fname`,' ',`users_lname`)as `name`,SUM(`how_many`)as number,SUM(`money`) as `prices`"
			. " FROM (SELECT `user_id`,`how_many`,`how_many` * `price` as `money` "
			. " FROM`food_tbl_history`"
			. " WHERE `code_days` BETWEEN $days1 AND $days2"
			. " ) as `history`"
			. " INNER JOIN `tbl_users`"
			. " ON `user_id` = `users_id`"
			. " GROUP BY `user_id`";
		$result = $conn->query($sql);
		if ($result == TRUE) {
			$json = array();
			while ($row = $result->fetch_assoc()) {
				$json[] = $row;
			}
			echo json_encode($json);
		}
	}
	$conn->close();
}

function load_setting()
{
	$conn = connect();
	$det = get_details($conn, 1);
	$json = array();
	
	$json['weeks'] = $det->weeks;
	$json['numberDayForSelction'] = $det->selction;
	$json['ShowMoney'] = $det->showmoney;
	echo json_encode($json);
	$conn->close();
}

function save_setting($jsondata)
{
	$conn = connect();
	$time = time();
	$obj = json_decode($jsondata);
	foreach ($obj as $key => $value) {
		if (!is_name($key) || !is_name($value))
            die ('error data');
		$sql = "UPDATE `food_tbl_setting` SET `value`='$value',`timeupdate`=$time WHERE `key` = '$key'";
		$result = $conn->query($sql);
	}
	$det = get_details($conn, 1);
	$json = array();
	$json['weeks'] = $det->weeks;
	$json['numberDayForSelction'] = $det->selction;
	$json['ShowMoney'] = $det->showmoney;
	echo json_encode($json);
	$conn->close();
}
//sleep(2);
function update_how_many($user_meal_week_id, $number){
	$conn = connect();
	$time = time();
	$det = get_details($conn, 1);
	$sql = "SELECT * FROM `food_tbl_user_meal_week` WHERE `user_meal_week_id` = $user_meal_week_id";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		if ($result->num_rows){
			$row = $result->fetch_assoc();
			$code_days = $row['code_days'];
			$user_id = $row['user_id'];
			$meal_weeks_id = $row['meal_weeks_id'];
		}
		//else  echo $sql;
	}else{
		//echo $sql;
	}
	$days = intval($code_days / 3);
	$cdays = shamsi_to_milady_days($det->ctime);
	$t2 = false;
	if($days == $cdays){
	
		$sql = "SELECT `taeed_code`as `code` FROM `food_tbl_taeed` WHERE `taeed_code` = $code_days AND `taeed_type` = 1";
		$result = $conn->query($sql);
		if ($result == TRUE) {
			if ($result->num_rows) {
				$t2 = true;
			}
		}
		if($t2){
			$sql = "UPDATE `food_tbl_user_meal_week` SET `how_many`=$number,`timeupdate`=$time WHERE `user_meal_week_id` = $user_meal_week_id";
			$result = $conn->query($sql);
			if ($result == TRUE) {
				
			}
			
			$sql = "UPDATE `food_tbl_history` SET `how_many`=$number WHERE `code_days` = $code_days AND  `meal_weeks_id` = $meal_weeks_id AND `user_id` =  $user_id";
			$result = $conn->query($sql);
			if ($result == TRUE) {
				
			}
			
			echo 'save';
		}
	}else{
		//echo "$days == $cdays";
	}
}

$user = intval($_SESSION["userid"]);

switch ($act) {
    case 'get_list_tablecloth':
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '0';
        get_list_tablecloth($time);
        break;
    case 'get_list_tablecloth_user':
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '0';
        if ((int)$time > 1) {
            $det = new details();
        } else {
            $det = null;
        }
        get_list_tablecloth_user($time, $det);
        break;
    case 'create_meal_weeks':
		if(getPermitAdmin() == '0')
			exit;
        $day_part = isset($_REQUEST['day_part']) ? $_REQUEST['day_part'] : '0';
        $meal_id = isset($_REQUEST['meal_id']) ? $_REQUEST['meal_id'] : '0';
        $week = isset($_REQUEST['week']) ? $_REQUEST['week'] : '0';
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '0';
        $lastid = isset($_REQUEST['lastid']) ? $_REQUEST['lastid'] : 0;
        $lastid = (int)$lastid;
        $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : 0;
        $count = (int)$count;
        create_meal_weeks($day_part, $meal_id, $week);
        get_list_meal_weeks($week, $count, $lastid, $time);
        break;
    case 'move_meal_weeks':
		exit;
		if(getPermitAdmin() == '0')
			exit;
        $day_part = isset($_REQUEST['day_part']) ? $_REQUEST['day_part'] : '0';
        $meal_id = isset($_REQUEST['meal_id']) ? $_REQUEST['meal_id'] : '0';
        $meal_week_id = isset($_REQUEST['meal_week_id']) ? $_REQUEST['meal_week_id'] : '0';
        $week = isset($_REQUEST['week']) ? $_REQUEST['week'] : '0';
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '0';
        $lastid = isset($_REQUEST['lastid']) ? $_REQUEST['lastid'] : 0;
        $lastid = (int)$lastid;
        $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : 0;
        $count = (int)$count;
        move_meal_weeks($meal_week_id, $day_part, $meal_id, $week);
        get_list_meal_weeks($week, $count, $lastid, $time);
        break;
    case 'delete_meal_weeks':
		if(getPermitAdmin() == '0')
			exit;
        $meal_week_id = isset($_REQUEST['meal_week_id']) ? $_REQUEST['meal_week_id'] : '0';
        $week = isset($_REQUEST['week']) ? $_REQUEST['week'] : '0';
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '0';
        $lastid = isset($_REQUEST['lastid']) ? $_REQUEST['lastid'] : 0;
        $lastid = (int)$lastid;
        $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : 0;
        $count = (int)$count;
        delete_meal_weeks($meal_week_id);
        get_list_meal_weeks($week, $count, $lastid, $time);
        break;
    case 'get_list_meal_weeks':
        $week = isset($_REQUEST['week']) ? $_REQUEST['week'] : '0';
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '0';
        $lastid = isset($_REQUEST['lastid']) ? $_REQUEST['lastid'] : 0;
        $lastid = (int)$lastid;
        $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : 0;
        $count = (int)$count;
        get_list_meal_weeks($week, $count, $lastid, $time);
        break;
    case 'get_next_list_meal_weeks':
        $week = isset($_REQUEST['week']) ? $_REQUEST['week'] : '0';
        $next = (isset($_REQUEST['next']) && $_REQUEST['next'] == '1') ? 1 : 0;
        $week = get_next_list_meal_weeks($week, $next);
        get_list_meal_weeks($week, 0, 0, 0, true);
        break;
    case 'get_selected_food_user':
        $saturday = isset($_REQUEST['saturday']) ? $_REQUEST['saturday'] : '0';
        $saturday = (int)$saturday;
        get_selected_food_user($user, $saturday);
        break;
    case 'save_list_meal_weeks':
        $json = isset($_REQUEST['json']) ? $_REQUEST['json'] : '';
        $saturday = isset($_REQUEST['saturday']) ? $_REQUEST['saturday'] : '0';
        $placeid = isset($_REQUEST['placeid']) ? $_REQUEST['placeid'] : '0';
		$extra = isset($_REQUEST['extra']) ? $_REQUEST['extra'] : '';
		$permit = isset($_REQUEST['permit']) ? $_REQUEST['permit'] : '0';
		$dt = isset($_REQUEST['dt']) ? $_REQUEST['dt'] : '0';
        $saturday = (int)$saturday;
        $placeid = (int)$placeid;
		$permit = (int)$permit;
        save_list_meal_weeks($user, $json, $placeid, $extra, $saturday, $dt, $permit);
        break;
	case 'get_list_day_meal':
		if(getPermitSupervisor() == '0' && getPermitAdmin() == '0')
			exit;
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
        $part = isset($_REQUEST['day_part']) ? $_REQUEST['day_part'] : '0';
        $part = (int)$part;
        echo get_list_day_meal($day, $part);
        break;
	case 'print1':
		if(getPermitAdmin() == '0')
			exit;
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
        $part = isset($_REQUEST['day_part']) ? $_REQUEST['day_part'] : '0';
        $part = (int)$part;
        print1($day, $part);
        break;
	case 'set_setting_selectfood':
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
		if(getPermitAdmin() == '0')
			exit;
        set_setting_selectfood($day);
        break;
	case 'set_setting_selectfood_tamdid':
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
		if(getPermitAdmin() == '0')
			exit;
        set_setting_selectfood_tamdid($day);
        break;
	case 'get_list_user_place':
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
        $part = isset($_REQUEST['day_part']) ? $_REQUEST['day_part'] : '0';
		$place = isset($_REQUEST['place']) ? $_REQUEST['place'] : '0';
        $part = (int)$part;
		$place = (int)$place;
		if(getPermitSupervisor() == '0' && getPermitAdmin() == '0')
			exit;
        get_list_user_place($day, $part, $place);
        break;
	case 'print2':
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
        $part = isset($_REQUEST['day_part']) ? $_REQUEST['day_part'] : '0';
		$place = isset($_REQUEST['place']) ? $_REQUEST['place'] : '0';
        $part = (int)$part;
		$place = (int)$place;
		if(getPermitSupervisor() == '0' && getPermitAdmin() == '0')
			exit;
        print2($day, $part, $place);
        break;
	case 'get_user_list':
		if(getPermitAdmin() == '0')
			exit;
		get_user_list();
		break;
	case 'get_user_list_details':

		if(getPermitAdmin() == '0')
			exit;
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '0';
        $dt1 = isset($_REQUEST['dt1']) ? $_REQUEST['dt1'] : '';
		$dt2 = isset($_REQUEST['dt2']) ? $_REQUEST['dt2'] : '';
		$uid = (int)$uid;
		get_user_list_details($uid, $dt1, $dt2);
		break;
		
	case 'load_setting':
		if(getPermitAdmin() == '0')
			exit;
		load_setting();
		break;
	case 'save_setting':
		if(getPermitAdmin() == '0')
			exit;
		
		$json = isset($_REQUEST['json']) ? $_REQUEST['json'] : '';
		save_setting($json);
		break;
	case 'update_how_many':
		if(getPermitAdmin() == '0')
			exit;
		
		$user_meal_week_id = isset($_REQUEST['user_meal_week_id']) ? $_REQUEST['user_meal_week_id'] : '';
		$number = isset($_REQUEST['number']) ? $_REQUEST['number'] : '';
		update_how_many($user_meal_week_id, $number);
		break;
}
?>