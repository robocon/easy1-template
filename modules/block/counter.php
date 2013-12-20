<?php defined('AM_EXEC') or die('Restricted Access'); ?>
<style type="text/css">
.online-item, .online-head{text-align: center; }
.online-lists{margin: 0; padding: 0; list-style: none; }
.online-head{font-weight: bold; cursor: pointer; }
.online-right{float: right; }
</style>
<table>
	<tr>
		<td colspan="2" class="online-item">
			<b><?php echo $l->t('From'); ?></b>&nbsp;<?php echo ThaiTimeConvert(WEB_TIMESTART); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="online-item">
			<?php 
			$IPADDRESS=get_real_ip();

			// Set delay @10 min
			$delay = time()-600;
			$main_query = "SELECT COUNT(`ct_no`) AS `active_row` FROM `".TB_ACTIVEUSER."` ";

			$sql = $main_query." WHERE ct_time >= $delay";
			$query = $db->select_query($sql);
			$now = $db->fetch($query);
			?>
			<b><?php echo $l->t('Current user online'); ?></b>&nbsp;<?php echo $now['active_row'] ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="online-item">
			<b><?php echo $l->t('Your current ip'); ?></b>: <?php echo $IPADDRESS ?>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td>
						<?php echo $l->t('Today');?>
					</td>
					<td align="right">
						<?php
						$today_sql = $main_query." WHERE `ct_time` >= ".(time()-86400);
						$query = $db->select_query($today_sql);
						$today = $db->fetch($query);
						?>
						<?php echo $today['active_row'] ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $l->t('Yesterday');?>
					</td>
					<td align="right">
						<?php
						$yesterday_sql = $main_query." WHERE `ct_yyyy`='".date('Y')."' AND `ct_mm`='".date('m')."' AND ct_dd='".(date('d')-1)."';";
						$query = $db->select_query($yesterday_sql);
						$yesterday = $db->fetch($query);
						?>
						<?php echo $yesterday['active_row']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $l->t('This month');?>
					</td>
					<td align="right">
						<?php
						$month_sql = $main_query." WHERE `ct_yyyy`='".date('Y')."' AND `ct_mm`='".date('m')."';";
						$query = $db->select_query($month_sql);
						$month = $db->fetch($query);
						?>
						<?php echo $month['active_row']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $l->t('This year');?>
					</td>
					<td align="right">
						<?php
						$year_sql = $main_query." WHERE `ct_yyyy`='".date('Y')."';";
						$query = $db->select_query($year_sql);
						$year = $db->fetch($query);
						?>
						<?php echo $year['active_row']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $l->t('All');?>
					</td>
					<td align="right">
						<?php
						$query = $db->select_query($main_query);
						$all = $db->fetch($query);
						?>
						<?php echo $all['active_row']; ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php
$sql = "SELECT a.`ct_no`,a.`ct_ip`,a.`ct_time`,b.`active_time` FROM `".TB_ACTIVEUSER."` AS a
LEFT JOIN (
SELECT `ct_no`, CONCAT(`ct_yyyy`,'-',`ct_mm`,'-',`ct_dd`) AS `active_time` FROM `".TB_ACTIVEUSER."` ORDER BY `ct_no` ASC
) AS b ON b.`ct_no` = a.`ct_no`
WHERE b.`active_time` = DATE(NOW())
AND a.`ct_ip` = '".get_real_ip()."'";
$query = $db->select_query($sql);
$rows = $db->rows($query);
if($rows > 0){
	?>
	<div>
		<div class="online-head">(<?php echo $l->t('Show/hide IP'); ?>)</div>
		<ul class="online-lists" style="display:none;">
		<?php	
		while($item = $db->fetch($query)){
		?>
			<li>
				<div>
					<span><?php echo $item['ct_ip']; ?></span>
					<span class="online-right"><?php echo ThaiTimeConvert($item['ct_time']); ?></span>
				</div>
			</li>
		<?php
		}
		?>
		</ul>
	</div>
	<script type="text/javascript">
	$(function(){
		$('.online-head').click(function(){
			$('.online-lists').slideToggle();
		});
	});
	</script>
	<?php
}