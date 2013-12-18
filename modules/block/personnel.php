<?php defined('AM_EXEC') or die('Restricted Access');
$query = $db->select_query("SELECT `p_name`,`p_position`,`p_data`,`p_pic` FROM `".TB_personnel."` WHERE `boss`=1 LIMIT 1;");
$head = $db->fetch($query);
?>
<style type="text/css">
.personal-head{text-align: center;}
.personal-head > img{box-shadow: 1px 1px 5px #333;}
.personal-details{display: block;font-weight: bold;}
</style>
<div class="personal-head">
	<img src="images/personnel/thb_<?php echo $head['p_pic'];?>">
	<span class="personal-details"><?php echo $head['p_name'];?></span>
	<span class="personal-details"><?php echo $head['p_position'];?></span>
	<span class="personal-details"><?php echo $head['p_data'];?></span>
</div>
<?php

$query = $db->select_query("SELECT `gp_id`, `gp_name` FROM `".TB_personnel_group."` ORDER BY `gp_id` ASC LIMIT 5;");
?>
<ul class="menu-lists">
	<?php
	while ($group = $db->fetch($query)) {
		?>
		<li>
			<a href="index.php?name=personnel&file=gdetail&id=<?php echo $group['gp_id'];?>"><?php echo $group['gp_name'];?></a>
		</li>
		<?php
	}
	?>
</ul>
<a href="index.php?name=personnel&file=detail" class="readmore"><?php echo $l->t('More')?></a>
<?php
