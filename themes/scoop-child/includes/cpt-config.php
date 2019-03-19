<?php

/*-------------------------------------------------------------------------------
	Register "Post Types" Taxonomy
-------------------------------------------------------------------------------*/

add_action( 'init', 'kol_register_post_types_tax' );
function kol_register_post_types_tax()
{
    $labels = array(
        "name" => __('Post Types', 'kulam-scoop'),
        "singular_name" => __('Post Type', 'kulam-scoop'),
        "menu_name" => __( 'Post Types', 'kulam-scoop' ),
        "all_items" => __( 'All Post Types', 'kulam-scoop' ),
        "edit_item" => __( 'Edit Post Type', 'kulam-scoop' ),
        "view_item" => __( 'View Post Type', 'kulam-scoop' ),
        "update_item" => __( 'Update Post Type', 'kulam-scoop' ),
        "add_new_item" => __( 'Add New Post Type', 'kulam-scoop' ),
        "new_item_name" => __( 'New Post Type Name', 'kulam-scoop' ),
    );

    $args = array(
        "label" => __('Post Types', 'kulam-scoop'),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => "pt",
        "rewrite" => array('slug' => 'pt', 'with_front' => false,),
        "show_admin_column" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => true,
        "capabilities"  => array(
            "manage_terms"  => "manage_options",
            "edit_terms"    => "manage_options",
            "delete_terms"  => "manage_options"
        ),
    );
    register_taxonomy("post_types_tax", array("post") , $args);
}


/*-------------------------------------------------------------------------------
	Register "My Siddur" Taxonomy
-------------------------------------------------------------------------------*/

add_action( 'init', 'kol_register_siddurim' );
function kol_register_siddurim()
{
    $labels = array(
        "name" => __('Siddurim', 'kulam-scoop'),
        "singular_name" => __('My Siddur', 'kulam-scoop')
    );

    $args = array(
        "label" => __('Siddurim', 'kulam-scoop'),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => true,
        "show_ui" => false,
        "show_in_menu" => false,
        "show_in_nav_menus" => false,
        "capabilities"  => array(
            "manage_terms"  => "manage_siddurim",
            "edit_terms"    => "manage_siddurim",
            "delete_terms"  => "manage_siddurim"
        ),
        "query_var" => "siddur",
        "rewrite" => array('slug' => 'siddur', 'with_front' => false,),
        "show_admin_column" => false,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => false,
    );
    register_taxonomy("siddurim", array("post"), $args);
}
