<?php defined('AM_EXEC') or die('Restricted Access');

$action = addslashes($_GET['action']);
if($action==="add"){
	if($_POST['token']!==$_SESSION['token']){
		header('Location: index.php?name=member&file=index');
		$_SESSION['x_message'] = $l->t("Incorrect data.");
		exit(0);
	}

	$username = addslashes($_POST['username']);
	$password = $_POST['password'];
	$first_name = addslashes($_POST['first_name']);
	$last_name = addslashes($_POST['last_name']);
	$email = addslashes($_POST['email']);

	$size = 80;
	$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?s=".$size."&d=404";
	$grav_data = @file_get_contents($grav_url);
	$user_thumb = "";
	if($grav_data!==false){
		$user_thumb = time()."_$username.jpg";
		$fp = fopen("icon/".$user_thumb, 'w');
		fwrite($fp, $grav_data);
		fclose($fp);
	}

	$data_member = array(
		'member_id' => '',
		'name' => $first_name." ".$last_name,
		'nic_name' => "",
		'date' => (int)$_POST['day'],
		'month' => (int)$_POST['month'],
		'year' => (int)$_POST['year'],
		'age' => ((int)date('Y') - (int)$_POST['year']),
		'sex' => "",
		'address' => "",
		'amper' => "",
		'province' => "",
		'zipcode' => "",
		'phone' => "",
		'education' => "",
		'work' => "",
		'office' => "",
		'user' => $username,
		'password' => md5($password),
		'email' => $email,
		'member_pic' => $user_thumb,
		'signup' => date('j/m/Y'),
		'lastlog' => date('d/m/y - H:i'),
		'dtnow' => date('d/m/y - H:i'),
		'blog' => 1,
		'post' => 0,
		'topic' => 0,
		'signature' => ""
	);
	
	DBi::connect();
	$member_id = DBi::insert("web_member", $data_member);
	if($member_id!==false){
		$verify = sha1($email);
		$data = array(
			"member_id" => "web$member_id",
			"verify" => $verify
		);
		DBi::update("web_member", $data, "id='$member_id'");

		$domain = AM_Utilities::get_domain();
		$url = "$domain/index.php?name=member&file=member_edit&action=confirm&s=$verify";

		$config = AM_Utilities::getconfig();
		$from = array($config['title'] => $config['email']);
		$to = array($first_name => $email);
		$subject = $l->sprintf("New account on %s",$config['title']);
		$message = $l->sprintf("Hello %s,", $first_name);
		$message .= "<p>&nbsp;</p>";
		$message .= $l->t("This is your access details")."<br>";
		$message .= $l->sprintf("Username: %s", $username)."<br>";
		$message .= $l->sprintf("Password: %s", $password)."<br>";
		$message .= "<p>&nbsp;</p>";
		$message .= $l->t("To confirm before login to server, Please click this link:");
		$message .= '<a href="'.$url.'">'.$url.'</a>';
		$message .= "<p>&nbsp;</p>";
		$message .= $l->t("Regards,")."<br>";
		$message .= $config['title'];

		AM_Utilities::sendemail($from, $to, $subject, $message);

		$msg = $l->t("Sign up successful, please check your email to confirm.");
		$_SESSION['token'] = "";

	}else{
		$msg = $l->t("Sign up unsuccessful, please contact admin.");
	}

	header('Location: index.php?name=member&file=index');
	$_SESSION['x_message'] = $msg;
	exit(0);
}else if($action==="edit"){
	/**
	 * TODO
	 * - Check user login
	 */

}else if($action==="confirm"){
	$s = addslashes($_GET['s']);
	$msg = "";
	if(strlen($s)===40){
		DBi::connect();
		DBi::update("web_member", array("verify" => ""), "verify='$s'");
		$msg = $l->t("Confirm successful");
	}
	header('Location: index.php');
	$_SESSION['x_message'] = $msg;
	exit(0);
}
