<?php
defined('AM_EXEC') or die('Restricted access');

function randomstr($length = 8){
    $str = "abcdefghijkmnpqrstuvwxyz123456789ABCDEFGHJKLMNPQRSTUVWXYZ";
    $len = strlen($str);
    $ran = '';
    for ($i = 0; $i <= $length; $i ++) {
	$ran .= $str[mt_rand(0, $len -1)];
    }
    return $ran;
}

$action = addslashes($_GET['action']);

if (empty($action)) {
	?>
	<style type="text/css">
	#forgot-pass div{ margin: 5px 0px; }
	#forgot-pass form{ width: 500px; margin: 0 auto; }
	#forgot-pass label{ display: inline-block; width: 80px; text-align: right; }
	</style>
	<h1><?php echo $l->t('Forgot password'); ?></h1>
	<div class="modules-sub-title"></div>
	<div id="forgot-pass">
		<form method="post" action="index.php?name=member&file=forget_pwd&action=send">
			<div>
				<label><?php echo $l->t('Email');?></label>
				<input name="email" type="text" id="email" size="33">
			</div>
			<div>
				<label><?= $l->t('Verify code');?></label>
				<img src="capcha/val_img.php?width=<?php echo CAPCHA_WIDTH ?>&height=<?php echo CAPCHA_HEIGHT ?>&characters=<?php echo CAPCHA_NUM ?>" class="captcha" />
				<input name="security_code" type="text" id="security_code" >
			</div>
			<div>
				<label>&nbsp;</label>
				<input id="submit-btn" type="submit" name="submit" value="<?= $l->t('Send email');?>">
			</div>
		</form>
	</div>
	<?php
} else if ($action == "send") {
    
	$patt = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
	$match = preg_match($patt, $_POST['email']);
	if($match==0 || empty($_POST['email'])){
		header('Location: index.php?name=member&file=forget_pwd');
		exit(0);
	}
	    
	check_captcha($_POST['security_code']);

	$sql = "SELECT id,email,name,user FROM " . TB_MEMBER . " WHERE email IN('%s');";
	$query = $db->select_query(sprintf($sql, $_POST['email']));
	$numrow = $db->rows($query);
	if ($numrow === 0 || $numrow===false) {
		$_SESSION['x_message'] = $l->t("Can not find an email");
		header('Location: index.php?name=member&file=forget_pwd');
		exit(0);
	} else {
		$dbarr = $db->fetch($query);
		if ($result !== false && $dbarr!==false) {
			$email = $dbarr['email'];
			$name = $dbarr['name'];
			$user = $dbarr['user'];
			$noncrypt = randomstr();

			$set_key = sha1($noncrypt);
			$db->update_db(TB_MEMBER, array("reset_key" => $set_key), "id=" . $dbarr['id']);
			$link_reset = WEB_URL.'/index.php?name=member&file=forget_pwd&action=formreset&key='.$set_key;

			// Send an email
			$admin_mail = WEB_EMAIL;
			$home = WEB_URL;
			$to = $name.' <'.$email.'>';
			$from = WEB_EMAIL;
			$headers = 'MIME-Version: 1.0'."\r\n"
			.'Content-type: text/html; charset=utf-8'."\r\n"
			.'To: '.$name.' <'.$email.'>'."\r\n"
			.'From: '.WEB_EMAIL."\r\n"
			.'Reply-to: '.WEB_EMAIL."\r\n"
			.'X-Mailer: PHP mailer'."\r\n";
			$subject = $l->t("New password has been request");
			$message = "<p>".$l->t("Dear")." $name,</p>";
			$message .= "<p>&nbsp;</p>";
			$message .= "<p>".$l->t("This mail was send because the 'forgot password'. To reset a new password, click the follow link").":</p>";
			$message .= '<a href="'.$link_reset.'">'.$link_reset.'</a>';
			$message .= "<p>".$l->t("Username").": $user</p>";
			$message .= "<p>&nbsp;</p>";
			$message .= "<p>".$l->t("Regards").",</p>";
			$message .= "<p>".WEB_TITILE."</p>";
			if(@mail($to,$subject,$message,$headers,$from)){
				$_SESSION['x_message'] = $l->t("Mail has been send, please check your email to change your password");
			}else{
				$_SESSION['x_message'] = $l->t("Can not send an email, please contact to system admin");
			}
			header('Location: index.php?name=member&file=forget_pwd');
			exit(0);
	}
    }
}else if($action=='formreset'){

	$key = addslashes($_GET['key']);
	$match = preg_match('/([a-z0-9]){40}/i', $key);

	$select = $db->select_query("SELECT id FROM ".TB_MEMBER." WHERE reset_key = '$key';");
	$rows = $db->rows($select);
	if($rows>0 && $match>0 ){
		?>
		<h1><?php echo $l->t('Reset your password');?></h1>
		<form method="post" action="index.php?name=member&file=forget_pwd&action=reset">
			<span><?php echo $l->t('New password')?></span>&nbsp;<input type="text" name="password">
			<input type="hidden" name="key" value="<?php echo $key?>">
			<input type="submit" value="<?php echo $l->t('reset')?>">
		</form>
		<?php	
		$_SESSION['form_key_reset'] = $key;
	}else{
		header('Location: index.php');
		exit(0);
	}
}else if($action=='reset'){
	$key = addslashes($_POST['key']);
	$match = preg_match('/([a-z0-9]){40}/i', $key);

	if($key===$_SESSION['form_key_reset'] && $match>0){
	
		$select = $db->select_query("SELECT id FROM ".TB_MEMBER." WHERE reset_key = '$key';");
		$member = $db->fetch($select);
		$db->update_db(TB_MEMBER,array("password" => md5($_POST['password']), "reset_key" => ''),"id = ".$member['id']);

		$query = $db->select_query("SELECT id FROM ".TB_ADMIN." WHERE id = '".$member['id']."';");
		$admin_row = $db->rows($query);
		if($admin_row > 0){
			$admin = $db->fetch($query);
			$db->update(TB_ADMIN,"password = '".md5($_POST['password'])."'","id = ".$admin['id']);
		}

		$_SESSION['x_message'] = $l->t("New password has been set");
		$_SESSION['form_key_reset'] = null;
		header('Location: index.php');
		exit(0);
	}
}
?>
