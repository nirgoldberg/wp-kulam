<?php
/**
 * The template for displaying the ProductsBTB page.
 *
 * Template name:siddur-folder 
 *
 */ 
get_header();
$lang=get_locale();
?>
  <?php if ($lang=="en_US"):?>
<div id="popup-to" hidden>
<form class="sendEmailpopup" > 
  <div class="close-popup-to" >+</div> 
  Please type or paste email addresses with a space between each one:
    <input type="email"   pattern="[^ @]*@[^ @]*" required class="to" id="idto"/>
    <input type="button" value="send" id="sendTo"/>
</form>
</div>
<?php endif;?>
<?php if ($lang=="he_IL"):?>
<div id="popup-to" hidden>
<form class="sendEmailpopup" > 
  <div class="close-popup-to" >+</div> 
הקלד או הדבק את כתובות המייל לשיתוף עם רווח בין כתובת אחת לשניה:
    <input type="email"   pattern="[^ @]*@[^ @]*" required class="to" id="idto"/>
    <input type="button" value="שלח" id="sendTo"/>
</form>
</div>
<?php endif;?>
<?php if ($lang=="en_US"):?>

<div class="share-popup">
    <div class="share-popup-inner">
        <div class="close-share-popup" >+</div> 
        <h3>In order to share this folder you must set it public</h3>
        <form>
          <p>Note! When you share this folder, all content in it becomes public and anyone can see it on the Web. Do you confirm?</p>
            <div class="choosing-items">
               <label>yes</label><input type="radio" name="public-folder" class="conf" value="public-folder" /><br>
               <label>no </label><input type="radio" name="public-folder" class="conf" value="cancel" /><br>
            </div>
            <input type="button" value="done" class="save-sharing-choosing"/>
        </form>
    </div>
</div>
<?php endif;?>
<?php if ($lang=="he_IL"):?>
<div class="share-popup">
    <div class="share-popup-inner">
        <div class="close-share-popup" >+</div> 
        <h3>על מנת לשתף תיקייה הזו עליך להגדיר אותה ציבורית</h3>
        <form>
        <p>שים לב! כאשר תשתף תיקייה זו , כל התכנים בה יהפכו לציבוריים וכל אחד יוכל לראותם ברשת. האם אתה מאשר?‎</p>
            <div class="choosing-items">
               <label>כן </label><input type="radio" name="public-folder" class="conf" value="public-folder" /><br>
               <label>לא </label><input type="radio" name="public-folder" class="conf" value="cancel" /><br>
            </div>
            <input type="button" value="בצע" class="save-sharing-choosing" />
        </form>
    </div>
</div>
<?php endif;?>
<?php if ($lang=="en_US"):?>
<div class="popup-new-folder" id="popup-settings">
<form class="popup-form-setting">
<input type="text" value="<?php echo $_GET['folder'];?>"  id="name-folder-hide"/>
    <div class="close-popup-settings" >+</div>
    <div class="form-body">
        <h3>Name Folder:</h3>
        <span>*Here you can rename the folder</span>
        <input type="text" value="<?php echo $_GET['folder'];?>" id="name-new-folder"/>
        <div id="wrap-checkbox">
            <input type="checkbox" id="del" />
            <label class="labal-del" for="del">Delete this folder?</label>
        </div>

        <input type="checkbox" id="is-public" name="is-public" value="is-public">
        <label for="is-public"> Public folder?</label>
        <input type="button" class="save-settings" value="save settings"/>
    </div>
    </form>

</div>
<?php endif;?>
<?php if ($lang=="he_IL"):?>
<div class="popup-new-folder" id="popup-settings">
<form class="popup-form-setting">
<input type="text" value="<?php echo $_GET['folder'];?>"  id="name-folder-hide"/>

    <div class="close-popup-settings" >+</div>
    <div class="form-body">
        <h3>שם התיקייה:</h3>
        <span>*כאן ניתן לשנות את שם התיקייה</span>
        <input type="text" value="<?php echo $_GET['folder'];?>" id="name-new-folder"/>
        <div id="wrap-checkbox">
            <input type="checkbox" id="del" />
            <label class="labal-del" for="del">למחוק את התקיה הזו?</label>
        </div>

        <input type="checkbox" id="is-public" name="is-public" value="is-public">
        <label for="is-public"> תיקייה ציבורית?</label>
        <input type="button" class="save-settings" value="שמור הגדרות"/>
    </div>
    </form>

</div>
<?php endif;?>
<?php if ($lang=="he_IL"):?>
<div class="popup-new-folder" id="popup-link-copy">
  <form class="popup-form-setting">
  <div class="close-popup-link" >+</div>
    <div class="form-body">
        <h4>לינק לשיתוף</h4>
        <input type="text" id="link_to_copy">
    </div>
  </form>
</div>
<?php endif;?>
<?php if ($lang=="en_US"):?>
<div class="popup-new-folder" id="popup-link-copy">
  <form class="popup-form-setting">
  <div class="close-popup-link" >+</div>
    <div class="form-body">
        <h4>Link to share</h4>
        <input type="text" id="link_to_copy">
    </div>
  </form>
</div>
<?php endif;?>

  <div hidden class="loader">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/loader.gif"/>
    </div>
<?php

if ( is_user_logged_in() ) {
    $site = get_current_blog_id();
    $user = wp_get_current_user();
    $folder=$_GET['folder'];
    $data_value = get_user_meta($user->ID,$folder.$site, true);
    $data_value = json_decode($data_value, true);
    $lang=get_locale();
    ?>
    
<div class="folder-wrap">

    <h1 class="entry-title"><?php echo $folder?></h1>
    <div class="settings"><i class="fa fa-cog" aria-hidden="true"></i></div>
    <?php if($lang=="he_IL"){?>
    <i class="fa fa-arrow-circle-o-left my-siddur" aria-hidden="true"></i>
<?php }
else{
    ?>
    <i class="fa fa-arrow-circle-o-right my-siddur" aria-hidden="true"></i>
    <?php
}
if($data_value)
{
   ?> 
    <div class="shere-section">
       
        <a class="entry-mail pojo-tooltip" id="send">
            <span class="fa fa-envelope-o "></span>
        </a>
        <a class="entry-facebook pojo-tooltip" id="facebook-share"   target="_blank">
            <span class="fa fa-facebook"></span>
        </a>
      
        <a class="entry-twitter pojo-tooltip" id="twitter-share"  target="_blank">
        <span class="fa fa-twitter"></span>
        </a>
        
        <a class="entry-whatsaap pojo-tooltip " id="whatsapp-share">
            <span class="fa fa-whatsapp"></span>
        </a>

        <a class="entry-telegram pojo-tooltip " id="telegram-share">
            <span class="fa fa-telegram"></span>
        </a>

        <a class="entry-clipboard pojo-tooltip " id="clipboard-share" >
            <span class="fa fa-clipboard"></span>
        </a>
    </div>

    <?php
$args = array(
    'post_type' => 'post',
    'post__in' => $data_value
);
$the_query = new WP_Query($args);
    ?>
            <div id="primary">
             <div id="content" role="main">
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>

                    <?php get_template_part('content/content', 'grid_three'); ?>
                <?php endwhile; // end of the loop.
                ?>
            </div><!-- #content -->
        </div><!-- #primary -->
</div>

    
      <?php

}
?>

<?php
}
else { ?>
    <script> window.location.href = "/login";</script>
<?php
}
get_footer();