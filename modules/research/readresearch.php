<?php defined('AM_EXEC') or die('Restricted Access');
include ("editor.php");
DBi::connect();
?>
<script type="text/javascript">
function showemotion() {
	emotion1.style.display = 'none';
	emotion2.style.display = '';
}
function closeemotion() {
	emotion1.style.display = '';
	emotion2.style.display = 'none';
}
function emoticon(theSmilie) {
	document.form2.COMMENT.value += ' ' + theSmilie + ' ';
	document.form2.COMMENT.focus();
}
</script>
<table cellSpacing=0 cellPadding=0 width=750 border=0>
	<tbody>
		<tr>
			<td width="10" ></td>
			<td>
				<!-- research -->
				<h1><?php echo $l->t('Research'); ?></h1>
				<div class="modules-sub-title"></div>
				<table>
					<?php
					$id = intval($_GET['id']);
					$query = DBi::select("SELECT * FROM " . TB_RESEARCH . " WHERE id=?;", array($id));

					$research = $query->fetch_assoc();
					
					if (!$research['id']) {
						header('Location: index.php?name=research');
					} else {
						$content = $research['detail'];
						$Detail = stripslashes(FixQuotes($content));

						$query = DBi::select("SELECT * FROM " . TB_RESEARCH . " WHERE id=?;", array($id));
						$research = $query->fetch_assoc();

						$full = $research['full_text'];
						$abst = $research['abstract'];

						DBi::update(TB_RESEARCH, array('pageview' => 'pageview+1'), "id={$id}");

						$select = DBi::select("SELECT * FROM " . TB_RESEARCH_CAT . " WHERE id=?", array($research['category']));
						$research_cat = $select->fetch_assoc();
?>
			<tr>
			    <td colspan="2">
				<table>
				    <tr>
					<td valign="top">
					    <img src="icon/research_<?= $research['post_date']; ?>.jpg">
					</td>
					<td valign="top">
					    <table>
						<tr>
						    <td>
							<b><?= _FORM_MOD_READ_CONT; ?></b> <?= $research['topic']; ?><?= NewsIcon(TIMESTAMP, $research['post_date'], "images/icon_new.gif"); ?>
						    </td>
						</tr>
						<tr>
						    <td>
							<b><?= _FORM_CAT; ?></b> <?= $research_cat['category_name']; ?>
						    </td>
						</tr>
						<tr>
						    <td>
							<b><?= _RESEARCH_AUTHX; ?>:</b> <?= $research['auth']; ?>
						    </td>
						</tr>
						<tr>
						    <td>
							<b><?= _BLOG_MOD_DATE_ACC; ?> </b><?= ThaiTimeConvert($research['post_date'], "1", ""); ?>
						    </td>
						</tr>
						<tr>
						    <td>
							<b><?= _DETAIL_PRIVIEW; ?>:</b> <?= $research['pageview']; ?>
						    </td>
						</tr>
						<tr>
							<td>
								<b><?= _RESEARCH_MOD_DOWN_COUNT?></b>: <?= $research['rate']; ?> <?= _RESEARCH_MOD_DOWN_COUNT_NUM; ?>
							</td>
						</tr>
					    </table>
					<?php
					if ($_SESSION['admin_user']) {
						//Admin Login Show Icon
						?>
						<a href="?name=admin&file=research&op=research_edit&id=<?php echo $research['id']; ?>"><img src="images/admin/edit.gif" border="0" alt="<?= _FROM_IMG_EDIT; ?>" ></a> 
						<a href="javascript:Confirm('?name=admin&file=research&op=research_del&id=<?php echo $research['id']; ?>&prefix=<?php echo $research['post_date']; ?>','<?php echo _FROM_COMFIRM_DEL; ?>');"><img src="images/admin/trash.gif"  border="0" alt="<?= _FROM_IMG_DEL; ?>" ></a>
						<?php
					}
					?>
					</td>
				    </tr>
				    <tr>
						<td colspan=2> </td>
				    </tr>
				</table>
			    </td>
			</tr>
			<tr>
			    <td height="1" class="dotline" colspan="2" ></td>
			</tr>
			<tr>
			    <td colspan="2">
				<b><?= _RESEARCH_MOD_ABSTRACT; ?>:</b> <?= $Detail; ?>
			    </td>
			</tr>
			<tr>
			    <td height="1" class="dotline" colspan="2"></td>
			</tr>
			<?php if ($full || $abst) { ?>
			<tr>
				<td align="center">
				<?php if($full){ ?>
					<b><a href="index.php?name=research&file=rate&id=<?= $id; ?>&filess=full_text" target="_blank"> ( Download Fulltext )</a></b>
				<?php } ?>
				<?php if($abst){ ?>
					<b><a href="index.php?name=research&file=rate&id=<?= $id; ?>&filess=abstract" target="_blank"> ( Download <?= _RESEARCH_MOD_ABSTRACT; ?> )</a></b>
				<?php } ?>
				</td>
			</tr>
			<?php
			}
			?>
		    </table>
<?php
}
?>
<table>
	<tr>
		<td align="left">
			<div>
				<b><?= $research_cat['category_name']; ?> <?= _FROM_LINK_FIVECONT; ?></b>
			</div>
			<?php
			$sql = "SELECT * FROM " . TB_RESEARCH . " WHERE category=? ORDER BY id DESC LIMIT 5 ";
			$query = DBi::select($sql, array($research_cat['id']));
			$cat_research = $query->fetch_assoc();
			?>
			<ul>
			<?php
			while ($arr_research = $query->fetch_assoc()) {
				?>
				<li>
					<a href="?name=research&file=readresearch&id=<?= $arr_research['id']; ?>"><?= $arr_research['topic']; ?></a><?= ThaiTimeConvert($arr_research['post_date']); ?>
				</li>
				<?php
			}
			?>
			</ul>
		</td>
	</tr>
</table>
<style type="text/css">
.comment-contain { display: inline-block; width: 100%; border-bottom: 1px solid #CACACA; padding: 5px; }
.comment-contain > img { float: left; margin-right: 5px;}
.comment-detail { color: #999; }
.comment-message { margin-top: 11px; }
</style>
<?php
if ($research['enable_comment']) {

	$limit = 10;
	$page = intval($_GET['page']);

	$query = DBi::select("SELECT research_id FROM ".TB_RESEARCH_COMMENT." WHERE research_id = ?", array($research['id']));
	$sumpage = $query->num_rows;

	if ($page === 0) {
		$page = 1;
	}

	$rt = $sumpage % $limit;
	$totalpage = ($rt != 0) ? floor($sumpage / $limit) + 1 : floor($sumpage / $limit);
	$goto = ($page - 1) * $limit;

	$sql = "SELECT a.*,b.`member_pic` FROM `".TB_RESEARCH_COMMENT."` AS a
LEFT JOIN `".TB_MEMBER."` AS b ON b.`user` = a.`name`
WHERE a.`research_id` = '1' 
ORDER BY a.`id` ASC LIMIT $goto, $limit";
	$query = DBi::select($sql);
	$count = 1;

	while ($comment = $query->fetch_assoc()) {
	?>
	<div class="comment-contain" id="comment<?= $count?>">
	<?php
		$src = "icon/".$comment['member_pic'];
		if(!is_file($src)){
			$src = "icon/member_nrr.gif";
		}
		?>
		<img src="<?php echo $src?>" class="membericon">
		<div class="comment-detail">
			<?= $l->t('By');?>: <?= $comment['name']; ?> 
			<?= $l->t('on')."&nbsp;".ThaiTimeConvert($comment['post_date'], "1", "1"); ?>
			<a href="#comment<?= $count?>">#<?= $count; ?></a>
			
		</div>
		<div class="comment-message">
			<?= (stripslashes($comment['comment'])); ?>
		</div>
<?php 
		if ($_SESSION['admin_user']) {
echo " <a href=\"?name=research&file=delete_comment&id=" . $id . "&comment=" . $comment['id'] . "\"><IMG SRC=\"images/admin/trash.gif\" ></a>";
		}
?>
<?php
		$count++;
	?>
	</div>
	<?php
	}
?>
<div style="margin:7px 0; text-align:center;">
<?php
SplitPage($page, $totalpage, "?name=research&file=readresearch&id=" . $id . "");
echo $ShowSumPages;
echo "<BR>";
echo $ShowPages;
?>
</div>
		    <!-- Enable Comment -->
		    <table cellSpacing=0 cellPadding=0 width=550 border=0 align="center">
			<tbody>
			    <tr>
				<td width="10" vAlign=top><IMG src="images/fader.gif" border=0></td>
				<td width="490" vAlign=top align=left><IMG src="images/topfader.gif" border=0><BR>
				    <IMG SRC="images/menu/textmenu_comment.gif" BORDER="0"><BR>
				    <FORM NAME="form2" METHOD=POST ACTION="?name=research&file=comment&id=<?= $id; ?>">
					<table cellSpacing=5 cellPadding=0 width=550 border=0 align="center">
					    <tr>
						<td width="80" align="right"><B><?= _FROM_COMMENT_AUTH; ?> </B></td>
						<td><INPUT TYPE="text" NAME="NAME" style="width:300" <?php if ($_SESSION['login_true']) {
							echo "value=\"" . $_SESSION['login_true'] . "\" readonly style=\"color: #FF0000\" ";
						} ?><?php if ($_SESSION['admin_user']) {
							echo "value=\"" . $_SESSION['admin_user'] . "\" readonly style=\"color: #FF0000\" ";
						} ?>></td>
					    </tr>
<?php
							if ($_SESSION['login_true'] || $_SESSION['admin_user']) {

							} else {
								if (USE_CAPCHA) {
?>
						    <tr>
							<td width="80" align="right">
<?php
									if (CAPCHA_TYPE == 1) {
										echo "<img src=\"capcha/CaptchaSecurityImages.php?width=" . CAPCHA_WIDTH . "&height=" . CAPCHA_HEIGHT . "&characters=" . CAPCHA_NUM . "\" width=\"" . CAPCHA_WIDTH . "\" height=\"" . CAPCHA_HEIGHT . "\" align=\"absmiddle\" />";
									} else if (CAPCHA_TYPE == 2) {
										echo "<img src=\"capcha/val_img.php?width=" . CAPCHA_WIDTH . "&height=" . CAPCHA_HEIGHT . "&characters=" . CAPCHA_NUM . "\" width=\"" . CAPCHA_WIDTH . "\" height=\"" . CAPCHA_HEIGHT . "\" align=\"absmiddle\" />";
									}
?>
							</td>
							<td><input name="security_code" type="text" id="security_code" size="20" maxlength="6" style="width:80" > <?= _JAVA_CAPTCHA_ADD; ?> </td>
						    </tr>
<?php
								}
							}
?>
					    <tr>
						<td width="80" align="right" valign=top><B><?= _FROM_COMMENT_NUMX; ?> : </B></td>
						<td><TEXTAREA NAME="COMMENT" ROWS="10" COLS="100" style="width:400"></TEXTAREA>
	<script type="text/javascript">CKEDITOR.replace ( 'COMMENT',{toolbar: 'Mini'});</script>
						</td>
					    </tr>
					    <tr>
						<td width="80" align="right" valign=top></td>
						<td><input type="submit" class="es1-button" value="<?= _FROM_COMMENT_BUTTON_ADD; ?>"><BR>
						    <BR><?= _FROM_COMMENT_WORNING; ?>
						</td>
					    </tr>
					</table>
				    </FORM>
				</td>
			    </tr>
			</tbody>
		    </table>
		    <BR><BR>
		    <?= _FROM_COMMENT_AGREE; ?> <a href="mailto:<?= WEB_EMAIL; ?>"><?= WEB_EMAIL; ?></a> <?= _FROM_COMMENT_AGREE2; ?>
		    <BR><BR>
		    <!-- End Enable Comment -->
<?php
}
?>
		<!-- End research -->
		</td>
		</tr>
		</tbody>
		</table>
