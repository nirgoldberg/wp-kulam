<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><form role="search" method="get" class="form form-search" action="<?php echo home_url( '/' ); ?>">
	<label for="s">
		<span class="sr-only"><?php _e( 'Search for:', 'pojo' ); ?></span>
		<input type="search" title="<?php _e( 'Search', 'pojo' ); ?>" name="s" value="<?php echo ( isset( $_GET['s'] ) ) ? esc_attr( $_GET['s'] ) : ''; ?>" placeholder="<?php _e( 'Search...', 'pojo' ); ?>" class="field search-field">
	</label>
	<button value="<?php _e( 'Search', 'pojo' ); ?>" class="search-submit button" type="submit"><?php _e( 'Search', 'pojo' ); ?></button>
</form>
