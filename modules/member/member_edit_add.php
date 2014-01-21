<?php defined('AM_EXEC') or die('Restricted Access');

header('Location: index.php?name=member&file=index');
$_SESSION['x_message'] = $l->t("Server maintenance");
exit(0);