<?php defined('AM_EXEC') or die('Restricted Access');
$query = $db->select_query("SELECT * FROM " . TB_RESEARCH . " ORDER BY id DESC LIMIT 7 ");
?>
<style type="text/css">
    #research-lists{list-style: none;padding: 0;}
    #research-lists li {border-bottom: 1px solid #ECECEC;margin-bottom: 7px;}
    #research-lists a{font-size: 17px; font-weight: bold;}
    #research-lists span{display: block;color: #999999;font-size: 11px;}
</style>
<ul id="research-lists">
    <?php
    while ($research = $db->fetch($query)) {
    ?>
    <li>
        <a href="index.php?name=research&file=readresearch&id=<?php echo $research['id']?>"><?php echo $research['topic']?></a>
        <?php echo NewsIcon(TIMESTAMP, $research['post_date'], "images/icon_new.gif"); ?>
        <span>
            <?php echo $research['auth']?>&nbsp;&middot;&nbsp;<?php echo ThaiTimeConvert($research['post_date'])?>
        </span>
    </li>
    <?php
    }
    ?>
</ul>
<a href="index.php?name=research" class="readmore"><?php echo $l->t('Read more'); ?></a>