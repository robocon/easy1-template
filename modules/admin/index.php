<?php defined('AM_EXEC') or die('Restricted Access');

if($admin_user){
	header('Location:index.php?name=admin&file=main');
	exit(0);
}else{
	?>
	<style type="text/css">
		#admin-login{width: 290px; margin: 0 auto; border: 1px solid #DDD; padding: 11px; box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.13); color: #777; }
		.admin-item{margin-bottom: 7px; position: relative; }
		.admin-item label{display: block; font-weight: bold; }
		.admin-item input[type="text"],
		.admin-item input[type="password"]{width: 100%; padding: 7px 3px; font-size: 18px; border: 1px solid #DDD; box-shadow: inset 0px 1px 2px rgba(0, 0, 0, 0.07); }
		.item-button{text-align: right; }
		#login-button{padding: 7px 15px; font-size: 17px; background-color: #3C8DC5; color: #FFF; border: none; }
		#forgotpass{position: absolute; top: 0; right: 0; font-weight: bold; }
	</style>
	<div id="admin-login">
		<h1><?php echo $l->t('Log In');?></h1>
		<form name="login" id="login" method="post" action="index.php?name=admin&file=login">
			<div class="admin-item">
				<label for="username">
					<?php echo $l->t('Username');?>
				</label>
				<input type="text" name="username" id="username" />
			</div>
			<div class="admin-item">
				<label for="password">
					<?php echo $l->t('Password');?>
				</label>
				<span id="forgotpass"><a href="#"><?php echo $l->t('For got your password?');?></a></span>
				<input type="password" name="password" id="password" />
			</div>
			<div class="admin-item">
			<?php
			if(USE_CAPCHA){
				?>
				<label for="security_code">
					<?php echo $l->t('Captcha');?>
					<img src="capcha/val_img.php?width=60&height=25&characters=4" width="60" height="25">
				</label>
				<input name="security_code" type="text" id="security_code" >
				<?php
			}
			?>
			</div>
			<div class="admin-item item-button">
				<input name="button" type="submit" class="button" id="login-button" value="<?php echo $l->t('Log In');?>" />
			</div>
			<input type="hidden" name="action" id="action" value="login"> 
		</form>
	</div>
	<script type="text/javascript">
    $(function(){
        $('#login').submit(function(){
            if($('#username').val()==''){
                alert('<?php echo $l->t('Please insert your username'); ?>');
                return false;
            }else if($('#password').val()==''){
                alert('<?php echo $l->t('Please insert your password'); ?>');
                return false;
            }

            return;
        });
    });
    </script>
	<?php
}
