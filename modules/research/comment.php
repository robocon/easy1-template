<?php
defined('AM_EXEC') or die('Restricted access');
$name = addslashes($_POST['NAME']);
$comment = addslashes($_POST['COMMENT']);
$id = intval($_GET['id']);

if (!$name OR !$comment OR $id==0) {
	header('Location: index.php?name=research&file=readresearch&id='.$id);
}

if(!$_SESSION['login_true'] && !$_SESSION['admin_user']){
	if (USE_CAPCHA) {
		check_captcha($_POST['security_code']);
	}
}

DBi::connect();
$data = array(
    "research_id" => $id,
    "name" => htmlspecialchars($name),
    "comment" => $comment,
    "ip" => $IPADDRESS,
    "post_date" => TIMESTAMP
);
Dbi::insert(TB_RESEARCH_COMMENT, $data);

header('Location: index.php?name=research&file=readresearch&id='.$id);
exit(0);
