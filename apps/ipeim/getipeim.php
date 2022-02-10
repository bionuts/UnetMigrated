<?php 
	$id_nezarat = $_POST['nezaratid'];
	echo get_peymankars_list($id_nezarat);	
	
	function get_peymankars_list( $nezarat_id )
	{
		$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
		if ($conn->connect_error) return 'failed to connect to db';
		mysqli_set_charset($conn, "utf8");

		$result = mysqli_query($conn, " CALL permit_sp_GetUserNazerTakePeimankar(".$nezarat_id.",NULL);");
					
		$str='';
		
		$option_start = "<option value='";
		$option_end = "</option>";
		
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)){
				
				$str .= $option_start . $row['fkusers_peimankar_info_id'] ."'>" . $row['users_fname'] ." ". $row['users_lname'] . $option_end;
			}
		} else {
			echo $option_start."0'>اطلاعات مورد نظر یافت نشد".$option_end;
		}
		
		mysqli_close($conn);
		return ($str);
	}
?>