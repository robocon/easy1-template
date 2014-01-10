<?php 
defined('AM_EXEC') or die('Restricted access');
CheckAdmin($admin_user, $admin_pwd);

$id = intval($_GET['id']);
$comment = intval($_GET['comment']);
if (CheckLevel($admin_user, "research_del")) {
	$db->del(TB_RESEARCH_COMMENT, " research_id='$id' AND id='$comment' ");
}

header('Location: index.php?name=research&file=readresearch&id='.$id);
