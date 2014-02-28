<?php defined('AM_EXEC') or die('Restricted Access');
DBi::connect();
$query = DBi::select("SELECT * FROM `".TB_NEWS."` ORDER BY `id` DESC LIMIT 0,10;");
if($query->num_rows > 0){
	?>
	<style type="text/css">
	    #news-lists{list-style: none;padding: 0;}
	    #news-lists li {border-bottom: 1px solid #ECECEC;margin-bottom: 7px;}
	    #news-lists a{font-size: 17px; font-weight: bold;}
	    #news-lists span{display: block;color: #999999;font-size: 11px;}
	</style>
	<ul id="news-lists">
		<?php
		while($news = $query->fetch_assoc()){
		    ?>
		    <li>
			<a href="index.php?name=news&file=readnews&id=<?php echo $news['id']; ?>"><?php echo $news['topic']; ?></a>
			<span>
			    <?php echo $news['posted']?>&nbsp;&middot;&nbsp;<?php echo ThaiTimeConvert($news['post_date'])?>&nbsp;&middot;&nbsp;<?php echo $l->t('View').' '.$news['pageview']?>
			</span>
		    </li>
		    <?php
		}
		?>
	</ul>
	<a href="index.php?name=news" class="readmore"><?php echo $l->t('Read more')?></a>
	<?php
}
