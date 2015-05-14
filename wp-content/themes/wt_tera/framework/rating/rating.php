<?php
class ECStarsRating {
	/**
	 * Variables privadas para el plugin
	 */
	public $url;
	public $path;

	/**
	 * Response statuses
	 */
	private $STATUS_UNKNOWN = -1;
	private $STATUS_PREVIOUSLY_VOTED = 0;
	private $STATUS_SUCCESS = 1;
	private $STATUS_REQUEST_ERROR = 2;

	/**
	 * Create the required actions for the script to appear
	 * @uses add_action
	 * @see ECStarRating::head()
	 */
	public function __construct() {

		// Add the head script and styles
		add_action('wp_head', array($this, 'head'));

		// Set the options
		register_activation_hook(__FILE__, array($this, '_set_options'));
		register_deactivation_hook(__FILE__, array($this, '_unset_options'));

		// AJAX functionality
		add_action('wp_ajax_ec_stars_rating', array($this, '_handle_vote'));
		add_action('wp_ajax_nopriv_ec_stars_rating', array($this, '_handle_vote'));
	}
	/**
	 * Echoes the main Javascript and CSS
	 * @return void
	 */
	public function head() {
		$this->headScript();
	}

	/* The main script */
	public function headScript() {
		wp_enqueue_script('wt_rating', get_template_directory_uri().'/js/wt-rating.js',array('jquery'));
		
		wp_localize_script( 'wt_rating', 'ec_ajax_data', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'codes' => array(
				'SUCCESS' => $this->STATUS_SUCCESS,
				'PREVIOUSLY_VOTED' => $this->STATUS_PREVIOUSLY_VOTED,
				'REQUEST_ERROR' => $this->STATUS_REQUEST_ERROR,
				'UNKNOWN' => $this->STATUS_UNKNOWN
			),
			'messages' => array(
				'success' => __('You have voted successfully.', 'wellthemes'),
				'previously_voted' => __('You have already voted.', 'wellthemes'),
				'request_error' => __('There was some problem.', 'wellthemes'),
				'unknown' => __('An error occurred.', 'wellthemes')
			)
		));
	}

	public function getCookieName($post_id) {
		return 'ec_sr_' . $post_id;
	}

	public function getTableName() {
		global $wpdb;
		return $wpdb->prefix . 'ec_stars_votes';
	}

	public function _handle_vote() {
		if( ! defined('YEAR_IN_SECONDS') ) {
			define('YEAR_IN_SECONDS', 365 * 24 * 60 * 60);
		}

		$post_id = intval(@$_POST['post_id']);
		$rating = intval(@$_POST['rating']);
		$cookie_name = $this->getCookieName($post_id);
		
		if( isset($_COOKIE[$cookie_name])) {
			setcookie( $cookie_name, 'true', time() + YEAR_IN_SECONDS, '/');
			die(json_encode(array('status' => $this->STATUS_PREVIOUSLY_VOTED)));
		}
		
		$current_rating = intval(get_post_meta($post_id, 'ec_stars_rating', true));
		$current_votes = intval(get_post_meta($post_id, 'ec_stars_rating_count', true));

		if( (empty($current_rating) && $current_rating !== 0) || (empty($current_votes) && $current_votes !== 0) ) {
			die(json_encode(array(
				'status' => $this->STATUS_REQUEST_ERROR,
				'current_votes' => $current_votes,
				'current_rating' => $current_rating
			)));
		}

		update_post_meta($post_id, 'ec_stars_rating', $current_rating + $rating);
		update_post_meta($post_id, 'ec_stars_rating_count', $current_votes + 1);
		setcookie( $cookie_name, 'true', time() + YEAR_IN_SECONDS, '/');

		die(json_encode(array(
			'status' => $this->STATUS_SUCCESS,
			'votes' => $current_votes + 1,
			'total' => $current_rating + $rating,
			'result' => ($current_rating + $rating) / ($current_votes + 1)
		)));
	}

}

$ecStarRating = new ECStarsRating();

function ec_stars_rating() {
	global $post;
	$rating = get_post_meta($post->ID, 'ec_stars_rating', true);
	$votes = get_post_meta($post->ID, 'ec_stars_rating_count', true);
	if( $rating === '' ) {
		$rating = 0;
		add_post_meta($post->ID, 'ec_stars_rating', 0);
	}
	if( $votes === '' ) {
		$votes = 0;
		add_post_meta($post->ID, 'ec_stars_rating_count', 0);
	}

	$votes = intval($votes);
	$rating = intval($rating);
	
	if( $votes === 0 ) {
		$result = 0;
	} else {
		$result = $rating / $votes;
	}

?>
<div class="ec-stars-outer hreview-aggregate">
	
	<div class="ec-stars-wrapper" data-post-id="<?php echo $post->ID ?>">
		<div class="ec-stars-overlay" style="width: <?php echo (100 - $result * 100 / 5) ?>%"></div>
		<a href="#" data-value="1" title="<?php _e('Rate it 1 star', 'wellthemes'); ?>"><i class="fa fa-star"></i></a>
		<a href="#" data-value="2" title="<?php _e('Rate it 2 stars', 'wellthemes'); ?>"><i class="fa fa-star"></i></a>
		<a href="#" data-value="3" title="<?php _e('Rate it 3 stars', 'wellthemes'); ?>"><i class="fa fa-star"></i></a>
		<a href="#" data-value="4" title="<?php _e('Rate it 4 stars', 'wellthemes'); ?>"><i class="fa fa-star"></i></a>
		<a href="#" data-value="5" title="<?php _e('Rate it 5 stars', 'wellthemes'); ?>"><i class="fa fa-star"></i></a>
	</div>
	
	<div class="ec-stars-value">
		<span class="ec-stars-rating-value"><?php
			echo is_int($result) ? $result : number_format($result, 1);
		?></span> (<span class="ec-stars-rating-count votes"><?php echo $votes ?></span> <?php echo __('votes', 'wellthemes') ?>)
	</div>
	
	<div style="display: none" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
		<span itemprop="itemreviewed"><?php the_title(); ?></span>
		<span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">
		  <span itemprop="average"><?php echo $result ?></span>
		  out of <span itemprop="best">5</span>
		</span>
		based on <span itemprop="votes"><?php echo $votes ?></span> ratings.
		<span itemprop="count"><?php echo $votes ?></span> user reviews.
	</div>  
	
	
</div>

<?php
}

function ec_stars_rating_archive() {
	global $post;
	$rating = get_post_meta($post->ID, 'ec_stars_rating', true);
	$votes = get_post_meta($post->ID, 'ec_stars_rating_count', true);
	if( $rating === '' ) {
		$rating = 0;
		add_post_meta($post->ID, 'ec_stars_rating', 0);
	}
	if( $votes === '' ) {
		$votes = 0;
		add_post_meta($post->ID, 'ec_stars_rating_count', 0);
	}

	$votes = intval($votes);
	$rating = intval($rating);
	
	if( $votes === 0 ) {
		$result = 0;
	} else {
		$result = $rating / $votes;
	}

	$full_stars = intval($result);		
	$empty_stars = 5 - $full_stars;
	
	$half_star = 0;		
	if(floor( $result ) != $result) {
		$half_star = 1;
	}

	if ($half_star == 1) {
		$empty_stars = $empty_stars -1;
	}
	
	$i = 0;
	$stars = "";
	while($i < $full_stars){
		$stars .= '<i class="fa fa-star"></i>';
		$i++;
	}
	
	if ($half_star == 1) {
		$stars .= '<i class="fa fa-star-half-o"></i>';
	}
	 
	$y = 0;
	while($y < $empty_stars){
		$stars .= '<i class="fa fa-star-o"></i>';
		$y++;
	}	
		
	if ($result > 0 ) {
		echo '<div class="entry-rating">'.$stars.'</div>';
	}
}