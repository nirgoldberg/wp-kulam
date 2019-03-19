<?php

namespace threewp_broadcast\premium_pack\user_access_manager;

/**
	@brief			Adds support for the <a href="https://wordpress.org/plugins/user-access-manager/">User Access Manager</a> plugin.
	@plugin_group	3rd party compatability
	@since			2018-08-07 10:19:00
**/
class User_Access_Manager
	extends \threewp_broadcast\premium_pack\base
{
	use \threewp_broadcast\premium_pack\classes\database_trait;

	public function _construct()
	{
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_broadcasting_started' );
	}

	/**
		@brief		threewp_broadcast_broadcasting_before_restore_current_blog
		@since		2018-08-08 22:09:20
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		$bcd = $action->broadcasting_data;		// Convenience

		if ( ! isset( $bcd->user_access_manager ) )
			return;

		global $wpdb;
		$uam = $bcd->user_access_manager;

		// Sanity checking.
		$table = sprintf( "%suam_accessgroup_to_object", $wpdb->prefix );
		$this->database_table_must_exist( $table );

		// Sync the groups, if any.
		$groups = [];
		$table = 'uam_accessgroups';
		foreach( $uam->collection( $table ) as $group )
		{
			// Find the equivalent group, if any.
			$query = sprintf( "SELECT * FROM `%s%s` WHERE `groupname` = '%s'",
				$wpdb->prefix,
				$table,
				$group->groupname
			);
			$row = $wpdb->get_row( $query );
			if ( ! $row )
			{
				$data = (array) $group;
				unset( $data[ 'ID' ] );
				$new_group_id = $wpdb->insert( $wpdb->prefix . $table, $data );
			}
			else
			{
				$new_group_id = $row->ID;
			}
			$groups[ $group->ID ] = $new_group_id;
			$this->debug( 'New group ID for %s is %s', $group->groupname, $new_group_id );
		}

		$this->debug( 'Group IDs are: %s', $groups );

		$table = 'uam_accessgroup_to_object';
		// Delete existing rows for this post.
		$query = sprintf( "DELETE FROM `%s%s` WHERE `object_id` = '%d'",
			$wpdb->prefix,
			$table,
			$bcd->new_post( 'ID' )
		);
		$this->debug( $query );
		$results = $wpdb->get_results( $query );

		// Insert the new rows.
		foreach( $uam->collection( $table ) as $row )
		{
			// Update the ID.
			$row->object_id = $bcd->new_post( 'ID' );
			if ( $row->group_id > 0 )
				$row->group_id = $groups[ $row->group_id ];
			$this->debug( 'Inserting %s', $row );
			$wpdb->insert( $wpdb->prefix . $table, (array) $row );
		}

		// And update the group contents.

		// Delete old group contents.
		$query = sprintf( "DELETE FROM `%s%s` WHERE `group_id` IN ('%s') AND `object_type` = '_role_'",
			$wpdb->prefix,
			$table,
			implode( "','", $groups )
		);
		$wpdb->get_results( $query );

		foreach( $uam->collection( 'uam_accessgroup_to_object_groups' ) as $old_group )
		{
			$new_group = (array) $old_group;
			$new_group[ 'group_id' ] = $groups[ $new_group[ 'group_id' ] ];

			// For some reason, wpdb insert absolutely refuses to insert the text object_id. So we temporarily insert a number...
			$time = time();
			$new_group[ 'object_id' ] = $time;
			$this->debug( 'Inserting group data %s', $new_group );
			$wpdb->insert( $wpdb->prefix . $table, $new_group );

			// Force a rename of the object id number.
			$query = sprintf( "UPDATE `%s%s` SET `object_id` = '%s' WHERE `object_id` = '%s'",
				$wpdb->prefix,
				$table,
				$old_group->object_id,
				$time
			);
			$this->debug( $query );
			$wpdb->query( $query );
		}
	}

	/**
		@brief		threewp_broadcast_broadcasting_started
		@since		2018-08-07 10:24:18
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		$bcd = $action->broadcasting_data;		// Convenience

		global $wpdb;
		$query = sprintf( "SELECT * FROM `%suam_accessgroup_to_object` WHERE `object_id` = '%d'",
			$wpdb->prefix,
			$bcd->post->ID
		);
		$results = $wpdb->get_results( $query );

		if ( count( $results ) < 1 )
			return;

		// Save the rows.
		$bcd->user_access_manager = ThreeWP_Broadcast()->collection();
		$uam = $bcd->user_access_manager;

		$uam->collection( 'uam_accessgroup_to_object' )->import_array( $results );

		// Fetch any groups.
		$group_ids = [];
		foreach( $results as $row )
		{
			if ( $row->group_id < 1 )
				continue;
			$group_ids []= $row->group_id;
		}

		$group_ids = array_unique( $group_ids );

		if ( count( $group_ids ) > 0 )
		{
			$query = sprintf( "SELECT * FROM `%suam_accessgroup_to_object` WHERE `group_id` IN ('%s') AND `object_type` = '_role_'",
				$wpdb->prefix,
				implode( "','", $group_ids )
			);
			$results = $wpdb->get_results( $query );
			$uam->collection( 'uam_accessgroup_to_object_groups' )->import_array( $results );

			$query = sprintf( "SELECT * FROM `%suam_accessgroups` WHERE `ID` IN ('%s')",
				$wpdb->prefix,
				implode( "','", $group_ids )
			);
			$results = $wpdb->get_results( $query );

			foreach( $results as $result )
				$uam->collection( 'uam_accessgroups' )->set( $result->ID, $result );
		}

		$this->debug( 'User Access Manager data for this post: %s', $uam );
	}
}
