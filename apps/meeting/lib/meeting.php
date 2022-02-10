<?php
class meeting_class
{
	//Variables list
	private $servername = 'localhost';
	private $username = 'root';
	private $password = '';
	private $db_name= 'meetingdb';

	private $conn;
	
	public function insert_new_meeting($arrvalues)
	{
		include 'jdf.php';
		$tmprow = null;
		$miladidate = trim(jalali_to_gregorian( $arrvalues['ydate'] , $arrvalues['mdate'] , $arrvalues['ddate'] , '-' ));
		$this->connect();
		$cresult = mysqli_query($this->conn,"call meeting_sp_check_overlapping('".$miladidate."','".$arrvalues['stime']."','".$arrvalues['ftime']."');");
		if(mysqli_num_rows($cresult) == 0)
		{
			mysqli_free_result($cresult);  
			mysqli_next_result($this->conn);
			
			$res = mysqli_query($this->conn,"call meeting_sp_insertnew_session('".
				$arrvalues['subj']."','".
				$arrvalues['neederman']."','".
				$miladidate."','".
				$arrvalues['stime']."','".
				$arrvalues['ftime']."','".
				$arrvalues['coname']."','".
				$arrvalues['members']."');");
			//return $res;
			if(mysqli_affected_rows($this->conn))
			{				
				$tmprow['status'] = 'inserted';
			}
			else
			{
				$tmprow['status'] = 'failed';
			}
		}
		else
		{
			$tmprow['status'] = 'conflict';
			$i=0;
			while($row = mysqli_fetch_assoc($cresult))
			{
				$tmprow[] = $row;
				$i++;
			}
			$tmprow['num'] = $i;
		}
		$this->close_connect();
		return $tmprow;
	}
	//connect to db
	private function connect()
	{	
		$this->conn = mysqli_connect($this->servername, $this->username, $this->password,$this->db_name);
		// Check connection
		if ($this->conn->connect_error) {
			//database connection failed!FUCK :(
			return -1;
		}
		mysqli_set_charset($this->conn,"utf8");
		//return $conn;
	}
	
	//disconnect from DB
	private function close_connect()
	{
		mysqli_close($this->conn);
	}
}
?>			