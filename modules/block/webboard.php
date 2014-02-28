<?php defined('AM_EXEC') or die('Restricted Access'); ?>
<style type="text/css">
.webboard-topic a{font-size: 17px; color: #357EB0!important; }
.webboard-detail,
.webboard-topic span {color: #999; font-size: 11px; }
</style>
<table class="grids" id="webboard-latest">
	<tr>
		<th><?php echo $l->t('Recent Topic'); ?></th>
		<th width="25%"><?php echo $l->t('Category'); ?></th>
	</tr>
	<?php
	DBi::connect();
	$sql = "SELECT a.`id`,a.`topic`,a.`post_name`,a.`post_update`,b.`post_name` AS `comment_name`,c.`id` AS `category_id`, c.`category_name`
FROM `".TB_WEBBOARD."` AS a
LEFT JOIN `".TB_WEBBOARD_COMMENT."` AS b ON a.`post_update` = b.`post_date`
LEFT JOIN `".TB_WEBBOARD_CAT."` AS c ON a.`category` = c.`id`
ORDER BY a.`post_update` DESC 
LIMIT 10";
	$query = DBi::select($sql);
	while($item = $query->fetch_assoc()){
		$post_name = empty($item['comment_name']) ? $item['post_name'] : $item['comment_name'] ;
	?>
	<tr>
		<td>
			<div class="webboard-topic">
				<a href="index.php?name=webboard&file=read&id=<?php echo $item['id']; ?>"><?php echo $item['topic']; ?></a>
				<span><b><?php echo $l->t('By'); ?></b> <?php echo $post_name;?></span>
			</div>
			<div class="webboard-detail"><?php echo ThaiTimeConvert($item['post_update'],null,1); ?></div>
		</td>
		<td align="center"><a href="index.php?name=webboard&file=board&category=<?php echo $item['category_id']; ?>"><?php echo $item['category_name']; ?></a></td>
	</tr>
	<?php
	}
	?>
</table>
