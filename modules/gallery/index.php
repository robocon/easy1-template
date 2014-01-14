<?php
defined('AM_EXEC') or die('Restricted access');

if(empty($op)){
	?>
	<link rel="stylesheet" href="templates/easy1/css/gallery.css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<h1><?php echo $l->t('Gallery');?></h1>
	<div class="modules-sub-title"></div>
	<?php
    $sql = "SELECT p3.*,p1.`pic` FROM `".TB_GALLERY."` AS p1
INNER JOIN
(
    SELECT MAX(a.id) AS `id`, a.`category` FROM `".TB_GALLERY."` AS a
    GROUP BY a.`category`
) AS p2 ON p1.`id` = p2.`id`
RIGHT JOIN `".TB_GALLERY_CAT."` AS p3 ON p3.`id`=p1.`category`
ORDER BY p3.`id` DESC";
    $gallery_query = $db->select_query($sql);
    $rows = $db->rows($gallery_query);
    if($rows>0){
        while ($gallery = $db->fetch($gallery_query)) {
            ?>
            <div class="gallery-cover">
                <div>
                    <?php
                    $file = "images/gallery/gal_{$gallery['post_date']}/thb_{$gallery['pic']}";
                    $img_src = is_file($file) ? $file : "images/admin/16.png" ;
                    ?>
                    <a href="index.php?name=gallery&op=gallery_detail&id=<?=$gallery['id']?>"><i style="background-image:url('<?=$img_src?>')" class="gallery-img"></i></a>
                </div>
                <div class="gallery-title">
                    <p><b><a href="index.php?name=gallery&op=gallery_detail&id=<?=$gallery['id']?>"><?=$gallery['category_name']?></a></b></p>
                    <p class="gallery-date"><?=date('Y-m-d H:i:s', $gallery['post_date'])?></p>
                </div>
                <?php if($admin_user){ ?>
                <div class="gallery-edit album<?=$gallery['id']?>">
		<a href="index.php?name=admin&file=gallery&op=gallery_add"><i class="fa fa-camera-retro fa-lg" title="<?= $l->t('Add image')?>"></i></a>
		<a href="index.php?name=gallery&op=gallerycat_del&id=<?=$gallery['id']?>" onclick="return confirm('<?= $l->t('Confirm to delete?')?>');" title="<?= $l->t('Remove')?>"><i class="fa fa-trash-o fa-lg"></i></a>
                </div>
                <?php } ?>
            </div>
            <?php
        } // end while
    }

}else if($op=='gallery_detail'){
	?>
	<link rel="stylesheet" href="templates/easy1/css/gallery.css">
	<link rel="stylesheet" href="templates/easy1/css/colorbox.css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<script type="text/javascript" src="templates/easy1/js/jquery.colorbox-min.js"></script>
	<?php
	$id = intval($_GET['id']);

    // About title, date details etc.
    $sql = "SELECT * FROM ".TB_GALLERY_CAT." WHERE id = {$id};";
    $category_query = $db->select_query($sql);
    $rows = $db->rows($category_query);

    if($rows===false){
	    //header('Location: index.php?name=galley');
	    exit(0);
    }

    // About title, date details etc.
    $category = $db->fetch($category_query);

    $page = is_null($_GET['page']) ? 1 : intval($_GET['page']);
    $limit = $page>0 ? ($page-1)*24 : $page ;

    // For image and 
    $sql = "SELECT a.*,b.post_date AS cat_folder FROM ".TB_GALLERY." AS a,
    ".TB_GALLERY_CAT." AS b WHERE a.category = {$id} AND a.category=b.id ORDER BY a.id DESC LIMIT {$limit},24;";
    $gallery_query = $db->select_query($sql);

    // Num all images
    $sum = $db->rows($gallery_query);
    $sum = $sum===0 ? "0" : $sum ;
    ?>
	<h1><?=$category['category_name']; ?></h1>
	<div class="modules-sub-title"></div>
    <div id="gallery-detail">
        <p><b><?=_GALLERY_ALBUM_POSTED_DATE ?></b> <?=ThaiTimeConvert($category['post_date'], '1'); ?></p>
        <p><b><?=_ADMIN_GALLERY_SHOW_TOTAL_PIC; ?>:</b> <?=$sum; ?><?=_ADMIN_GALLERY_SHOW_TOTAL_NUM; ?></p>
        <?php
        if(!empty($category['category_detail'])){
        ?>
        <p><?=$category['category_detail']; ?></p>
        <?php } ?>
    </div>
    <?php

    if($sum!==false){
        while ($gallery = $db->fetch($gallery_query)) {
            
            $file = "images/gallery/gal_{$gallery['cat_folder']}/{$gallery['pic']}";
            $thumb = "images/gallery/gal_{$gallery['cat_folder']}/thb_{$gallery['pic']}";
            if(is_file($file)){
            ?>
            <div class="gallery-cover">
                <a href="<?=$file?>" title="<?=$gallery['post_date']?>" class="lightview">
                    <i style="background-image:url('<?=$thumb?>')" class="gallery-img"></i>
                </a>
                <?php if($admin_user){ ?>
                <div class="gallery-edit album<?=$gallery['id']?>">
		<a href="index.php?name=gallery&op=gallery_del&id=<?=$gallery['id']?>" onclick="return confirm('<?= $l->t('Confirm to delete?') ?>');" title="<?= $l->t('Remove');?>"><i class="fa fa-trash-o fa-lg"></i></a>
                </div>
                <?php } ?>
            </div>
            <?php
            }
        }
	?>
	<script type="text/javascript">
		jQuery.noConflict();
		(function( $ ) {
		$(function() {
			$(".lightview").colorbox({rel:'group'});
		});
		})(jQuery);
	</script>
	<?php
    }
	?><div><?php 
	// Show split page
	SplitPage($page, $rows, "index.php?name=gallery&op=gallery_detail&id=" . $id . "");
	echo $ShowSumPages;
	echo "<BR>";
	echo $ShowPages;
	?></div><?php 
} else if($op==="gallerycat_del"){

    if (CheckLevel($admin_user, $op)) {
	$id = intval($_GET['id']);
        if($id>0){
            $sql = "SELECT a.`post_date`, b.`id`, b.`pic` FROM `".TB_GALLERY_CAT."` a
                    LEFT JOIN `".TB_GALLERY."` AS b ON a.`id` = b.`category`
                    WHERE a.`id` = {$id} ";
            $select = $db->select_query($sql);
            $gallery = $db->fetch($sql);

            if($gallery['id']!==null){
                while($item = $db->fetch($select)){

                    // Delete image and thumb file
                    $sql = "DELETE FROM `".TB_GALLERY."` WHERE  `id`={$item['id']};";
                    $db->select_query($sql);
                    @unlink("images/gallery/gal_".$item['post_date']."/".$item['pic']);
                    @unlink("images/gallery/gal_".$item['post_date']."/thb_".$item['pic']);
                }
            }

            // Delete category and remove folder
            $sql = "DELETE FROM `".TB_GALLERY_CAT."` WHERE  `id`={$id};";
            $db->select_query($sql);
            @rmdir("images/gallery/gal_{$gallery['post_date']}");
        }

	$_SESSION['x_message'] = $l->t('Remove gallery successful');
	header('Location: index.php?name=gallery');
	exit(0);
    }else{
	    header('Location: index.php?name=gallery');
	    exit(0);
    }
    
}else if($op=="gallery_del"){
    
    $id = intval($_GET['id']);
    if (CheckLevel($admin_user, $op) && $id>0) {
        $sql = "SELECT a.`category`,a.`pic`,b.`post_date` FROM `".TB_GALLERY."` AS a
                LEFT JOIN `".TB_GALLERY_CAT."` AS b ON a.`category`=b.`id`
                WHERE a.`id` = {$id}";
	$query = $db->select_query($sql);
        $gallery = $db->fetch($query);
        if($gallery!==false){
            $sql = "DELETE FROM `".TB_GALLERY."` WHERE  `id`={$id};";
            $db->select_query($sql);

            @unlink("images/gallery/gal_".$gallery['post_date']."/".$gallery['pic']);
            @unlink("images/gallery/gal_".$gallery['post_date']."/thb_".$gallery['pic']);
        }

	$_SESSION['x_message'] = $l->t('Image has been delete');
	header('Location: index.php?name=gallery&op=gallery_detail&id='.$gallery['category']);
	exit(0);
    }else{
	header('Location: index.php?name=gallery&op=gallery_detail&id='.$gallery['category']);
	exit(0);
    }
}
