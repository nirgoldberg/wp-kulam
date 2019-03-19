<?php

namespace threewp_broadcast\premium_pack\learndash;

/**
	@brief			Adds support for the <a href="https://www.learndash.com/">LearnDash LMS</a> plugin.
	@plugin_group	3rd party compatability
	@since			2017-02-26 15:44:07
**/
class LearnDash
	extends \threewp_broadcast\premium_pack\base
{
	/**
		@brief		The Quiz handling class.
		@since		2017-06-29 11:22:44
	**/
	public $quiz;

	public function _construct()
	{
		$this->add_action( 'threewp_broadcast_broadcasting_after_update_post' );
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_broadcasting_started' );
		$this->add_action( 'threewp_broadcast_get_post_types' );
		$this->add_action( 'threewp_broadcast_menu' );
		$this->quiz = new Quiz();
	}

	/**
		@brief		Activate the plugin.
		@since		2017-10-01 18:46:51
	**/
	public function activate()
	{
		// Check that Broadcast's save post is set _after_ LearnDash, which is at 2000.
		$bc = ThreeWP_Broadcast();
		$key = 'save_post_priority';
		$ld_prio = 2000;
		$prio = $bc->get_site_option( $key, 10 );
		if ( $prio > $ld_prio )
			return;
		$prio = $ld_prio * 2;
		$bc->update_site_option( $key, $prio );
	}

	/**
		@brief		admin_questions
		@since		2017-10-01 18:48:19
	**/
	public function admin_questions()
	{
		$form = $this->form2();
		$form->css_class( 'plainview_form_auto_tabs' );
		$r = '';

		// These are the columns in the questions table we process.
		$columns = [
			'title' => 'Question title',
			'question' => 'Question text',
			'correct_msg' => 'Correct answer message',
			'incorrect_msg' => 'Incorrect answer message',
			'answer_data' => 'The answers',
		];

		$column_options = [];
		foreach( $columns as $column_id => $column_label )
				$column_options [ $column_id ] = $column_label;

		$fs = $form->fieldset( 'fs_text_to_replace' )
			// Fieldset label
			->label( __( 'Text to replace', 'threewp_broadcast' ) );

		$text_to_replace = $fs->text( 'text_to_replace' )
			// Input description
			->description( __( 'This is the text you want replaced in the questions table', 'threewp_broadcast' ) )
			// Input label
			->label( __( 'Text to replace', 'threewp_broadcast' ) )
			->placeholder( __( 'Old text', 'threewp_broadcast' ) )
			->required()
			->size( 64 );

		$columns_to_process = $fs->select( 'columns_to_replace' )
			->description( __( 'Select the database table columns in which you wish to search for the text to replace.', 'threewp_broadcast' ) )
			->label( __( 'Columns to process', 'threewp_broadcast' ) )
			->multiple()
			->options( array_flip( $column_options ) )
			->autosize()
			->required();

		$replacement_text = $fs->text( 'replacement_text' )
			// Input description
			->description( __( 'This is the text that will replace the above text wherever it is found', 'threewp_broadcast' ) )
			// Input label
			->label( __( 'Replacement text', 'threewp_broadcast' ) )
			->placeholder( __( 'New text', 'threewp_broadcast' ) )
			->size( 64 );

		$fs = $form->fieldset( 'fs_selection' )
			// Fieldset label
			->label( __( 'Question selection', 'threewp_broadcast' ) );

		$fs->markup( 'm_selection' )
			->p( __( 'Do you want only some questions replaced? Fill in the fields below.', 'threewp_broadcast' ) );

		$selection_text = $fs->text( 'selection_text' )
			// Input description
			->description( __( 'This text, if specified, must exist somewhere in the question data for the question to be processed.', 'threewp_broadcast' ) )
			// Input label
			->label( __( 'Selection text', 'threewp_broadcast' ) )
			->size( 64 );

		$columns_to_search = $fs->select( 'columns_to_search' )
			->description( __( 'Which database columns should contain the above text.', 'threewp_broadcast' ) )
			->label( __( 'Columns to search', 'threewp_broadcast' ) )
			->multiple()
			->options( array_flip( $column_options ) )
			->autosize();

		$fs = $form->fieldset( 'fs_misc' )
			// Fieldset label
			->label( __( 'Other options', 'threewp_broadcast' ) );

		$broadcast_afterwards = $fs->checkbox( 'broadcast_afterwards' )
			// Input description
			->description( __( 'Broadcast the modified questions to existing child blogs.', 'threewp_broadcast' ) )
			// Input label
			->label( __( 'Broadcast afterwards', 'threewp_broadcast' ) );

		$fs = $form->fieldset( 'fs_start' )
			// Fieldset label
			->label( __( 'Search!', 'threewp_broadcast' ) );

		$find_text = $fs->primary_button( 'find' )
			// Button
			->value( __( 'Only do the text search without replacing', 'threewp_broadcast' ) );

		$replace_text = $fs->secondary_button( 'replace' )
			// Button
			->value( __( 'Start search and replace', 'threewp_broadcast' ) );

		if ( $form->is_posting() )
		{
			$form->post();
			$form->use_post_values();

			global $wpdb;
			$messages =[];
			$columns_to_process = $columns_to_process->get_post_value();
			$columns_to_search = $columns_to_search->get_post_value();
			$replacement_text = $replacement_text->get_post_value();
			$search_term = "'%" . addslashes( $text ) . "%'";
			$table = $this->get_table( 'wp_pro_quiz_question' );
			$text_for_selection = $selection_text->get_post_value();
			$text_to_replace = $text_to_replace->get_post_value();
			$where = [];

			if ( count( $columns_to_search ) > 0 )
			{
				if ( $text_for_selection != '' )
				{
					$or = [];
					// We only want to replace questions containing this selection text.
					foreach( $columns_to_search as $column_to_search )
						$or []= sprintf( "`%s` LIKE '%%%s%%'", $column_to_search, $text_for_selection );
					$or = '(' . implode( ' OR ', $or ) . ')';
					$where []= $or;
				}
			}

			// Use all columns containing the text to replace.
			$or = [];
			foreach( $columns_to_process as $column_id )
				$or []= sprintf( "`%s` LIKE '%%%s%%'", $column_id, $text_to_replace );
			$or = '(' . implode( ' OR ', $or ) . ')';
			$where []= $or;

			$where = implode( ' AND ', $where );

			$query = sprintf( "SELECT * FROM `%s` WHERE %s",
				$table,
				$where
			);
			$this->debug( $query );

			$results = $wpdb->get_results( $query );

			if ( $find_text->pressed() )
			{
				if ( count( $results ) < 1 )
					$r .= $this->info_message_box()->_( __( 'Could not find any questions containing that text.', 'threewp_broadcast' ) );
				else
				{
					$count = count( $results );
					$messages []= sprintf( __( 'Found %d questions that contain your text. Below is the first search hit:', 'threewp_broadcast' ), $count );

					// Assemble the search result.
					$result = reset( $results );
					foreach( $columns as $column => $ignore )
					{
						if ( strpos( $result->$column, $text_to_replace ) === false )
							continue;
						$messages []= sprintf( 'Found the text in the %s field: <code>%s</code>', $column, htmlspecialchars( $result->$column ) );
					}
					$messages = implode( "\n", $messages );
					$r .= $this->info_message_box()->_( $messages );
				}
			}

			if ( $replace_text->pressed() )
			{
				if ( count( $results ) < 1 )
					$r .= $this->info_message_box()->_( __( 'Could not find any questions containing that text.', 'threewp_broadcast' ) );
				else
				{
					foreach( $results as $result )
					{
						$id = $result->id;
						$update_data = [];
						foreach( $columns_to_process as $column )
						{
							if ( strpos( $result->$column, $text_to_replace ) === false )
								continue;
							if ( $column == 'answer_data' )
							{
								// We have to unserialize in order to update.
								$answer_data = maybe_unserialize( $result->$column );
								foreach( $answer_data as $index => $object )
								{
									if ( is_a( $object, 'WpProQuiz_Model_AnswerTypes' ) )
									{
										$answer = $object->getAnswer();
										$answer = str_replace( $text_to_replace, $replacement_text, $answer );
										$object->setAnswer( $answer );
									}
								}
								$update_data[ $column ] = serialize( $answer_data );
							}
							else
							{
								$update_data[ $column ] = str_replace( $text_to_replace, $replacement_text, $result->$column );
							}
						}
						$message = sprintf( 'Replacing text in %s for question <em>%s</em>.', implode( ", ", array_keys( $update_data ) ), $result->title );
						$this->debug( $message );
						$messages []= $message;
						$wpdb->update( $table, $update_data, [ 'id' => $id ] );

						// Broadcast the question?
						if ( $broadcast_afterwards->is_checked() )
						{
							// The quiz ID links to the post in the postmeta table.
							$query = sprintf( "SELECT `post_id` FROM `%s` WHERE `meta_key` = 'quiz_pro_id' AND `meta_value` = '%d' AND `post_id` > 0",
								$wpdb->postmeta,
								$result->quiz_id
							);
							$post_ids = $wpdb->get_col( $query );

							foreach( $post_ids as $post_id )
							{
								$message = sprintf( 'Broadcasting quiz %d found on post %d.', $result->quiz_id, $post_id );
								$this->debug( $message );
								$messages []= $message;

								ThreeWP_Broadcast()->api()
									->update_children( $post_id, [] );
							}
						}
					}
					$messages = implode( "\n", $messages );
					$r .= $this->info_message_box()->_( $messages );
				}
			}
		}

		$r .= $form->open_tag();
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		echo $r;
	}

	/**
		@brief		Admin tabs.
		@since		2017-10-01 18:47:00
	**/
	public function admin_tabs()
	{
		$tabs = $this->tabs();

		$tabs->tab( 'questions' )
			->callback_this( 'admin_questions' )
			// Tab heading for modifying Learndash questions.
			->heading( __( 'Question Search & Replace', 'threewp_broadcast' ) )
			// Tab name for modifying Learndash questions.
			->name( __( 'Question S&R', 'threewp_broadcast' ) );

		echo $tabs->render();
	}

	/**
		@brief		threewp_broadcast_broadcasting_after_update_post
		@since		2017-09-15 15:32:32
	**/
	public function threewp_broadcast_broadcasting_after_update_post( $action )
	{
		$bcd = $action->broadcasting_data;
		$this->maybe_prerestore_course( $bcd );
	}

	/**
		@brief		Replace the ingredients and terms with their equivalents.
		@since		2015-04-05 08:10:43
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		$bcd = $action->broadcasting_data;
		$this->maybe_restore_course( $bcd );
		$this->maybe_restore_group( $bcd );
		$this->maybe_restore_lesson( $bcd );
		$this->maybe_restore_quiz( $bcd );
		$this->maybe_restore_topic( $bcd );
	}

	/**
		@brief		Save the nutritional information and ingredient metadata.
		@since		2015-04-09 19:29:30
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		$bcd = $action->broadcasting_data;

		$this->prepare_bcd( $bcd );
		$this->maybe_save_quiz( $bcd );
	}

	/**
		@brief		Add our types.
		@since		2016-07-27 20:15:57
	**/
	public function threewp_broadcast_get_post_types( $action )
	{
		$action->add_types( 'sfwd-courses', 'sfwd-lessons', 'sfwd-quiz', 'sfwd-essays', 'sfwd-assignment', 'groups', 'sfwd-topic', 'sfwd-certificates', 'sfwd-transactions' );
	}

	/**
		@brief		Add ourselves into the menu.
		@since		2016-01-26 14:00:24
	**/
	public function threewp_broadcast_menu( $action )
	{
		if ( ! is_super_admin() )
			return;

		$action->menu_page
			->submenu( 'broadcast_learndash' )
			->callback_this( 'admin_tabs' )
			->menu_title( 'LearnDash' )
			->page_title( 'LearnDash' );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Save
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Maybe save the quiz data.
		@since		2017-02-26 16:40:30
	**/
	public function maybe_save_quiz( $bcd )
	{
		if ( $bcd->post->post_type != 'sfwd-quiz' )
			return;

		// Save the quiz.
		$quiz = $this->get_quiz( $bcd->post->ID );
		$bcd->learndash->set( 'quiz', $quiz );

		// Save the questions.
		$questions = $this->get_questions( $quiz->id );
		$this->debug( 'Found %d questions.', count( $questions ) );
		$bcd->learndash->set( 'questions', $questions );

		// Save all of the categories from the parent blog for syncing later.
		foreach( $this->get_categories() as $category_id => $category )
			$bcd->learndash->collection( 'categories' )->set( $category_id, $category );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Restore
	// --------------------------------------------------------------------------------------------

	/**
		@brief		maybe_prerestore_course
		@since		2017-09-15 15:32:56
	**/
	public function maybe_prerestore_course( $bcd )
	{
		if ( $bcd->post->post_type != 'sfwd-courses' )
			return;

		$bcd->learndash->forget( 'existing_course_meta' );

		// Do not overwrite the user enrollments, so we have to save the current value.
		$meta = $bcd->custom_fields()->child_fields()->get( '_sfwd-courses' );
		$meta = reset( $meta );
		$meta = maybe_unserialize( $meta );
		$bcd->learndash->set( 'existing_course_meta', $meta );
		$this->debug( 'Existing courses meta found! %s', $meta );
	}

	/**
		@brief		Maybe restore the course data.
		@since		2017-02-26 15:47:51
	**/
	public function maybe_restore_course( $bcd )
	{
		if ( $bcd->post->post_type != 'sfwd-courses' )
			return;

		$ld_course_steps = $bcd->custom_fields()->get_single( 'ld_course_steps' );
		$ld_course_steps = maybe_unserialize( $ld_course_steps );
		if ( is_array( $ld_course_steps ) )
		{
			// I have no idea what these characters mean.

			// h
			$h = $this->handle_ld_h_course_steps( $bcd, $ld_course_steps[ 'h' ] );
			$ld_course_steps[ 'h' ] = $h;

			// l
			$l_data = $ld_course_steps[ 'l' ];
			foreach( $l_data as $index => $item )
			{
				// Split out the item into the type and ID.
				$parts = explode( ':', $item );
				// Broadcast the post.
				$old_post_id = $parts[ 1 ];
				$new_post_id = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
				$l_data[ $index ] = sprintf( '%s:%s', $parts[ 0 ], $new_post_id );
			}
			$ld_course_steps[ 'l' ] = $l_data;

			// r
			$r = [];
			foreach( $ld_course_steps[ 'r' ] as $key => $children )
			{
				// Assemble the new key first.
				$parts = explode( ':', $key );
				$old_post_id = $parts[ 1 ];
				$new_post_id = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
				$new_key = sprintf( '%s:%s', $parts[ 0 ], $new_post_id );

				$new_children = [];
				// And now we can handle each child.
				foreach( $children as $child )
				{
					$parts = explode( ':', $child );
					$old_post_id = $parts[ 1 ];
					$new_post_id = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
					$new_child = sprintf( '%s:%s', $parts[ 0 ], $new_post_id );
					$new_children []= $new_child;
				}

				$r[ $new_key ] = $new_children;
			}
			$ld_course_steps[ 'r' ] = $r;

			// t
			foreach( $ld_course_steps[ 't' ] as $type => $posts )
			{
				$new_post_ids = [];
				foreach( $posts as $old_post_id )
				{
					$new_post_id = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
					$new_post_ids []= $new_post_id;
				}
				$ld_course_steps[ 't' ][ $type ] = $new_post_ids;
			}

			$bcd->custom_fields()->child_fields()->update_meta( 'ld_course_steps', $ld_course_steps );
		}

		$this->update_sfwd_custom_field( [
			'broadcasting_data' => $bcd,
			'meta_key' => '_sfwd-courses',
			'meta_values' => [ 'sfwd-courses_course_prerequisite', 'sfwd-courses_certificate' ],
		] );

		$this->update_association( $bcd, 'learndash_group_enrolled_' );
	}

	/**
		@brief		Maybe restore the group data.
		@since		2017-02-26 17:03:12
	**/
	public function maybe_restore_group( $bcd )
	{
		$this->update_association( $bcd, 'learndash_group_users_' );
	}

	/**
		@brief		Maybe restore the lesson data.
		@since		2017-02-26 16:22:22
	**/
	public function maybe_restore_lesson( $bcd )
	{
		if ( $bcd->post->post_type != 'sfwd-lessons' )
			return;

		$this->update_sfwd_custom_field( [
			'broadcasting_data' => $bcd,
			'meta_key' => '_sfwd-lessons',
			'meta_values' => [ 'sfwd-lessons_course' ],
		] );

		$this->update_equivalent_post_id( $bcd, 'course_id' );
	}

	/**
		@brief		Maybe restore the quiz data.
		@since		2017-02-26 16:40:30
	**/
	public function maybe_restore_quiz( $bcd )
	{
		if ( $bcd->post->post_type != 'sfwd-quiz' )
			return;

		// Note that sfwd-quiz_quiz_pro has to be saved also, but we can only do it later.
		// Man is this quiz data all over the place.
		$this->update_sfwd_custom_field( [
			'broadcasting_data' => $bcd,
			'meta_key' => '_sfwd-quiz',
			'meta_values' => [ 'sfwd-quiz_course', 'sfwd-quiz_lesson', 'sfwd-quiz_certificate' ],
		] );

		$this->update_equivalent_post_id( $bcd, 'course_id' );
		$this->update_equivalent_post_id( $bcd, 'lesson_id' );

		$quiz = $bcd->learndash->get( 'quiz' );
		if ( ! $quiz )
			return;

		global $wpdb;
		$table = $this->get_table( 'wp_pro_quiz_master' );

		// Find the quiz with the same name.
		$query = sprintf( "SELECT * FROM `%s` WHERE `name` = '%s' ORDER BY `id` DESC", $table, $quiz->name );
		$child_quiz = $wpdb->get_row( $query );

		$data = (array) $quiz;
		unset( $data[ 'id' ] );
		$this->debug( 'Quiz data is: %s', $data );

		if ( ! $child_quiz )
		{
			$this->debug( 'Creating a new quiz on the child.' );
			$wpdb->insert( $table, $data );
			$child_quiz_id = $wpdb->insert_id;
		}
		else
		{
			$child_quiz_id = $child_quiz->id;
			$this->debug( 'Using existing child quiz %d and updating.', $child_quiz_id );
			// Update the quiz table.
			$wpdb->update( $table, $data, [ 'id' => $child_quiz_id ] );
		}

		$this->debug( 'Child quiz ID is %d', $child_quiz_id );
		$bcd->custom_fields()->child_fields()->update_meta( 'quiz_pro_id', $child_quiz_id );

		// The sfwd-quiz_quiz_pro key needs to be updated separately.
		$quiz_data = $bcd->custom_fields()->child_fields()->get( '_sfwd-quiz' );
		$quiz_data = reset( $quiz_data );
		$quiz_data = maybe_unserialize( $quiz_data );
		if ( is_array( $quiz_data ) )
		{
			if ( isset( $quiz_data[ 'sfwd-quiz_quiz_pro' ] ) )
				$quiz_data[ 'sfwd-quiz_quiz_pro' ] = $child_quiz_id;
			$this->debug( 'Saving new %s data: %s', '_sfwd-quiz', $quiz_data );
			$bcd->custom_fields()->child_fields()->update_meta( '_sfwd-quiz', $quiz_data );
		}

		// Restore the questions.
		// This is a delicate procedure, since we have to overwrite instead of delete+insert, due to the question IDs used in the stats.

		// Also, the quiz ID, probably not being written by the same people as LearnDash, has nothing to do with the post ID.

		$questions = $bcd->learndash->get( 'questions', [] );
		$child_questions = $this->get_questions( $child_quiz_id );

		$categories = $this->get_categories();
		$questions_to_add = $questions;
		$table = $this->get_table( 'wp_pro_quiz_question' );

		// Update existing and delete non-needed.
		foreach( $child_questions as $child_question_index => $child_question )
		{
			$found = false;
			foreach( $questions as $question_index => $question )
			{
				if ( $child_question->title == $question->title )
				{
					$found = true;
					unset( $questions_to_add[ $question_index ] );
					// Update the question data.
					$data = (array) $question;
					$data[ 'id' ] = $child_question->id;
					$data[ 'quiz_id' ] = $child_quiz_id;
					if ( $data[ 'category_id' ] > 0 )
					{
						$category = $bcd->learndash->collection( 'categories' )->get( $data[ 'category_id' ] );
						$category_name = $category->category_name;
						$new_category_id = $this->find_equivalent_category( $categories, $category_name );
						$data[ 'category_id' ] = $new_category_id;
						$this->debug( 'Equivalent category of %s (%d) is %d', $category_name, $category->category_id, $new_category_id );
					}
					$this->debug( 'Updating child quiz question: %s', $data );
					$wpdb->update( $table, $data, [ 'id' => $child_question->id ] );
					break;
				}
			}

			if ( ! $found )
			{
				// This child question has no equivalent parent. Delete it.
				$query = sprintf( "DELETE FROM `%s` WHERE `id` = '%d'", $table, $child_question->id );
				$this->debug( 'Debug orphan quiz child question: %s', $query );
				$wpdb->query( $query );
			}
		}

		// Add new questions.
		foreach( $questions_to_add as $question_to_add )
		{
			$question_to_add = (array) $question_to_add;
			unset( $question_to_add[ 'id' ] );
			$question_to_add[ 'quiz_id' ] = $child_quiz_id;
			if ( $question_to_add[ 'category_id' ] > 0 )
			{
				$category = $bcd->learndash->collection( 'categories' )->get( $question_to_add[ 'category_id' ] );
				$category_name = $category->category_name;
				$new_category_id = $this->find_equivalent_category( $categories, $category_name );
				$question_to_add[ 'category_id' ] = $new_category_id;
				$this->debug( 'Equivalent category of %s (%d) is %d', $category_name, $category->category_id, $new_category_id );
			}
			$this->debug( 'Adding new child quiz question: %s', $question_to_add );
			$wpdb->insert( $table, $question_to_add );
		}
	}

	/**
		@brief		Maybe restore this lesson topic.
		@since		2017-02-26 16:35:29
	**/
	public function maybe_restore_topic( $bcd )
	{
		if ( $bcd->post->post_type != 'sfwd-topic' )
			return;

		$this->update_sfwd_custom_field( [
			'broadcasting_data' => $bcd,
			'meta_key' => '_sfwd-topic',
			'meta_values' => [ 'sfwd-topic_course', 'sfwd-topic_lesson' ],
		] );

		$this->update_equivalent_post_id( $bcd, 'course_id' );
		$this->update_equivalent_post_id( $bcd, 'lesson_id' );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Misc
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Find the equivalent category in this category collection.
		@details	Will insert the category if not found.
		@param		$category	The category collection as returned by get_categories().
		@param		$category_name_to_find The name of the category to find.
		@since		2017-10-19 13:57:24
	**/
	public function find_equivalent_category( $categories, $category_name_to_find )
	{
		foreach( $categories as $category )
			if( $category->category_name == $category_name_to_find )
				return $category->category_id;

		// Since we're here, the category wasn't found. Insert it.
		$this->debug( 'Creating category %s', $category_name_to_find );

		global $wpdb;
		$wpdb->insert( $this->get_table( 'wp_pro_quiz_category' ), [
			'category_name' => $category_name_to_find,
		] );
		$new_category_id = $wpdb->insert_id;

		// We need to save this so that we don't keep creating the same category.
		$categories->set( $new_category_id, (object)[
			'category_id' => $new_category_id,
			'category_name' => $category_name_to_find,
		] );

		return $new_category_id;
	}

	/**
		@brief		Save all of the categories on this blog.
		@since		2017-10-19 13:44:45
	**/
	public function get_categories()
	{
		global $wpdb;
		$r = ThreeWP_Broadcast()->collection();
		$query = sprintf( "SELECT * FROM `%s`", $this->get_table( 'wp_pro_quiz_category' ) );
		$results = $wpdb->get_results( $query );
		foreach( $results as $result )
			$r->set( $result->category_id, $result );
		return $r;
	}

	/**
		@brief		Return the equivalent post from a value in a custom field.
		@since		2017-06-29 21:45:00
	**/
	public function get_equivalent_post_from_custom_field( $bcd, $meta_key )
	{
		$old_post_id = $bcd->custom_fields()->get_single( $meta_key );
		$new_post_id = $bcd->equivalent_posts()->get( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
	}

	/**
		@brief		Return the questions of this quiz
		@since		2017-06-29 12:37:07
	**/
	public function get_questions( $quiz_id )
	{
		global $wpdb;
		$query = sprintf( "SELECT * FROM `%s` WHERE `quiz_id` = '%d'", $this->get_table( 'wp_pro_quiz_question' ), $quiz_id );
		$questions = $wpdb->get_results( $query );
		return $questions;
	}

	/**
		@brief		Return the quiz from this post ID.
		@since		2017-06-29 13:23:10
	**/
	public function get_quiz( $post_id )
	{
		$quiz_id = get_post_meta( $post_id, 'quiz_pro_id', true );
		global $wpdb;
		$query = sprintf( "SELECT * FROM `%s` WHERE `id` = '%d'", $this->get_table( 'wp_pro_quiz_master' ), $quiz_id );
		$quiz = $wpdb->get_row( $query );
		return $quiz;
	}

	/**
		@brief		Return the name of the table on this blog with the correct prefix.
		@since		2017-06-29 21:20:20
	**/
	public function get_table( $name )
	{
		global $wpdb;
		return sprintf( '%s%s', $wpdb->prefix, $name );
	}

	/**
		@brief		Broadcast the h course steps recursively.
		@since		2018-01-07 14:36:52
	**/
	public function handle_ld_h_course_steps( $bcd, $array )
	{
		$new_array = [];
		foreach( $array as $old_post_id => $subarray )
		{
			if ( strlen( intval( $old_post_id ) ) == strlen( $old_post_id ) )
				$new_post_id = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
			else
				$new_post_id = $old_post_id;
			$new_array[ $new_post_id ] = $this->handle_ld_h_course_steps( $bcd, $subarray );
		}
		return $new_array;
	}

	/**
		@brief		Prepare the broadcasting_data object.
		@since		2016-07-27 21:28:24
	**/
	public function prepare_bcd( $bcd )
	{
		if ( ! isset( $bcd->learndash ) )
			$bcd->learndash = ThreeWP_Broadcast()->collection();
	}

	/**
		@brief		Update the association to other parts of LearnDash.
		@since		2017-02-26 17:04:36
	**/
	public function update_association( $bcd, $assoc_key )
	{
		foreach( $bcd->custom_fields()->child_fields() as $key => $value )
		{
			// Look for the key.
			if ( strpos( $key, $assoc_key ) === false )
				continue;

			// Extract the assoc ID.
			$old_assoc_id = str_replace( $assoc_key, '', $key );
			$new_assoc_id = $bcd->equivalent_posts()->get( $bcd->parent_blog_id, $old_assoc_id, get_current_blog_id() );
			if ( $new_assoc_id > 0 )
			{
				$new_assoc_key = $assoc_key . $new_assoc_id;
				$this->debug( 'Assigning new association meta key: %s', $new_assoc_key );
				$bcd->custom_fields()->child_fields()->update_meta( $new_assoc_key, $value );
			}

			// Delete the old key that isn't being used.
			$bcd->custom_fields()->child_fields()->delete_meta( $key );
		}
	}

	/**
		@brief		Update the post ID in this custom field.
		@since		2017-02-26 16:37:30
	**/
	public function update_equivalent_post_id( $bcd, $meta_key )
	{
		$old_post_id = $bcd->custom_fields()->get_single( $meta_key );
		$new_post_id = $bcd->equivalent_posts()->get( $bcd->parent_blog_id, $old_post_id, get_current_blog_id() );
		$this->debug( 'Updating custom field %s with %s from blog %s, post %s', $meta_key, $new_post_id, $bcd->parent_blog_id, $old_post_id );
		$bcd->custom_fields()->child_fields()->update_meta( $meta_key, $new_post_id );
	}

	/**
		@brief		Common function to update the serialized sfwd data in the child custom field.
		@since		2017-02-26 16:24:08
	**/
	public function update_sfwd_custom_field( $options )
	{
		$options = (object) $options;

		$data = $options->broadcasting_data->custom_fields()->child_fields()->get( $options->meta_key );
		$data = reset( $data );
		$data = maybe_unserialize( $data );

		foreach( $options->meta_values as $key )
		{
			$equivalent = $options->broadcasting_data->equivalent_posts()->get( $options->broadcasting_data->parent_blog_id, $data[ $key ], get_current_blog_id() );
			$this->debug( 'Updating meta value %s to %d', $key, $equivalent );
			$data[ $key ] = intval( $equivalent );
		}

		// Do we have to merge old meta? This is mostly for user enrollments.
		if ( $options->meta_key == '_sfwd-courses' )
		{
			$key = 'sfwd-courses_course_access_list';
			$old_meta = $options->broadcasting_data->learndash->get( 'existing_course_meta' );
			if ( $old_meta )
			{
				$this->debug( 'Merging old %s: %s', $key, $old_meta[ $key ] );
				$data[ $key ] = $old_meta[ $key ];
			}
		}

		$this->debug( 'Saving new data for %s: %s', $options->meta_key, $data );
		$options->broadcasting_data->custom_fields()->child_fields()->update_meta( $options->meta_key, $data );
	}
}
