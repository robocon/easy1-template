<?php
defined('AM_EXEC') or die('Restricted access');

$action = addslashes($_GET['action']);
if (empty($action)) {
	?>
	<h1><?php echo $l->t('Forgot password'); ?></h1>
	<div class="modules-sub-title"></div>
	<div id="forgot-pass">
		<form method="post" class="pure-form pure-form-aligned" action="index.php?name=member&file=forget_pwd&action=send">
			<div class="pure-control-group">
				<label><?php echo $l->t('Email');?></label>
				<input name="email" type="text" id="email" size="33">
			</div>
			<div class="pure-control-group">
				<label><?= $l->t('Verify code');?></label>
				<img src="capcha/val_img.php?width=<?php echo CAPCHA_WIDTH ?>&height=<?php echo CAPCHA_HEIGHT ?>&characters=<?php echo CAPCHA_NUM ?>" class="captcha" />
				<input name="security_code" type="text" id="security_code" >
			</div>
			<div class="pure-control-group">
				<label>&nbsp;</label>
				<input class="pure-button pure-button-primary" type="submit" name="submit" value="<?= $l->t('Send email');?>">
			</div>
		</form>
	</div>
	<?php
} else if ($action == "send") {
	$email = addslashes($_POST['email']);

	$patt = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
	$match = preg_match($patt, $email);
	if($match==0 || empty($email)){
		header('Location: index.php?name=member&file=forget_pwd');
		$_SESSION['x_message'] = $l->t("Please input your email");
		exit(0);
	}
	    
	check_captcha($_POST['security_code']);

	$sql = "SELECT id,email,name,user FROM ".TB_MEMBER." WHERE email IN('$email');";
	$select = DBi::select($sql);
	$numrow = $select->num_rows;
	if ($numrow === 0 || $numrow===false) {
		$_SESSION['x_message'] = $l->t("Can not find an email");
		header('Location: index.php?name=member&file=forget_pwd');
		exit(0);
	} else {
		$dbarr = $select->fetch_assoc();
		if ($dbarr!==false) {
			$email = $dbarr['email'];
			$name = $dbarr['name'];
			$user = $dbarr['user'];
			$noncrypt = AM_Utilities::random_str();

			$set_key = sha1($noncrypt);
			DBi::update(TB_MEMBER, array("reset_key" => $set_key), "id=".$dbarr['id']);
			$domain = AM_Utilities::get_domain();
			$link_reset = $domain.'/index.php?name=member&file=forget_pwd&action=formreset&key='.$set_key;
			
			$config = AM_Utilities::getconfig();
			$to = array($name => $email);
			$from = array($config['title'] => $config['email']);
			$subject = $l->t("New password has been request");
			$message = $l->t("Dear")." $name,";
			$message .= "<p>&nbsp;</p>";
			$message .= $l->t("This mail was send because the 'forgot password'. To reset a new password, click the follow link").":";
			$message .= '<a href="'.$link_reset.'">'.$link_reset."</a><br>";
			$message .= $l->t("Username").": $user";
			$message .= "&nbsp;</p>";
			$message .= $l->t("Regards").",";
			$message .= $config['title'];

			if(AM_Utilities::sendemail($from, $to, $subject, $message)){
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

	DBi::connect();
	$sql = "SELECT id,email,name,user FROM ".TB_MEMBER." WHERE email IN('$email');";
	$select = DBi::select("SELECT id FROM ".TB_MEMBER." WHERE reset_key = '$key';");
	$rows = $select->num_rows;
	if($rows>0 && $match>0 ){
		?>
		<h1><?php echo $l->t('Reset your password');?></h1>
		<div class="modules-sub-title"></div>
		<form method="post" class="pure-form" action="index.php?name=member&file=forget_pwd&action=reset">
			<span><?php echo $l->t('New password')?></span>&nbsp;<input type="text" name="password">
			<input type="hidden" name="key" value="<?php echo $key?>">
			<input type="submit" class="pure-button pure-button-primary" value="<?php echo $l->t('reset')?>">
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
		DBi::connect();
		$query = DBi::select("SELECT id FROM ".TB_MEMBER." WHERE reset_key = '$key';");
		$member = $query->fetch_assoc();
		$data_update = array(
			"password" => md5($_POST['password']),
			"reset_key" => ""
		);
		DBi::update(TB_MEMBER, $data_update, "id = ".$member['id']);
		
		$query = DBi::select("SELECT id FROM ".TB_ADMIN." WHERE id = '".$member['id']."';");
		$admin_row = $query->num_rows;
		if($admin_row > 0){
			$admin = $query->fetch_assoc();
			DBi::update(TB_ADMIN, array("password" => md5($_POST['password'])), "id=".$admin['id']);
		}

		$_SESSION['x_message'] = $l->t("New password has been set");
		$_SESSION['form_key_reset'] = null;
		header('Location: index.php');
		exit(0);
	}
}
?>
