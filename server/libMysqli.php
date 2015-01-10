<?php
//affected_rows works like num_rows on SELECT statements
require 'libConfig.php';
/* configuration, common classes and functions

	database consts;
	//class BindParam: bind parameters for mysqli_stmt::bind_param;
	//json_err(): organize error infomation;

*/

/* json_err(): organize error infomation */
function json_err($err_type=0, $err_num=0, $err_msg=0){
	return json_encode(array(
		"err_type"=>$err_type, 
		"err_num"=>$err_num, 
		"err_msg"=>$err_msg
	));
}

/* function safe_query() 

	Parameters:
		$query NOT NULL = string "INSERT INTO CountryLanguage VALUES (?, ?, ?, ?);";
		&$result        = reference of $result;
		$bind_params    = array('idsb', $i, $d, $s, $b);//stop using class BindParam
	Return:
		affected_rows
		&$result        = return NULL or array();
	Procedure:	
		connect -> prepare -> bind_param -> execute -> store_result -> bind_result-> fetch	
	
	WARNING: 
		results of SQL like "select max(id) from table" might err
		
*/

function safe_query($query, &$result, $bind_params=NULL){//stop using class BindParam

	/* database consts */
	global $DB_HOST,$DB_USER,$DB_PSWD,$DB_NAME,$DB_PORT;	
	/* be aware 'const' works ONLY INSIDE of a class definition */	
	
	/* connection */
	$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PSWD,$DB_NAME,$DB_PORT); //instantiate mysqli
	/* check connection */
	if ($mysqli->connect_errno) {
		$err_info = json_err("db_connect", $mysqli->connect_errno, $mysqli->connect_error);
		die($err_info);
	}

	/* preparation */
	$stmt = $mysqli->prepare($query);//prepare statement, $query must exist
	/* check preparation */
	if (false===$stmt) {//prepare() will return a FALSE on error
		$err_info = json_err("db_prepare", $mysqli->errno, $mysqli->error);//a BOOLEAN $stmt has no errno or error property
		$mysqli->close();
		die($err_info);
	}

	/* you can set up valuse of the parameters bounded, e.g.
	$emp_id=4;
	*/
	
	/* binding parameters */
	if (false===is_null($bind_params)){//need binding parameters? yes! bool is-null()
		$rc=call_user_func_array(array(&$stmt, 'bind_param'), $bind_params);//stop using class BindParam
		//call_user_func_array() binds parameters either byRef or byVal, whilst $stmt->bind_param() needs first parameter byVal and others by Ref.
		/* check binding */
		if (false===$rc) {//bool mysqli_stmt::bind_param
			$err_info = json_err("db_bind_param", $stmt->errno, $stmt->error);
			$stmt->close();
			$mysqli->close();
			die($err_info);
		}
	}
	
	/* you can still renew valuse of the parameters bounded, e.g.
	$emp_id=5;
	*/

	/* execution */	
	$rc = $stmt->execute();//execute statement
	/* check execution */
	if (false===$rc) {//bool mysqli_stmt::execute
		$err_info = json_err("db_execute", $stmt->errno, $stmt->error);
		$stmt->close();
		$mysqli->close();
		die($err_info);
	}

	/* field_count */
	$rc = $stmt->field_count;//int $mysqli_stmt->field_count;
	/* check field_count */
	if ($rc<1) //no columns bounded
		return $stmt->affected_rows;
	/* 
	RETURN 0;
	
	if mysqli returns no columns, function finishes here returning no rows
	
	it's not an error because $stmt->errno is checked after execution
	*/

	/* store_result */
	$rc = $stmt->store_result();//store result
	/* check store_result */
	if (false===$rc) {//bool mysqli_stmt::store_result
		//it is an erro because execution is correct ,and mysqli did returns something
		$err_info = json_err("db_store_result", $stmt->errno, $stmt->error);
		$stmt->close();
		$mysqli->close();
		die($err_info);
	}

	/* get metadata */
	$meta = $stmt->result_metadata();//it would be a mysqli_result object
	if (false===$meta) {//result_metadata() will return a FALSE on error though
		$err_info = json_err("db_result_metadata", $stmt->errno, $stmt->error);
		//a BOOLEAN $meta has no errno or error property, neither has object mysqli_result
		$stmt->close();
		$mysqli->close();
		die($err_info);
	}
	$bind_results = array();
	$row = array();
	while($field = $meta->fetch_field())
		$bind_results[] = &$row[$field->name];// pass by reference!!!
	//http://php.net/manual/zh/class.mysqli-result.php#115009

	/* bind_result */
	$rc = call_user_func_array(array(&$stmt, 'bind_result'), $bind_results);
	//call_user_func_array() binds parameters either byRef or byVal, whilst $stmt->bind_result() needs all parameters by Ref.
	/* check binding */
	if (false===$rc) {//bool mysqli_stmt::bind_result
		$err_info = json_err("db_bind_result", $stmt->errno, $stmt->error);
		$stmt->close();
		$mysqli->close();
		die($err_info);
	}

	/* fetch */
	$i=0;
	while($stmt->fetch()){
		$result[$i] = array();
		foreach($row as $k=>$v)
			$result[$i][$k] = $v;
		$i++;
	}

	/* exit */
	$count_rows = $stmt->affected_rows;
	$stmt->free_result();
	$stmt->close();
	$mysqli->close();
	return $count_rows;
	/* 
	RETURN $stmt->affected_rows;
	*/
}
?>
