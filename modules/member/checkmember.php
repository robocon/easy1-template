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

$error = false;
if(empty($username) || empty($password) || empty($email) || empty($first_name) || empty($last_name) 
|| empty($day) || empty($month) || empty($year)){
	$error = true;
	$msg = $l->t("You must fill in all of the fields.");
}else{
	if(!preg_match('/.+@.+(\.[a-zA-Z0-9]{2,}){1,}+$/', $email)){
		$error = true;
		$msg = $l->t("Please enter a valid email address.");
	}
}

/**
 * TODO:
 * - Check admin & user from $_POST -> username
 * - Check email
 * - ADD CAPTCHA FROM FORM **
 */

header('Content-Type: application/json');
echo json_encode(array(
	'error' => $error,
	'message' => $msg
));
exit(0);
