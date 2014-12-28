<?php
	/* database const */
	$DB_HOST="localhost";	//database address
	$DB_USER="UserName";	//database username
	$DB_PSWD="PassWord";	//database password
	$DB_NAME="NameOfDB";	//databese name
	$DB_PORT=      3306;	//database port
	/* be aware 'const' works ONLY INSIDE of a class definition */
	
	/* class BindParam: bind parameters for mysqli_stmt::bind_param */
	class BindParam {
		private $values = array(), $types = '';
		public function add( $type, &$value ) {
			$this->values[] = $value;
			$this->types .= $type;
		}
		public function get() {
		//will get array($types = string "idsb...", &$var_1 = integer, &$var_2 = double, &$var_3 = string, &$var_4 = blob, ...);
			return array_merge(array($this->types), $this->values); 
		}
	}

	/* json_err(): organize error infomation */
	function json_err($err_type, $err_num, $err_msg){
		return json_encode(array(
			"err_type"=>$err_type, 
			"err_num"=>$err_num, 
			"err_msg"=>$err_msg
		));
	}
?>