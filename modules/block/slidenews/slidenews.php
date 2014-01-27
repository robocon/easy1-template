<?php defined('AM_EXEC') or die('Restricted Access');
$db->connectdb(DB_NAE,DB_USERNAME,DB_PASSWORD);
$sql = "SELECT b.id,a.rm_news,a.rm_image,a.rm_topic FROM `".TB_RANDOM."` AS a
LEFT JOIN `".TB_NEWS."` AS b ON a.`rm_news` = b.`id`
WHERE a.`status` = 1 AND b.`ran` = 1
ORDER BY a.`id` DESC 
LIMIT 10";
$query = $db->select_query($sql);
$rows = $db->rows($query);
if($rows > 0){
	?>
	<!-- bxSlider Javascript file -->
	<script src="templates/easy1/modules/block/slidenews/jquery.bxslider.min.js"></script>
	<!-- bxSlider CSS file -->
	<link href="templates/easy1/modules/block/slidenews/jquery.bxslider.css" rel="stylesheet" />
	<script type="text/javascript">
	jQuery.noConflict();
	(function( $ ) {
	  $(function() {
		  $('.bxslider').bxSlider({
			captions: true,
			adaptiveHeight: true,
			slideWidth: 530,
			auto: true
		  });
	  });
	})(jQuery);
	</script>
	<ul class="bxslider">
	<?php
	while($item = $db->fetch($query)){
		?>
		<li>
			<a href="<?php echo 'index.php?name=news&file=readnews&id='.$item['id'];?>">
				<img src="icon/<?php echo $item['rm_image']?>" title="<?php echo $item['rm_topic'];?>">
			</a>
		</li>
		<?php
	}
	?>
	</ul>
	<?php
}
?>
