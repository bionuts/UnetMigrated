<?php

require '../../../config/food_config.php';
$con = connect();
/* AJAX check  REQUEST  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//this is an ajax request, process data here.

    if(isset($_POST['func'])) {

        $func=$_POST['func'];

        switch ($func)
        {
            case "add_meal":

                $peymankar_id = $_POST['peymankar_id'];
                $meal_price = $_POST['meal_price'];
                $arry_item = $_POST['arry_item'];
                $meal_item_list = join(",", $arry_item);				
                if(!is_null($con))
                {
					$time = time();
					$sql = "call food_sp_makemeal($peymankar_id,$meal_price,$time,'$meal_item_list') ";
                    mysqli_query($con, $sql);
					//die ($sql);
                    $result =mysqli_query($con,"SELECT meal_id FROM `food_tbl_meal` ORDER BY  `meal_id` DESC Limit 1 ");
                    if (mysqli_num_rows($result) == 1)
                    {
                        $row = mysqli_fetch_assoc($result);
                    }
                    echo  $row['meal_id'];


                }


                break;

            case "del_meal":
                $del_id = $_POST['del_id'];
                $time = time();
                if(!is_null($con))
                {
                    $sql = "update `food_tbl_meal` SET isvis=0 , timeupdate=$time where (meal_id=$del_id)";
                    mysqli_query($con,$sql);
                }
                echo 1;
                break;

            case "active_meal":
                $act_id = $_POST['act_id'];
                $time = time();
                if(!is_null($con))
                {
                    mysqli_query($con," update `food_tbl_meal` SET isvis=1, timeupdate=$time  where (meal_id=$act_id)  ");
                }
                echo 1;
                break;


            case "load_edit_date":
                $edit_id = $_POST['meal_id'];


                $result =mysqli_query($con,"SELECT * FROM `food_tbl_meal` where (`meal_id`=$edit_id ) Limit 1 ");
                if (mysqli_num_rows($result) == 1)
                {

                    $row = mysqli_fetch_assoc($result);

                    $arr = array('peymankarid' => $row["peymankar_id"], 'price' => $row["price"] );
                    echo   json_encode($arr);
                }


                break;
            case "load_items_4edit":

                $meal_id = $_POST['meal_id'];
                $result =mysqli_query($con,"SELECT item_id FROM `food_tbl_meal_item` where (`meal_id`=$meal_id )  ");
                if (mysqli_num_rows($result))
                {
                    $arr = array();

                    while($row = mysqli_fetch_assoc($result))
                    {
                          $getname=mysqli_query($con,"SELECT name FROM `food_tbl_item` where (`item_id`=$row[item_id]) limit 1 ") ;
                          $itemname= mysqli_fetch_assoc($getname);

                          array_push($arr, array('name' => $itemname["name"], 'id'=> $row["item_id"] ));


                    }
                    echo   json_encode($arr);
                }
                break;
            case "update_meal":

                $peymankar_id = $_POST['peymankar_id'];
                $meal_price = $_POST['meal_price'];
                $arry_item = $_POST['arry_item'];
                $editt_id= $_POST['meal_edit_id'];

                $meal_item_list = join(",", $arry_item);

                if(!is_null($con))
                {
					$time = time();
                     mysqli_query($con,"call food_sp_updatemeal($peymankar_id,$meal_price,$time,'$meal_item_list' , $editt_id) ");

                }
                  echo $meal_item_list;
                break;





        }

    }

    /* special ajax here */

}