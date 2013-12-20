<?php defined('AM_EXEC') or die('Restricted Access'); ?>
<style type="text/css">
.calendar td {height: 26px; width: 29px!important; }
.calendarHeader{font-size: 13px; font-weight: bold; }
.calendarToday {border-bottom: 1px solid #999; background-color: #3C8DC5; font-weight: bold; color: #FFF; }
</style>
<table>
	<tr>
		<td border="0" align="center">
		<?php
		$cal = new MyCalendar();
		echo $cal->getmonthView(date('m'),date('Y') );
		?>
		</td>
	</tr>
</table>
<?php
$sql = "SELECT * FROM `web_calendar` WHERE `date_event` >= DATE(NOW()) ORDER BY `date_event` ASC";
$query = $db->select_query($sql);
$rows = $db->rows($query);

if($rows > 0){
?>
<style type="text/css">
#event-lists{list-style: none; padding: 0px; margin: 0px; }
#event-lists li{border-bottom: 1px solid #ECECEC; margin-bottom: 7px; }
#event-lists a{font-size: 17px; font-weight: bold; }
#event-lists span{font-size: 11px; color: #999; display: block; }
</style>
<div>
	<h3><?php echo $l->t('Latest event'); ?></h3>
	<div class="modules-sub-title"></div>
	<ul id="event-lists">
	<?php
	while ($event = $db->fetch($query)) {
		?>
		<li>
			<a href="index.php?name=calendar&file=view&dates=<?php echo $event['date_event']?>" target="_blank"><?php echo $event['subject']; ?></a>
			<span><?php echo $l->t('Date');?> <?php echo $event['date_event']; ?>&nbsp;&middot;&nbsp;<?php echo $event['pageview']; ?> <?php echo $l->t('Views');?></span>
		</li>
		<?php
	}
	?>
	</ul>
</div>
<?php
}