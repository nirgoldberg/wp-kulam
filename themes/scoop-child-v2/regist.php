<?php
/**
 *
 *
 * Template name:Registration
 *
 */


get_header();
$lang = get_locale();
$captcha_instance = new ReallySimpleCaptcha();
$word = $captcha_instance->generate_random_word();
$prefix = mt_rand();

if(is_user_logged_in()) {?>

    <script>window.location.href = "/";</script>
    <?php
}

else{

    if ($lang == 'he_IL') {
        ?>
        <div id="container">
            <form class="registration_form">
                שם משתמש <input type="text" id="uname" name="uname"/>
                אימייל <input id="uemail" type="text" name="uemail"/>
                סיסמא <input type="password" id="upass" name="upass"/>
                <img src="/wp-content/plugins/really-simple-captcha/tmp/<?php echo $captcha_instance->generate_image( $prefix, $word ); ?>"/>
                <br>
                אנא הזן את הטקסט בתיבה מתחת<input id="captcha" type="text" name="captcha"/>
                <input hidden id="prefix" name="prefix" value="<?php echo $prefix ?>"/>
                <input class="submit_reg" type="submit" value="הירשם"/>
                <a href="<?php echo home_url("/login")?>">יש לך כבר חשבון? לכניסה לחץ כאן</a>
            </form>
        </div>
        <?php
    }
    else
    {
        ?>
        <div id="container">
            <form class="registration_form">
                User <input type="text" id="uname" name="uname"/>
                Email <input id="uemail" type="text" name="uemail"/>
                Password <input type="password" id="upass" name="upass"/>
                <img src="/wp-content/plugins/really-simple-captcha/tmp/<?php echo $captcha_instance->generate_image( $prefix, $word ); ?>"/>
                <br>
                Please enter the following text in the box below:<input id="captcha" type="text" name="captcha"/>
                <input hidden id="prefix" name="prefix" value="<?php echo $prefix ?>"/>
                <input hidden id="lang" value="<?php echo $lang ?>"/>
                <input class="submit_reg" type="submit" value="Submit"/>
                <a href="<?php echo home_url("/login")?>">login</a>
            </form>
        </div>
        <?php
    }
    ?>
        <div hidden class="loader">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/loader.gif"/>
    </div>
<?php
}
get_footer();
?>
