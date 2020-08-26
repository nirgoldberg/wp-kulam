<?php
/**
 * Template name:Login
 */

get_header();
$lang = get_locale();
if ($lang == 'he_IL') {
    ?>
    <div id="container">
        <form class="login_form">
            שם משתמש <input type="text" id="unamelog" name="unamelog"/>
            סיסמא <input type="password" id="upasslog" name="upasslog"/>
            <input class="submit_log" type="submit" value="התחבר"/>
            <a href="<?php echo home_url("/register")?>">עוד לא מחובר? להרשמה לחץ כאן!</a>
        </form>
    </div>
    <?php
}
else
{
    ?>
    <div id="container">
        <form class="login_form">
            User <input type="text" id="unamelog" name="unamelog"/>
            Password <input type="password" id="upasslog" name="upasslog"/>
            <input class="submit_log" type="submit" value="Submit"/>
            <input hidden id="lang" value="<?php echo $lang ?>"/>
            <a href="<?php echo home_url("/register")?>">Register Now</a>
        </form>
    </div>
    <?php

}
?>
    <div hidden class="loader">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/loader.gif"/>
    </div>
<?php
get_footer();