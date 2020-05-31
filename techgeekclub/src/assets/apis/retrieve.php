<?php
	include 'connect.php';

	//include '././components/functions.php';

	//$table_name = "users";

	//$columns = ["Name","Rank","Class"];

	/*function r_count($param){

		$count = 0;

		if(is_array($param)){

			foreach ($param as $key) {
				$count++;
			}

		}

		return $count;
	}*/

	$success = "";

	$counter = 0;

	if(!isset($columns)){
		die("Columns Not Set");
	}else {
		$column = "";

		foreach ($columns as $column_i) {
			if($counter + 1 == sizeof($columns)){
				$column .= $column_i." ";
				break;
			}
			$column .= $column_i.", ";
			$counter ++;
		}

		//$column = substr_replace(", ", "", $column, strlen($column) - 4);
	}

	//echo r_count($columns);

	if(!isset($table_name)){
		die("Table Name Not Set");
	}
	if(!isset($condition)){
		$condition = "";
	}

	$sql = "SELECT ".$column."FROM ".$table_name." ".$condition;

	$data = array();

	//echo $sql;

	$result = $tgc_connect->query($sql);

	//echo $column."<br/>";

	if($result->num_rows > 0){
		while($cols = $result->fetch_assoc()){
			$temp_data = array();
			foreach($columns as $column){
				$temp_data[$column] = $cols[$column];
			}
			array_push($data, $temp_data);
		}
	}else {
		
		$success = "Table is Empty!!";

		//die("");
	}

	/*foreach ($data as $key => $value) {
		echo $key." => ".$value."<br/>";
	}*/

?>