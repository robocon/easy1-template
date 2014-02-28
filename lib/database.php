<?php defined('AM_EXEC') or die('Restricted Access');
class DBi{
	private static $db;
	private static $query;
	private static $error;
	private static $stmti;
	public function __construct($dbi){
		return $dbi;
	}
	public static function connect(){
		static $mysqli;
		if(empty($mysqli)){
			$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: " . $mysqli->connect_error;
			}else{
				$mysqli->query("SET NAMES utf8");
				return $mysqli;
			}
		}else{
			return $mysqli;
		}
	}

	public static function select($sql, $items=null){
		$mysqli = self::connect();

		// Some Server not support mysqlnd
		if(extension_loaded('mysqlnd')===false && !is_null($items)){

			$bind_value = array();
			foreach($items AS $key => $value){
				$bind_value[] = & $items[$key];
			}

			$pre_sql = str_replace('?', "'%s'", $sql);
			$real_sql = call_user_func_array("sprintf", array_merge(array($pre_sql), $bind_value) );

			if(!$after_query = $mysqli->query($real_sql)){
				self::$error = $mysqli->error;
				return false;
			}else{
				self::$query = $after_query;
				return $after_query;
			}

		}else if(extension_loaded('mysqlnd')!==false && $items!==null){
			if (!($stmt = $mysqli->prepare($sql))) {
			    self::$error = $mysqli->error;
			    return false;
			}

			$bind_text = "";
			$bind_value = array();
			foreach($items AS $key => $value){
				$bind_value[] = & $items[$key];
				$bind_text .= "s";
			}

			call_user_func_array(array($stmt, "bind_param"), array_merge(array($bind_text), $bind_value) );
			if($stmt->execute()===true){
				self::$query = $stmt->get_result();
				return self::$query;
			}else{
				self::$error = $mysqli->error;
				return false;
			}
		}else{
			if(!$after_query = $mysqli->query($sql)){
				self::$error = $mysqli->error;
				return false;
			}else{
				self::$query = $after_query;
				return $after_query;
			}
		}
	}

	public static function get_error(){
		header('Location: '.$_SERVER['HTTP_REFERER']);
		$_SESSION['x_message'] = self::$error;
		exit();
	}

	public static function fetch_assoc(){
		if(extension_loaded('mysqlnd')){
			$query = self::$query;
			$items = array();
			while($item = $query->fetch_assoc()){
				$items[] = $item;
			}
			return $items;
		}else{
			while (self::$stmti->fetch()) { 
		        foreach($row as $key => $val) 
		        { 
		            $c[$key] = $val; 
		        } 
		        $result[] = $c; 
		    }
		    return $result;
		}
			
	}

	public static function insert($table, $items){

		$bind_query = $item_value = $item_field = array();
		$prepare_bind = "";

		foreach ($items as $field => $value) {
			$item_field[] = "`$field`";
			$item_value[] = & $items[$field];
			$bind_query[] = "?";

			/**
			 * TODO
			 * - Check type of and convert to i, d, s, b
			 */
			$prepare_bind .= "s";

		}
			

		if(count($items) > 1){
			$field_key = implode(',',$item_field);
			$bind_str = implode(', ',$bind_query);
		}else{
			$field_key = $item_field[0];
			$bind_str = $bind_query[0];
		}

		$mysqli = self::connect();
		if (!($stmt = $mysqli->prepare("INSERT INTO `$table` ($field_key) VALUE ($bind_str);"))) {
		    self::$error = $mysqli->error;
		    return false;
		}

		call_user_func_array(array($stmt, "bind_param"), array_merge(array($prepare_bind), $item_value) );
		if($stmt->execute()===true){
			return $mysqli->insert_id;
		}else{
			self::$error = $mysqli->error;
			return false;
		}
	}

	public static function update($table, $items, $where){

		$item_value = $data = array();
		$prepare_bind = "";
		foreach($items AS $key => $value){
			$data[] = "`$key`= ?";
			$prepare_bind .= "s";
			$item_value[] = & $items[$key];
		}
		

		if(count($items)>1){
			$set_data = implode(', ',$data);
		}else{
			$set_data = $data[0];
		}

		$mysqli = self::connect();
		if (!($stmt = $mysqli->prepare("UPDATE `$table` SET $set_data WHERE $where;"))) {
		    self::$error = $mysqli->error;
		    return false;
		}

		call_user_func_array(array($stmt, "bind_param"), array_merge(array($prepare_bind), $item_value) );
		if($stmt->execute()===true){
			return true;
		}else{
			self::$error = $mysqli->error;
			return false;
		}
	}
}
