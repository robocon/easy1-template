<?php defined('AM_EXEC') or die('Restricted Access');
$action = addslashes($_GET['action']);
if(empty($action)){
?>
	<h1><?php echo $l->t("Contact"); ?></h1>
	<div class="modules-sub-title"></div>

	<div id="contact-box">
		<form class="pure-form pure-form-aligned" method="post" action="index.php?name=contact&action=sendmail">
		<fieldset>
			<div class="pure-control-group">
				<label for="name"><?= $l->t("Name"); ?></label>
				<input type="text" id="name" class="pure-input-1-3" name="name">
			</div>
			<div class="pure-control-group">
				<label for="yourmail"><?= $l->t("Email"); ?></label>
				<input type="email" id="yourmail" class="pure-input-1-3" name="email">
			</div>
			<div class="pure-control-group">
				<label for="subject"><?= $l->t("Subject"); ?></label>
				<input type="text" id="subject" class="pure-input-1-3" name="subject">
			</div>
			<div class="pure-control-group">
				<label for="details"><?= $l->t("Details"); ?></label>
				<textarea name="details" class="pure-input-1-3" id="details" rows="10"></textarea>
			</div>
			<div class="pure-control-group">
				<label for="security_code">
					<span><?= $l->t('Captcha')?></span>
				</label>
				<img src="capcha/val_img.php?width=60&height=25&characters=4" />
				<input name="security_code" type="text" id="security_code" >
			</div>
			<div class="pure-control-group">
				<label>&nbsp;</label>
				<input type="submit" class="pure-button pure-button-primary" value="<?php echo $l->t("Send"); ?>">
			</div>
		</fieldset>
		</form>
	</div>
<?php
}else if($action=="sendmail"){

	$email = addslashes($_POST['email']);
	$name = htmlspecialchars($_POST['name'], ENT_XHTML);
	$subject = htmlspecialchars($_POST['subject'], ENT_XHTML);
	$details = htmlspecialchars($_POST['details'], ENT_XHTML);

	if(empty($email) OR empty($name) OR empty($subject) OR empty($details)){
		header('Location: index.php?name=contact');
		$_SESSION['x_message'] = $l->t("You must fill in all of the fields.");
		exit();
	}

	if (!preg_match("/^\w+([\w\-\.])+\@([\w\-])+(\.[\w]+)+$/", $email)) {
		header('Location: index.php?name=contact');
		$_SESSION['x_message'] = $l->t("Please enter a valid email address.");
		exit();
	}

	check_captcha($_POST['security_code']);

	$config = AM_Utilities::get_config();
	$from = array($name => $email);
	$to = array($config['title'] => $config['email']);
	$send = AM_Utilities::sendemail($from, $to, $subject, $details);
	if($send===true){
		DBi::connect();
		$data = array(
			'subject' => $subject,
			'detail' => $details,
			'form_mail' => $email
		);
		$insert = DBi::insert(TB_MAIL, $data);
		if($insert===false){
			DBi::get_error();
		}

		$msg = $l->t("Send an email successful");
	}else{
		$msg = $l->t("Can not send an email, Please contact admin");
	}

	header('Location: index.php?name=contact');
	$_SESSION['x_message'] = $msg;
	exit();
}
?>


