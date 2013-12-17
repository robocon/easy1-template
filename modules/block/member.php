<?php defined('AM_EXEC') or die('Restricted Access'); ?>
<style type="text/css">
    #login-form{width: 167px;margin: 0 auto;}
    #login-form .item-field{margin-bottom: 4px;}
    #login-form label{display: block;font-weight: bold;}
    #login-form label[for="loginCaptcha"]{display: inline;}
    #user-logedin p{margin: 0;}
    #user-logedin ul{list-style: none;}
    #user-logedin-name{text-align: center;}
</style>
<?php
// If not loged in yet
if(!isset($_SESSION['login_true']) && !isset($_SESSION['admin_user'])){
    
    ?>
    <script type="text/javascript">
    $(function(){
        $('#login-form').submit(function(){
            if($('#loginUsername').val()==''){
                alert('<?php echo _MEMBER_MOD_CHECK_USER_MESS_NULL;?>');
                return false;
            }else if($('#loginPassword').val()==''){
                alert('<?php echo _MEMBER_MOD_FORM_JAVA_PASS;?>');
                return false;
            }

            return;
        });
    });
    </script>
    <form id="login-form" method="post" action="index.php?name=member&file=login_check">
        <div class="item-field">
            <label for="loginUsername">
                <span><?php echo $l->t('Username')?></span>
            </label>
            <input type="text" id="loginUsername" name="user_login" placeholder="<?php echo $l->t('Username',true)?>"/>
        </div>
        <div class="item-field">
            <label for="loginPassword">
                <span><?php echo $l->t('Password')?></span>
            </label>
            <input type="password" id="loginPassword" name="pwd_login" placeholder="<?php echo $l->t('Password',true)?>"/>
        </div>
        <div class="item-field">
            <label for="loginCaptcha">
                <span><?php echo $l->t('Captcha')?></span>
            </label>
            <img src="capcha/val_img.php?width=60&height=23&characters=4" />
            <input type="text" id="loginCaptcha" name="security_code" placeholder="<?php echo $l->t('Captcha',true)?>"/>
        </div>
        <div class="item-field"><input type="submit" value="<?php echo $l->t('Log In')?>" /></div>
        <div>
            <a href="index.php?name=member&file=index"><?php echo $l->t('Sign Up')?></a>
        </div>
        <div>
            <a href="index.php?name=member&file=forget_pwd"><?php echo $l->t('Forget your password?')?></a>
        </div>
    </form>
    <?php
}else{
    $name = isset($_SESSION['admin_user']) ? $_SESSION['admin_user'] : $_SESSION['login_true'] ;
    
    $query = $db->select_query("SELECT * FROM ".TB_MEMBER." WHERE user='{$name}' ");
    $user = $db->fetch($query);
    
    $thumb = 'icon/'.$user['member_pic'];
    $user_thumb = is_file($thumb) ? $thumb : 'icon/member_nrr.gif' ;
    ?>
    <div id="user-logedin">
        <div id="user-logedin-name">
            <img src="<?php echo $user_thumb; ?>" />
            <p><?php echo $l->t('Hello')?> <?php echo $user['name']; ?></p>
        </div>
        <ul class="menu-lists">
            <?php if(isset($_SESSION['admin_user']) && !isset($_SESSION['login_true'])){ ?>
            <li>
                <a href="index.php?name=admin&file=member_detail"><?php echo _MEMBER_AUTH; ?></a>
            </li>
            <li>
                <a href="index.php?name=blog&file=blogad"><?php echo _MEMBER_BLOG; ?></a>
            </li>
            <li>
                <a href="index.php?name=blog&file=blogad&op=article_add"><?php echo _MEMBER_BLOGADD; ?></a>
            </li>
            <?php } ?>
            <?php if(isset($_SESSION['login_true']) && !isset($_SESSION['admin_user'])){ ?>
            <li>
                <a href="index.php?name=member&file=member_detail"><?php echo _MEMBER_AUTH; ?></a>
            </li>
            <li>
                <a href="index.php?name=blog&file=blog"><?php echo _MEMBER_BLOG; ?></a>
            </li>
            <li>
                <a href="index.php?name=blog&file=blog&op=article_add"><?php echo _MEMBER_BLOGADD; ?></a>
            </li>
            <?php } ?>
            <li>
                <a href="index.php?name=member&file=logout"><?php echo _MEMBER_EXIT; ?></a>
            </li>
        </ul>
    </div>
    <?php
}