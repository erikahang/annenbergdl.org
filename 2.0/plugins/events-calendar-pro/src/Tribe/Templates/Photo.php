<?php
/**
 * @for Photo Template
 * This file contains hooks and functions required to set up the photo view.
 *
 * @package TribeEventsCalendarPro
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die('-1');
}

if( !class_exists( 'Tribe__Events__Pro__Templates__Photo' )){
	class Tribe__Events__Pro__Templates__Photo extends Tribe__Events__Pro__Template_Factory {

		protected $body_class = 'events-photo';
		const AJAX_HOOK = 'tribe_photo';

		/**
		 * Array of asset packages needed for this template
		 *
		 * @var array
		 **/
		protected $asset_packages = array( 'ajax-photoview' );

		protected function hooks() {
			parent::hooks();
			add_filter( 'tribe_events_header_attributes',  array( $this, 'header_attributes') );
		}

		/**
		 * Add header attributes for photo view
		 *
		 * @return string
		 **/
		public function header_attributes($attrs) {
			$attrs['data-startofweek'] = get_option( 'start_of_week' );
			$attrs['data-view'] = 'photo';
			$attrs['data-baseurl'] = tribe_get_photo_permalink( false );

			return apply_filters('tribe_events_pro_header_attributes', $attrs);
		}


		/**
		 * Add event classes specific to photo view
		 *
		 * @param $classes
		 *
		 * @return array
		 **/
		public function event_classes( $classes ) {
			$classes[] = 'tribe-events-photo-event';

			return $classes;
		}

		/**
		 * AJAX handler for Photo view
		 *
		 * @return void
		 */
		function ajax_response() {

			$tec = Tribe__Events__Main::instance();

			Tribe__Events__Query::init();

			$tribe_paged = ( !empty( $_POST['tribe_paged'] ) ) ? intval( $_POST['tribe_paged'] ) : 1;

			$post_status = array( 'publish' );
			if ( is_user_logged_in() ) {
				$post_status[] = 'private';
			}

			$args = array(
				'eventDisplay' => 'list',
				'post_type'    => Tribe__Events__Main::POSTTYPE,
				'post_status'  => $post_status,
				'paged'        => $tribe_paged
			);

			$view_state = 'photo';

			if ( isset( $_POST['tribe_event_category'] ) ) {
				$args[Tribe__Events__Main::TAXONOMY] = $_POST['tribe_event_category'];
			}

			/* if past view */
			if ( ! empty( $_POST['tribe_event_display'] ) && $_POST['tribe_event_display'] == 'past' ){
				$view_state = 'past';
				$args['eventDisplay'] = 'past';
			}


			$query = Tribe__Events__Query::getEvents( $args, true );
			$hash  = $query->query_vars;

			$hash['paged']      = null;
			$hash['start_date'] = null;
			$hash_str           = md5( maybe_serialize( $hash ) );

			if ( !empty( $_POST['hash'] ) && $hash_str !== $_POST['hash'] ) {
				$tribe_paged   = 1;
				$args['paged'] = 1;
				$query         = Tribe__Events__Query::getEvents( $args, true );
			}

			$response = array(
				'html'        => '',
				'success'     => true,
				'max_pages'   => $query->max_num_pages,
				'hash'        => $hash_str,
				'tribe_paged' => $tribe_paged,
				'view'        => $view_state,
			);

			global $wp_query, $post;
			$wp_query = $query;
			if ( !empty( $query->posts ) ) {
				$post = $query->posts[0];
			}

			add_filter( 'tribe_events_list_pagination', array( 'Tribe__Events__Main', 'clear_module_pagination' ), 10 );

			$tec->displaying = 'photo';

			ob_start();

			tribe_get_view( 'pro/photo/content' );

			$response['html'] .= ob_get_clean();

			apply_filters( 'tribe_events_ajax_response', $response );

			header( 'Content-type: application/json' );
			echo json_encode( $response );

			die();
		}

	}
}