<?php
/**
 * Map View Single Event
 * This file contains one event in the map
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/map/single_event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $post;

$venue_details = tribe_get_venue_details();

// Venue microformats
$has_venue         = ( $venue_details ) ? ' vcard' : '';
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';
?>

<!-- Event Cost -->
<?php if ( tribe_get_cost() ) : ?>
	<div class="tribe-events-event-cost">
		<span><?php echo tribe_get_cost( null, true ); ?></span>
	</div>
<?php endif; ?>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>

<!-- Event Distance -->
<?php echo tribe_event_distance(); ?>

<h2 class="tribe-events-map-event-title summary">
	<a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title() ?>" rel="bookmark">
		<?php the_title() ?>
	</a>
</h2>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- Event Meta -->
<?php do_action( 'tribe_events_before_the_meta' ) ?>
<div class="tribe-events-event-meta <?php echo $has_venue . $has_venue_address; ?>">

	<!-- Schedule & Recurrence Details -->
	<div class="updated published time-details">
		<?php echo tribe_events_event_schedule_details() ?>
	</div>

	<?php if ( $venue_details ) : ?>
		<!-- Venue Display Info -->
		<div class="tribe-events-venue-details">
			<?php echo implode( ', ', $venue_details ); ?>
		</div> <!-- .tribe-events-venue-details -->
	<?php endif; ?>

</div><!-- .tribe-events-event-meta -->
<?php do_action( 'tribe_events_after_the_meta' ) ?>

<!-- Event Image -->
<?php echo tribe_event_featured_image( null, 'medium' ) ?>

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ) ?>
<div class="tribe-events-map-event-description tribe-events-content description entry-summary">
	<?php echo tribe_events_get_the_excerpt() ?>
	<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more" rel="bookmark"><?php _e( 'Find out more', 'tribe-events-calendar' ) ?> &raquo;</a>
</div><!-- .tribe-events-map-event-description -->
<?php do_action( 'tribe_events_after_the_content' ) ?>
