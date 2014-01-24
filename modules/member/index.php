<?php
defined('AM_EXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery.noConflict();
(function( $ ) {
$(function() {
	$("#formSubmit").click(function(){
		$(".i-spin").fadeIn();
		$.ajax({
			type: "post",
			url: "<?= ROOT_TEMP?>/index.php?name=member&file=checkmember",
			data: $("#regisForm").serialize()+'&ajax=1',
			dataType: "json",
			success: function(res){
				if(res.error==true){
					$(".x-message").show().text(res.message);
				}else{
					$(".x-message").hide();
					$("#token").val(res.token);
					$("#regisForm").submit();
				}
				$(".i-spin").fadeOut();
			}
		});	
		return false;
	});
});
})(jQuery);
</script>
<div>
	<h1><?php echo $l->t("Sign up"); ?></h1>
	<div class="modules-sub-title"></div>
	<form method="post" id="regisForm" name="regisForm" action="index.php?name=member&file=member_edit&action=add" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="username"><?=$l->t("Username");?></label>
			<input type="text" name="username" id="username" class="pure-input-1-4" placeholder="Username">
		</div>
		<div class="pure-control-group">
			<label for="password"><?=$l->t("Password");?></label>
			<input type="password" name="password" id="password" class="pure-input-1-4" placeholder="Password">
		</div>
		<div class="pure-control-group">
			<label for="email"><?=$l->t("Email");?></label>
			<input type="email" name="email" id="email" class="pure-input-1-4" placeholder="Email">
		</div>
		<div class="pure-control-group">
			<label for="first_name"><?=$l->t("First name");?></label>
			<input type="text" name="first_name" id="first_name" class="pure-input-1-4" placeholder="First name">
		</div>
		<div class="pure-control-group">
			<label for="last_name"><?=$l->t("Last name");?></label>
			<input type="text" name="last_name" id="last_name" class="pure-input-1-4" placeholder="Last name">
		</div>
		<div class="pure-control-group">
			<label><?=$l->t("Birthday");?></label>
			<div class="pure-u-1-12">
				<select name="day">
					<option value=""><?=$l->t("Day");?></option>
					<?php for($i=1; $i<=31; $i++){ ?>
					<option value="<?=$i?>"><?=$i?></option>
					<?php } ?>
				</select>
			</div>
			<div class="pure-u-1-8">
				<?php $months = AM_Date::get_months(); ?>
				<select name="month">
					<option value=""><?=$l->t("Month");?></option>
					<?php foreach($months AS $num => $name){ ?>
					<option value="<?=$num?>"><?=$l->t($name)?></option>
					<?php } ?>
				</select>
			</div>
			<div class="pure-u-1-8">
				<?php
				$years = date('Y');
				$min_year = $years-90;
				?>
				<select name="year">
					<option value=""><?=$l->t("Year");?></option>
					<?php for($years; $years>=$min_year; $years--){ ?>
					<option value="<?=$years?>"><?=$years?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="pure-control-group">
			<label>&nbsp;</label>
			<button id="formSubmit" class="pure-button pure-button-primary"><?=$l->t("Sign up");?></button><span class="i-spin"><i class="fa fa-refresh fa-spin fa-lg"></i></span>
			<input type="hidden" name="token" id="token" value="">
		</div>
		<div class="pure-control-group">
			<label>&nbsp;</label>
			<div class="x-message" style="display:none;"></div>
		</div>
	</form>

</div>
