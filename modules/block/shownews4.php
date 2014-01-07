<?php defined('AM_EXEC') or die('Restricted Access');
$news4_query = $db->select_query("SELECT * FROM " . TB_NEWS . " WHERE category = 2 ORDER BY id DESC LIMIT 0, 9 ");
$i = 1;
?>
<style type="text/css">
    #general-lists{list-style: none;padding: 0;}
    #general-lists p{margin: 0}
    #general-lists li {border-bottom: 1px solid #ECECEC;padding-bottom: 7px;}
    #general-lists li.general-headline{float:left;width:100%;}
    #general-lists li img{float: left; margin-right: 5px;}
    #general-lists a{font-size: 17px; font-weight: bold;}
    #general-lists span{display: block;color: #999999;font-size: 11px;}
</style>
<ul id="general-lists">
    <?php
    while ($news4 = $db->fetch($news4_query)) {
        
        $all_rows = $db->num_rows(TB_NEWS_COMMENT,"id"," news_id = ".$news4['id']);
        $comment = $all_rows==FALSE ? "0" : $all_rows ;

        $head = $i<=4 ? 'class="general-headline"' : '' ;
    ?>
    <li <?php echo $head ?>>
        <a href="index.php?name=news&file=readnews&id=<?php echo $news4['id']; ?>"><?php echo $news4['topic']?></a>
        <div>
            <?php
            if($i<=4){
                if ($news4['pic'] == 1) {
                    $img = "icon/news_".$news4['post_date'].".jpg";
                }else{
                    $img = "images/icon/Apps.png";
                }
                ?>
                <img src="<?php echo $img?>" class="mysborder module-thumb">
                <p><?php echo strip_tags($news4['headline']) ?></p>
                <?php
            }
            ?>
        </div>
        <span class="modules-details"><?php echo $news4['posted']?>&nbsp;&middot;&nbsp;<?php echo ThaiTimeConvert($news4['post_date'], NULL, NULL)?>&nbsp;&middot;&nbsp;<?php echo $comment?> ความคิดเห็น</span>
    </li>
    <?php
        $i++;
    }
    ?>
</ul>
<a href="index.php?name=news&category=2" class="readmore"><?php echo $l->t('Read more'); ?></a>
