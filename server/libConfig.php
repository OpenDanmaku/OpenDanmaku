<?php
	/* database const */
	$DB_HOST="localhost";	//database address
	$DB_USER="zjwdb_290659";	//database username
	$DB_PSWD="3WWZBGNR5";	//database password
	$DB_NAME="zjwdb_290659";	//databese name
	$DB_PORT=      3306;	//database port
	/* be aware 'const' works ONLY INSIDE of a class definition */
	
/* class BindParam: bind parameters for mysqli_stmt::bind_param 
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
	*/
?>
