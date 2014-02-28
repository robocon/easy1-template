<?php
defined('AM_EXEC') or die('Restricted access');
$op = addslashes($_GET['op']);
if(empty($op)){
?>
	<link rel="stylesheet" href="templates/easy1/css/gallery.css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<h1><?php echo $l->t('Gallery');?></h1>
	<div class="modules-sub-title"></div>
<?php
	DBi::connect();
	$sql = "SELECT p3.*,p1.`pic` FROM `".TB_GALLERY."` AS p1
		INNER JOIN
		(
			SELECT MAX(a.id) AS `id`, a.`category` FROM `".TB_GALLERY."` AS a
			GROUP BY a.`category`
		) AS p2 ON p1.`id` = p2.`id`
		RIGHT JOIN `".TB_GALLERY_CAT."` AS p3 ON p3.`id`=p1.`category`
		ORDER BY p3.`id` DESC";
	$query = DBi::select($sql);
	$rows = $query->num_rows;
	if($rows>0){
		while($gallery = $query->fetch_assoc()){
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
	DBi::connect();
	// About title, date details etc.
	$sql = "SELECT * FROM ".TB_GALLERY_CAT." WHERE id = ?;";
	$query = DBi::select($sql, array($id));
	$rows = $query->num_rows;
	if($rows===false){
		//header('Location: index.php?name=galley');
		exit(0);
	}

	// About title, date details etc.
	$category = $query->fetch_assoc();

	$page = is_null($_GET['page']) ? 1 : intval($_GET['page']);
	$limit = $page>0 ? ($page-1)*24 : $page ;

	// For image and 
	$sql = "SELECT a.*,b.post_date AS cat_folder FROM ".TB_GALLERY." AS a,
		".TB_GALLERY_CAT." AS b WHERE a.category = ? AND a.category=b.id ORDER BY a.id DESC LIMIT ?,24;";
	$gallery_query = DBi::select($sql, array($id, $limit));

	// Num all images
	$sum = $gallery_query->num_rows;
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
			while($gallery = $gallery_query->fetch_assoc()){
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
			DBi::connect();
			$sql = "SELECT a.`post_date`, b.`id`, b.`pic` FROM `".TB_GALLERY_CAT."` a
				LEFT JOIN `".TB_GALLERY."` AS b ON a.`id` = b.`category`
				WHERE a.`id` = ?; ";
			$select = DBi::select($sql, array($id));
			$gallery = $select->fetch_assoc();

			if($gallery['id']!==null){
				while($item = $select->fetch_assoc()){

					// Delete image and thumb file
					$sql = "DELETE FROM `".TB_GALLERY."` WHERE  `id`=?;";
					DBi::select($sql, array($item['id']));
					@unlink("images/gallery/gal_".$item['post_date']."/".$item['pic']);
					@unlink("images/gallery/gal_".$item['post_date']."/thb_".$item['pic']);
				}
			}

			// Delete category and remove folder
			$sql = "DELETE FROM `".TB_GALLERY_CAT."` WHERE  `id`=?;";
			DBi::select($sql, array($id));
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
		DBi::connect();
		$sql = "SELECT a.`category`,a.`pic`,b.`post_date` FROM `".TB_GALLERY."` AS a
			LEFT JOIN `".TB_GALLERY_CAT."` AS b ON a.`category`=b.`id`
			WHERE a.`id` = ?;";
		$query = DBi::select($sql, array($id) );
		$gallery = $query->fetch_assoc();
		if($gallery!==false){
			$sql = "DELETE FROM `".TB_GALLERY."` WHERE  `id`=?;";
			DBi::select($sql, array($id));

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
