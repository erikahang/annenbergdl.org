<?php
/**
 * Events Pro Venue Widget
 * This is the template for the output of the venue widget.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/widgets/venue-widget.php
 *
 * @package TribeEventsCalendarPro
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_plural = tribe_get_event_label_plural();

?>

<div class="tribe-venue-widget-wrapper">
	<div class="tribe-venue-widget-venue">
		<div class="tribe-venue-widget-venue-name">
			<?php echo tribe_get_venue_link( $venue_ID ); ?>
		</div>
		<?php if ( has_post_thumbnail( $venue_ID ) ) { ?>
			<div class="tribe-venue-widget-thumbnail">
				<?php echo get_the_post_thumbnail( $venue_ID, 'related-event-thumbnail' ); ?>
			</div>
		<?php } ?>
		<div class="tribe-venue-widget-address">
			<?php echo tribe_get_meta_group( $venue_ID, 'tribe_event_venue' ) ?>
		</div>
	</div>

	<?php if ( 0 === $events->post_count ): ?>
		<?php printf( __( 'No upcoming %s.', 'tribe-events-calendar-pro' ),  strtolower( $events_label_plural ) ); ?>
	<?php else: ?>
		<?php do_action( 'tribe_events_venue_widget_before_the_list' ); ?>
		<ul class="tribe-venue-widget-list hfeed vcalendar">
			<?php while ( $events->have_posts() ): ?>
				<?php $events->the_post(); ?>
				<li class="<?php tribe_events_event_classes() ?>">
					<h4 class="entry-title summary">
						<a href="<?php echo esc_url( tribe_get_event_link() ); ?>"><?php echo get_the_title( get_the_ID() ) ?></a>
					</h4>
					<?php echo tribe_events_event_schedule_details() ?>
					<?php if ( tribe_get_cost( get_the_ID() ) != '' ): ?>
						<span class="tribe-events-divider">|</span>
						<span class="tribe-events-event-cost">
						<?php echo tribe_get_cost( get_the_ID(), true ); ?>
					</span>
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php do_action( 'tribe_events_venue_widget_after_the_list' ); ?>
	<?php endif; ?>

	<a href="<?php echo esc_url( tribe_get_venue_link( $venue_ID, false ) ); ?>"><?php printf( __( 'View all %s at this %s', 'tribe-events-calendar' ), $events_label_plural, tribe_get_venue_label_singular() ); ?></a>
</div>
