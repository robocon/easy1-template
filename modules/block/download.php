<?php defined('AM_EXEC') or die('Restricted Access');
$query = $db->select_query("SELECT * FROM `".TB_DOWNLOAD."` WHERE `status`='1' ORDER BY `id` DESC LIMIT 10;");
?>
<style type="text/css">
    #download-lists{list-style: none;padding: 0px;}
    #download-lists li {border-bottom: 1px solid #ECECEC;margin-bottom: 7px;}
    #download-lists a{font-size: 17px; font-weight: bold;}
    #download-lists span{display: block;color: #999999;font-size: 11px;}
</style>
<ul id="download-lists">
<?php
while($download = $db->fetch($query)){
    ?>
    <li>
        <a href="index.php?name=download&file=readdownload&id=<?php echo $download['id']; ?>"><?php echo $download['topic']; ?></a>
        <span>
            <?php echo $download['posted']?>&nbsp;&middot;&nbsp;
            <?php echo ThaiTimeConvert($download['post_date'])?>&nbsp;&middot;&nbsp;
            <?php echo $l->t('View').' '.$download['pageview']?>
        </span>
    </li>
    <?php
}
?>
</ul>
<a href="index.php?name=download" class="readmore"><?php echo $l->t('Read more')?></a>