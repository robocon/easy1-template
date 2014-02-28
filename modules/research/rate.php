<?php
defined('AM_EXEC') or die('Restricted access');
$id = intval($_GET['id']);
$filess = addslashes($_GET['filess']);
if ($filess !== "abstract" && $filess !== "full_text") {
    header('Location: index.php');
    exit;
}

DBi::connect();
$query = DBi::select("SELECT ".$filess." FROM ".TB_RESEARCH." WHERE id=?", array($id));
$fetch = $query->fetch_assoc();

DBi::update(TB_RESEARCH, array('rate' => 'rate+1'), "id={$id}");

$wb_picture = "data/" . $fetch[$filess];
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($wb_picture));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($wb_picture));
ob_clean();
flush();
readfile($wb_picture);
exit(0);
