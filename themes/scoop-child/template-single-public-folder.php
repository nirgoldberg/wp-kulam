<?php
/**
 * The template for displaying the single Public folder page.
 *
 * Template name:single-public-folder
 *
 */ 
get_header();
?>
<?php
if(isset($_GET['folder'])&&isset($_GET['u'])&&isset($_GET['si']))
  {
      $folder = $_GET['folder'];
      if (preg_match('/_/', $folder)) 
      {
        $folder = str_replace('_', ' ', $folder);
      }
      $user = $_GET['u'];
      $site = $_GET['si'];
      $lang = get_locale();
      global $wpdb;
      $table_name = $wpdb->prefix . 'public_folders';
      $sqlQuery="SELECT * FROM  ". $table_name ." WHERE `folder_name` =  '". $folder ."' AND `id_user` = '".$user."'  AND `id_site` = '".$site ."'AND `lang` = '".$lang ."'";
      $result = $wpdb->get_results($sqlQuery,OBJECT);
      if($result)
      {
        ?>
        <h1><?php echo $folder?></h1>
        <?php
        $data_value = get_user_meta($user,$folder.$site, true);
        $data_value = json_decode($data_value, true);
        if($data_value)
        {
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
    <?php
        }
      }
  }
get_footer();