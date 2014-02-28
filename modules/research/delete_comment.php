<?php 
defined('AM_EXEC') or die('Restricted access');
CheckAdmin($_SESSION['admin_user'], $_SESSION['admin_pwd']);

$id = intval($_GET['id']);
$comment = intval($_GET['comment']);
if (CheckLevel($_SESSION['admin_user'], "research_del")) {
	DBi::connect();
	DBi::select("DELETE FROM ".TB_RESEARCH_COMMENT." WHERE research_id='$id' AND id='$comment'");
}

header('Location: index.php?name=research&file=readresearch&id='.$id);
