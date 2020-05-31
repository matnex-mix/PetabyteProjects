<?php

	if(!file_exists(dirname(__FILE__)."/../erd.json")) exit(0);
	
	$ERD = json_decode(file_get_contents(dirname(__FILE__)."/../erd.json"), true);

	foreach ($ERD as $table_name => $columns) {
		
		$sql = "";
		$les = sql_execute("SHOW TABLES LIKE '%$table_name%';");

		if ($les->num_rows > 0) {

			$data1 = parse_table($table_name);
			sql_execute("DROP TABLE `$table_name`");

		}
		
		$sql .= "CREATE TABLE `$table_name` (";
		$count = 0;

		foreach ($columns as $name => $props) {
			
			if($name == "PRIMARY") $sql .= " $name ".$props[0];
			else $sql .= " `$name` ".$props[0];
			array_shift($props);

			if(isset($props[0]) && $props[0]) {

				$sql .= "(".$props[0].")";

			}
			array_shift($props);

			foreach ($props as $value) {
				$sql .= " ".$value;
			}

			if($count == sizeof($columns)-1) break;

			$sql .= ",";
			$count++;

		}

		$sql .= " );\n";

		$test = sql_execute($sql);
		
		if ($les->num_rows > 0) {
			foreach ($data1 as $value) {
				table_insert($table_name, $value);
			}
		}

	}

