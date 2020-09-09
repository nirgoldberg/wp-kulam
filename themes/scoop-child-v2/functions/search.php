<?php
/**
 * Search functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     2.0.2
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_search_get_post_formats_field
 *
 * This function generates and returns the post formats form field
 *
 * @param	N/A
 * @return	(string)
 */
function kulam_search_get_post_formats_field() {

	/**
	 * Variables
	 */
	$formats	= kulam_search_get_post_formats();
	$output		= '';

	if ( $formats ) {

		$output .= '<span class="menu-search-input menu-search-input-post-format">' .
			'<div class="list-title">' . __( 'Post Format', 'kulam-scoop' ) . '</div>' .
			'<div class="checkbox-list-holder">' .
				'<ul class="checkbox-list">';

					$output .= sprintf( '<li data-id="%1$s"><label><input type="checkbox" name="%2$s[]" value="%1$s" %3$s> <span>%4$s</span></label></li>',
						'0',
						'filters[post_format]',
						isset( $_GET[ 'filters' ][ 'post_format' ] ) && is_array( $_GET[ 'filters' ][ 'post_format' ] ) ? checked( in_array( '0', $_GET[ 'filters' ][ 'post_format' ], true ), 1, false ) : '',
						__( 'Text', 'kulam-scoop' )
					);

					foreach ( $formats as $f ) {

						$output .= sprintf( '<li data-id="%1$s"><label><input type="checkbox" name="%2$s[]" value="%1$s" %3$s> <span>%4$s</span></label></li>',
							$f,
							'filters[post_format]',
							isset( $_GET[ 'filters' ][ 'post_format' ] ) && is_array( $_GET[ 'filters' ][ 'post_format' ] ) ? checked( in_array( $f, $_GET[ 'filters' ][ 'post_format' ] ), 1, false ) : '',
							esc_html( get_post_format_string( $f ) )
						);

					}

				$output .= '</ul>' .
			'</div>' .
		'</span>';

	}

	// return
	return $output;

}

/**
 * kulam_search_get_post_formats
 *
 * This function returns an array of supported post formats
 *
 * @param	N/A
 * @return	(array)
 */
function kulam_search_get_post_formats() {

	/**
	 * Variables
	 */
	$formats = array();

	if ( current_theme_supports( 'post-formats' ) ) {

		$post_formats = get_theme_support( 'post-formats' );

		if ( is_array( $post_formats[0] ) ) {
			$formats = $post_formats[0];
		}

	}

	// return
	return $formats;

}

/**
 * kulam_search_get_category_terms_field
 *
 * This function generates and returns the category terms form field
 *
 * @param	N/A
 * @return	(string)
 */
function kulam_search_get_category_terms_field() {

	if ( ! function_exists( 'get_field' ) )
		return '';

	/**
	 * Variables
	 */
	$terms						= kulam_search_get_category_terms();
	$parents					= array();		// stack of parents (LIFO) in order to manage list structure
	$last_term					= 0;			// last term ID added to the tree structure
	$categories_field_label		= get_field( 'acf-option_advanced_search_categories_label', 'option' );
	$categories_field_label		= $categories_field_label ?: __( 'Category', 'kulam-scoop' );
	$output						= '';

	if ( $terms ) {

		$output .= '<span class="menu-search-input menu-search-input-category">' .
			'<div class="list-title">' . $categories_field_label . '</div>' .
			'<div class="checkbox-list-holder">' .
				'<ul class="checkbox-list">';

					foreach ( $terms as $term ) {

						$ancestors	= get_ancestors( $term->term_id, 'category', 'taxonomy' );
						$parent		= false;	// is parent found

						// check $ancestors against $last_term
						if ( $last_term ) {
							if ( in_array( $last_term, $ancestors ) ) {

								$parent = true;

								// add $last_term to $parents stack
								array_unshift( $parents, $last_term );

								// open a new sub list
								$output .= '<ul class="children">';

							}
						}

						// check $ancestors against $parents
						if ( ! $parent && ! empty( $parents ) ) {

							$depth = 0;	// parents depth in which parent was found

							foreach ( $parents as $p ) {
								if ( in_array( $p, $ancestors ) )
									break;
								else
									$depth++;
							}

							// shift parents as many as depth
							for( $i=0 ; $i<$depth ; $i++ ) {

								// pull out last parent from $parents
								array_shift( $parents );

								// close last sub list
								$output .= '</ul></li>';

							}

						}

						if ( ! $parent && ! $depth ) {

							// close last term
							$output .= '</li>';

						}

						$last_term = $term->term_id;

						$output .= sprintf( '<li data-id="%1$s"><label><input type="checkbox" name="%2$s[]" value="%1$s" %3$s> <span>%4$s</span></label>',
							$term->slug,
							'filters[category]',
							isset( $_GET[ 'filters' ][ 'category' ] ) && is_array( $_GET[ 'filters' ][ 'category' ] ) ? checked( in_array( $term->slug, $_GET[ 'filters' ][ 'category' ] ), 1, false ) : '',
							$term->name
						);

					}

				$output .= '</li></ul>' .
			'</div>' .
		'</span>';

	}

	// return
	return $output;

}

/**
 * kulam_search_get_category_terms
 *
 * This function returns an array of chosen category terms for advanced search form
 *
 * @param	N/A
 * @return	(array)
 */
function kulam_search_get_category_terms() {

	if ( ! function_exists( 'get_field' ) )
		return '';

	/**
	 * Variables
	 */
	$categories	= get_field( 'acf-option_advanced_search_categories', 'option' );
	$terms 		= array();

	if ( ! $categories )
		return $terms;

	foreach ( $categories as $term ) {

		/**
		 * Uncomment this in order to check terms based on their direct number of terms

		if ( $term->count <= 1 )
			continue;

		*/

		$terms[] = $term;

	}

	// return
	return $terms;

}

/**
 * kulam_search_get_taxonomies_fields
 *
 * This function generates and returns the taxonomies terms form fields
 *
 * @param	N/A
 * @return	(string)
 */
function kulam_search_get_taxonomies_fields() {

	/**
	 * Variables
	 */
	$tt			= kulam_search_get_taxonomies();
	$output		= '';

	if ( $tt ) {
		foreach ( $tt as $tax_name => $terms ) {

			$tax = get_taxonomy( $tax_name );

			if ( ! $tax )
				continue;

			$output .= '<span class="menu-search-input menu-search-input-' . $tax->name . '">' .
				'<div class="list-title">' . $tax->labels->singular_name . '</div>' .
				'<div class="checkbox-list-holder">' .
					'<ul class="checkbox-list">';

						foreach ( $terms as $term ) {

							$output .= sprintf( '<li data-id="%1$s"><label><input type="checkbox" name="%2$s[]" value="%1$s" %3$s> <span>%4$s</span></label></li>',
								$term->slug,
								'filters[' . $tax->name . ']',
								isset( $_GET[ 'filters' ][ $tax->name ] ) && is_array( $_GET[ 'filters' ][ $tax->name ] ) ? checked( in_array( $term->slug, $_GET[ 'filters' ][ $tax->name ] ), 1, false ) : '',
								$term->name
							);

						}

					$output .= '</ul>' .
				'</div>' .
			'</span>';

		}
	}

	// return
	return $output;

}

/**
 * kulam_search_get_taxonomies
 *
 * This function returns an array of chosen taxonomies for advanced search form
 * Each array element contains an array of taxonomy terms
 *
 * @param	N/A
 * @return	(array)
 */
function kulam_search_get_taxonomies() {

	if ( ! function_exists( 'get_field' ) )
		return '';

	/*1
	 * Variables
	 */
	$taxonomies	= get_field( 'acf-option_advanced_search_filters', 'option' );
	$tt 		= array();

	if ( ! $taxonomies )
		return $tt;

	foreach ( $taxonomies as $tax ) {

		$terms_args = array(
			'taxonomy'	=> $tax->name,
		);
		$terms = get_terms( $terms_args );

		if ( $terms ) {

			foreach ( $terms as $key => $term ) {
				if ( $term->count <= 1 ) {
					unset( $terms[ $key ] );
				}
			}

			if ( $terms ) {
				$tt[ $tax->name ] = $terms;
			}

		}

	}

	// return
	return $tt;

}

/**
 * kulam_advanced_search_pre_get_posts
 *
 * @param   $query (obj)
 * @return  N/A
 */
function kulam_advanced_search_pre_get_posts( $query ) {

	// exit if is admin or current query is not the main query
	if ( is_admin() || ! $query->is_main_query() )
		return;

	if ( ! $query->is_search || ! isset( $_GET[ 'filters' ] ) ) {
		return;
	}

	/**
	 * Variables
	 */
	$tax_query = array();

	foreach ( $_GET[ 'filters' ] as $key => $value ) {

		if ( 'post_format' == $key ) {

			// post_format taxonomy
			// build an inner tax_query array element holding an OR relationship in order to support standard post format
			$inner_tax_query = array();

			foreach ( $value as $k => $v ) {
				if ( '0' !== $v ) {

					// extend $value with additional slug represents same format (post-format-)
					$value[] = 'post-format-' . $v;

				}
				else {

					// standard post format
					$inner_tax_query[] = array(
						'taxonomy'	=> $key,
						'operator'	=> 'NOT EXISTS',
					);

					unset( $value[ $k ] );

				}
			}

			if ( count( $value ) ) {

				// other post formats
				$inner_tax_query[] = array(
					'taxonomy'	=> $key,
					'field'		=> 'slug',
					'terms'		=> $value,
				);

			}

			if ( count( $inner_tax_query ) > 1 ) {
				$inner_tax_query[ 'relation' ] = 'OR';
			}

			$tax_query[] = $inner_tax_query;

		}
		else {

			// other taxonomies
			$tax_query[] = array(
				'taxonomy'	=> $key,
				'field'		=> 'slug',
				'terms'		=> $value,
			);

		}

	}

	if ( count( $tax_query ) > 1 ) {
		$tax_query[ 'relation' ] = 'AND';
	}

	if ( count( $tax_query ) > 0 ) {
		$query->set( 'tax_query', $tax_query );
	}

}
add_action( 'pre_get_posts', 'kulam_advanced_search_pre_get_posts' );