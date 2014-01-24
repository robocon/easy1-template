<?php
defined('AM_EXEC') or die('Restricted access');

$username = addslashes($_POST['username']);
$password = $_POST['password'];
$email = addslashes($_POST['email']);
$first_name = addslashes($_POST['first_name']);
$last_name = addslashes($_POST['last_name']);
$day = (int)$_POST['day'];
$month = (int)$_POST['month'];
$year = (int)$_POST['year'];

$token = $error = false;
if(empty($username) || empty($password) || empty($email) || empty($first_name) || empty($last_name) 
|| empty($day) || empty($month) || empty($year)){
	$error = true;
	$msg = $l->t("You must fill in all of the fields.");
}else{
	
	$sql = "SELECT a.`id`,a.`username`,b.`user` FROM `web_admin` AS a
RIGHT JOIN `web_member` AS b ON b.`user` = a.`username`
WHERE b.`user` = '$username' OR a.`username` = '$username'";
	$query = $db->select_query($sql);
	$rows = $db->rows($query);
	if($rows > 0){
		$error = true;
		$msg = $l->t("This username is already in use.");
	}

	if(!preg_match('/.+@.+(\.[a-zA-Z0-9]{2,}){1,}+$/', $email)){
		$error = true;
		$msg = $l->t("Please enter a valid email address.");
	}else{
		$sql = "SELECT a.`id`,a.`email`,b.`email` FROM `web_admin` AS a
	RIGHT JOIN `web_member` AS b ON b.`user` = a.`username`
	WHERE b.`email` = '$email' OR a.`email` = '$email'";
		$query = $db->select_query($sql);
		$rows = $db->rows($query);
		if($rows >0){
			$error = true;
			$msg = $l->t("This email is already in use.");
		}
	}
}

if($error===false){
	@session_start();
	$token = sha1(session_id().$username);
	$_SESSION['token'] = $token;
}
header('Content-Type: application/json');
echo json_encode(array(
	'error' => $error,
	'message' => $msg,
	'token' => $token
));
exit(0);
