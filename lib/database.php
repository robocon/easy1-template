<?php defined('AM_EXEC') or die('Restricted Access');
class DBi{
	private static $db;
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

	public static function insert($table, $items){
		if(count($items) > 1){
			$item_field = array();
			$item_value = array();
			foreach($items AS $field => $value){
				$item_field[] = "`$field`";
				$item_value[] = "'$value'";
			}
			$field_key = implode(',',$item_field);
			$value = implode(',',$item_value);
		}else{
			$item_key = array_keys($items);
			$field_key = "`$item_key[0]`";
			$item_val = array_values($items);
			$value = $item_val[0];
		}
		$mysqli = self::connect();
		if($mysqli->query("INSERT INTO `$table` ($field_key) VALUE ($value);")===true){
			return $mysqli->insert_id;
		}else{
			return false;
		}
	}

	public static function update($table, $items, $where){
		if(is_array($items)){
			$data = array();
			foreach($items AS $key => $value){
				$data[] = "`$key`='$value'";
			}
			$set_data = implode(',',$data);
		}else{
			$set_data = $items;
		}
		$mysqli = self::connect();
		if($mysqli->query("UPDATE `$table` SET $set_data WHERE $where;")===true){
			return true;
		}else{
			return false;
		}
	}
}
