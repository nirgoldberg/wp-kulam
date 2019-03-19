<?php
/**
 * Created by PhpStorm.
 * User: Itay Banner
 * Date: 31/01/2018
 * Time: 17:39
 */

// if (!defined('ABSPATH')) exit; // Exit if accessed directly

// //$children = get_term_children(get_queried_object()->term_id, 'category');
// $children = get_terms(
//     'category',
//     array(
//         'parent' => get_queried_object_id(),
//     )
// );
 ?>

 <!-- <nav class="subcat-menu" style="float:none">
     <div class="row subcat-menu"> -->
    <?php

//     foreach ($children as $child) {
//         $child_obj = get_category($child);
//         $link = get_category_link($child_obj->term_id);
//         if (isset($_GET['pt'])) {
//             $link .= '?pt=' . $_GET['pt'];
//         }
//         ?> 
         <!-- <a href="<?php //echo $link; ?>"  role="button" > -->
        <?php //$nameImage=$child_obj->name;
//         $nameImage=strtok($nameImage, " ");
//         if(substr($nameImage, -1)==":")
//          $nameImage=rtrim($nameImage,":");
//          ?>
         <!-- <div class="tile-box-wrapper col-md-3 subcat" style="background-image: url(http://kulam-qa.cmbm.co.il/wp-content/themes/scoop-child/assets/images/<?php echo $nameImage?>.jpg);
        background-position: center;
         background-size: cover;
         background-repeat: no-repeat;
         height: 30vh;
         margin-bottom: 10px;
         width: 13vw;
         "> -->
               <!-- <h4 ><?php //echo $child_obj->name; ?></h4> -->
           
        <!-- </div>
      </a> -->
     <?php //} ?>
    <!-- </div>
 </nav> -->

<?php
/**
 * Created by PhpStorm.
 * User: Itay Banner
 * Date: 31/01/2018
 * Time: 17:39
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

//$children = get_term_children(get_queried_object()->term_id, 'category');
$children = get_terms(
    'category',
    array(
        'parent' => get_queried_object_id(),
        'hide_empty' => false,
        'include' => 'all'
    )
);
?>

<nav class="subcat-menu" style="float:none">
    <div class="row">
    <?php

    foreach ($children as $child) {
        $child_obj = get_category($child);
        $link = get_category_link($child_obj->term_id);
        if (isset($_GET['pt'])) {
            $link .= '?pt=' . $_GET['pt'];
        }
        ?>
		<div class="tile-box-wrapper-child-cat col-md-3">
            <a href="<?php echo $link; ?>" class="tile-box-link" role="button" style="background: rgba(7,21,147,0.45);padding-top: 71%;height: 280px;"><h2 style="text-align:center; position:relative; top:-80px"><?php echo $child_obj->name; ?></h2></a></div>
    <?php } ?>
    </div>
</nav> 