<?php  
defined('AM_EXEC') or die('Restricted access');

$category = empty($_GET['category']) ? "" : intval($_GET['category']);
$fields = empty($_POST['fields']) ?  "" : addslashes($_POST['fields']);
$keyword = empty($_POST['keyword']) ?  "" : addslashes($_POST['keyword']);

DBi::connect();
?>
<script language='JavaScript'>
function checkboard() {
	if (document.formboard.keyword.value == '') {
		alert('<?php echo _FROM_SEARCH_NULL; ?>');
		document.formboard.keyword.focus();
		return false;
	}
	else{
		return true;
	}
}
</script> 

<h1><?php echo $l->t('Research'); ?></h1>
<div class="modules-sub-title"></div>
<table>
	<tr>
		<td height="1" ></td>
	</tr>
	<tr>
		<td>
			<?php
			if ($_SESSION['admin_user'] || $_SESSION['login_true']) {
			?>
				<a href="index.php?name=admin&file=research&op=research_add"><img src="images/admin/book.gif"  border="0" align="absmiddle"><?= $l->t('Add research'); ?></a>
			<?php
			}
			?>
<table align="left">
	<tr>
		<td>
			<!-- FROM SEARCH BY TOPIC -->
			<form name="formsearch" method="post" action="?name=research">
				<?= $l->t('Search word'); ?> <input type="text" name="keyword" value="<?php echo"$keyword"; ?>">
				<?= $l->t('from'); ?>
				<select name="fields">
					<option value="id" <?php if ($fields == 'id') { echo "selected"; } ?>><?= _FROM_SEARCH_FIELD_ID; ?> </option>
					<option value="topic" <?php if ($fields == 'topic') { echo "selected"; } ?>><?= _FROM_SEARCH_FIELD_TOPIC; ?> </option>
					<option value="headline" <?php if ($fields == 'headline') { echo "selected"; } ?>><?= _FROM_SEARCH_FIELD_HEADLINE; ?></option>
				</select>
				<input type="hidden" name="category" value="<?= $category; ?>">
				<input type="submit" class="pure-button pure-button-primary" name="Submit" value="<?= $l->t('Search'); ?>">
				<img src="images/admin/opendir.gif" align="absmiddle"><a href="?name=research"><?= $l->t('See all'); ?></a>
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<!-- FORM SEARCH BY CATEGORY -->
			<form name="categoty" method="post">
				<?= $l->t('Select from group'); ?>
				<select name="category" onchange="if (options[selectedIndex].value) { window.location = options[selectedIndex].value } ; ">
					<option value="?name=research"><?= _FROM_SEARCH_CAT_ALL; ?></option>
<?php
$cat_search = DBi::select("SELECT * FROM " . TB_RESEARCH_CAT . " ORDER BY sort;");
while ($item = $cat_search->fetch_assoc()) {
	$cat_select = '';
	if ($category == $item['id']) {
		$cat_select = "selected";
	}
?>
						<option value="?name=research&category=<?php echo $item['id']?>" <?php echo $cat_select?> ><?php echo $item['category_name']?></option>
<?php
}
?>
				</select>
			</form>
		</td>
	</tr>
	<tr>
		<td height="1" colspan="2" ></td>
	</tr>
</table>
<?php
$where = '';
if ($category!='' || $keyword!=='') {
	$where .= " WHERE";
	if($category){
		$where .= " category = '$category'";
	}

	if($keyword){
		if($fields=='id'){
			$where .= " id = '$keyword' ";
		}else{
			$where .= " $fields like '%$keyword%' ";
		}
	}
}
$limit = 20;
$SUMPAGE = DBi::select("SELECT id FROM ".TB_RESEARCH." ".$where);

if (empty($page)) {
	$page = 1;
}
$rt = $SUMPAGE % $limit;
$totalpage = ($rt != 0) ? floor($SUMPAGE / $limit) + 1 : floor($SUMPAGE / $limit);
$goto = ($page - 1) * $limit;
?>
<form action="?name=admin&file=research&op=research_del&action=multidel" name="myform" method="post">
	<table width="100%" cellspacing="0" cellpadding="0" class="grids">
		<tr class="odd">
			<th width="55"><?= $l->t('#'); ?></th>
			<th align=center><?= $l->t('Topic'); ?></th>
			<th width="100"><?= $l->t('Date'); ?></th>
			<th width="100"><?= $l->t('Group'); ?></th>
			<th width="50"><?= $l->t('Download file');?></th>
		</tr>  
		<?php
		$research_sql = DBi::select("SELECT * FROM " . TB_RESEARCH . " $where ORDER BY id DESC LIMIT $goto, $limit ");
		$rank = 1;
		$count = 0;
		while ($research = $research_sql->fetch_assoc()) {
			if ($page > 1) {
				$p = $page * 10;
				$ranks = $rank + $p;
			} else {
				$ranks = $rank;
			}
		?>
		<tr>
			<td valign="top">
		<?php
		$query = DBi::select("SELECT * FROM " . TB_RESEARCH_CAT . " WHERE id=?;", array($research['category']));
		$research_category = $query->fetch_assoc();
		$newsid = $research['id'];

		//Comment Icon
		$CommentIcon = "";
		if ($research['enable_comment']) {
			$CommentIcon = " <img src=\"images/icon/suggest.gif\" WIDTH=\"13\" HEIGHT=\"9\" border=\"0\" ALIGN=\"absmiddle\">";
		}

		echo $ranks;
		if ($_SESSION['admin_user']) {
			?>
			<a href="?name=admin&file=research&op=research_edit&id=<?php echo $research['id']; ?>"><img src="images/admin/edit.gif" alt="<?= _FROM_IMG_EDIT; ?>" title="<?= _FROM_IMG_EDIT; ?>"></a> 
			<a href="javascript:Confirm('?name=admin&file=research&op=research_del&id=<?php echo $research['id']; ?>&prefix=<?php echo $research['post_date']; ?>','<?php echo _FROM_COMFIRM_DEL; ?>');"><img src="images/admin/trash.gif" alt="<?= _FROM_IMG_DEL; ?>" title="<?= _FROM_IMG_DEL; ?>"></a>
			<?php
		}
?>
			</td> 
			<td valign="top">
				<a href="?name=research&file=readresearch&id=<?php echo $research['id']; ?>"><?php echo $research['topic']; ?></a>
				<?= $CommentIcon; ?>
				<?= NewsIcon(TIMESTAMP, $research['post_date'], "images/icon_new.gif"); ?>
				<font color="#CC3300">( <?= _FORM_MOD_READ; ?> <?= $research['pageview']; ?> / <?= _FORM_MOD_DONWLOAD; ?> : <?= $research['rate']; ?> )</font> <?= _RESEARCH_AUTH; ?> <font color="#CC3300"><?= $research['auth']; ?></font>
			</td>
			<td valign="top" align="center">
				<?php echo ThaiTimeConvert($research['post_date'], '', ''); ?>
			</td>
			<td align="center" valign="top">
				<?php echo $research_category['category_name']; ?>
			</td>
			<td align="center"  valign="top">
				<?php
				if ($research['full_text']) {
					$fullt = $research['posted'];
					$timedd = $research['post_date'];
				?>
				<a href="?name=research&file=rate&id=<?= $research['id']; ?>&filess=<?= $research['full_text']; ?>">FullText</a>
				<?php
				} else {
					echo "-";
				}
				?>
			</td>
		</tr>
	<?php
	$rank++;
	$count++;
}
?>
	</table>
</form>
<?php
SplitPage($page, $totalpage, "?name=research");
echo $ShowSumPages;
echo "<br>";
echo $ShowPages;
?>
</td>
</tr>
</table>
