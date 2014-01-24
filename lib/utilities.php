<?php defined('AM_EXEC') or die('Restricted Access');
class AM_Utilities{
	private static $config;
	public static function getconfig(){
		if(!self::$config){
			$dbi = Dbi::connect();
			$query = $dbi->query("SELECT * FROM web_config;");
			$config = array();
			while($item = $query->fetch_assoc()){
				$key = $item['posit'];
				$config[$key] = $item['name']; 
			}
			self::$config = $config;
			return $config;
		}else{
			return self::$config;
		}
	}

	/**
	 * from and to can set in to array to send multiple
	 *
	 * $from	array('name' => 'email');
	 * $to 		array('name' => 'email');
	 */
	public static function sendemail($from, $to, $subject, $message){
		if(count($to) > 1){
			$set_to = array();
			foreach($to AS $name => $email){
				$set_to[] = "$name <$email>";
			}
			$user_to = implode(',',$set_to);
		}else{
			list($name) = array_keys($to);
			list($email) = array_values($to);
			$user_to = "$name <$email>";
		}

		if(count($from) > 1){
			$set_from = array();
			foreach($from AS $name => $email){
				$set_from[] = "$name <$email>";
			}
			$user_from = implode(',',$set_from);
		}else{
			list($name) = array_keys($from);
			list($email) = array_values($from);
			$user_from = "$name <$email>";
		}
		
		$headers = 'MIME-Version: 1.0'."\r\n"
		.'Content-type: text/html; charset=utf-8'."\r\n"
		.'From: '.$user_from."\r\n"
		.'X-Mailer: PHP mailer'."\r\n";
		if(@mail($user_to,$subject,$message,$headers)){
			return true;
		}else{
			return false;
		}
	}

	public static function get_domain(){
		return (strtolower(getenv('HTTPS')) == 'on' ? 'https' : 'http') . '://' . getenv('HTTP_HOST') . (($p = getenv('SERVER_PORT')) != 80 AND $p != 443 ? ":$p" : '');
	}

}
