<?php defined('AM_EXEC') or die('Restricted Access');
DBi::connect();
$query = DBi::select("SELECT * FROM `".TB_POLL."` ORDER BY `id` DESC LIMIT 1");
$poll = $query->fetch_assoc();

$session_id = session_id();

// Check vote yet?
$query = DBi::select("SELECT * FROM `".TB_POLL_VOTES."` WHERE `poll_id`=? AND `ip`=?;", array($poll['id'], $session_id));
$vote = $query->fetch_assoc();
?>
<style type="text/css">
.poll-display ul{list-style: none; margin: 0; padding: 0;}
#poll-notify{display:none;}
.poll-title{font-weight: bold; }
.vote-item,
.vote-percent{width: 100%; display: block; }
.vote-item{text-align: center; }
.vote-percent{text-align: right; }
.vote-line{height: 11px; background-color: #DDD; }
.vote-line span{height: 11px; background: #333; display: block; }
.vote-amount{text-align: center; color: #999;}
</style>
<?php
if(!$vote){
	?>
	<div class="poll-display">
		<p class="poll-title"><?php echo $poll['poll_question']?>:</p>
		<div>
			<form method="post" id="pollForm" class="pure-form" action="index.php?name=ajoxpoll&file=vote">
				<ul>
				<?php
				$choices = explode('|', $poll['poll_options']);
				$i = 0;
				foreach($choices AS $choice){
					if($choice){
						?>
						<li>
							<input type="radio" class="voteid" name="voteid" value="<?php echo $i?>" id="<?php echo $choice; ?>">
							<label for="<?php echo $choice; ?>"><?php echo $choice?></label>
						</li>
						<?php
						$i++;
					}
				}
				?>
				</ul>
				<input type="hidden" name="poll_id" id="poll_id" value="<?php echo $poll['id']?>">
				<button class="pure-button pure-button-primary" id="pollSubmit" value=""><?php echo $l->t('Vote')?></button>
			</form>
			<div id="poll-notify" class="x-message"></div>
		</div>
	</div>
	<script type="text/javascript">
	jQuery.noConflict();
	(function( $ ) {
	$(function() {
		var vote_checked=false;
		$('.voteid').click(function(){
			vote_checked=$(this).val();
		});

		$('#pollSubmit').click(function(){

			var id=$('#poll_id').val();
			if(vote_checked===false){
				$("#poll-notify").text('<?php echo $l->t('Please choose some choice before vote')?>').show();
				return false;
			}
			
			$.ajax({
				type: "POST",
				url: 'modules/ajoxpoll/vote.php',
				data: {poll_id: id, voteid: vote_checked},
				success: function(wdata){
					location.reload();
				}
			});
			return false;
		});
	});
	})(jQuery);
	</script>
	<?php
}else if($vote>0){

	$sql = "SELECT `vote_id`, COUNT(`vote_id`) AS `vote_rows` FROM `".TB_POLL_VOTES."` WHERE `poll_id` = ? GROUP BY `vote_id` ORDER BY `vote_id` ASC;";
	$query = DBi::select($sql, array($poll['id']));
	$newItems = array();
	$amount = 0;
	while($item = $query->fetch_assoc()){
		$newItems[$item['vote_id']] = $item['vote_rows'];
		$amount += (int) $item['vote_rows'];
	}
	?>
	<div class="poll-display">
		<p class="poll-title"><?php echo $poll['poll_question']?>:</p>
		<ul>
			<?php
			$choices = explode('|', substr($poll['poll_options'], 0,-1));
			foreach ($choices as $key => $value) {
				$vote_val = (int)$newItems[$key];
				$vote_percent = round((($vote_val*100)/$amount),0);
				?>
				<li>
					<div>
						<span class="vote-item"><?php echo $value; ?></span>
						<div class="vote-line"><span style="width: <?php echo $vote_percent?>px;"></span></div>
						<span class="vote-percent"><?php echo $vote_percent?>%</span>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
		<div class="vote-amount">
			<?php echo $l->t('Total votes') ?>: <?php echo $amount;?>
		</div>
	</div>
	<?php
}
