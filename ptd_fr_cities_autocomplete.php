<?php 

/*
Plugin Name: PTD France Cities Automcomplete
Plugin URI: http://paristokyo.ae
Description: Retrieve a list of cities in france from db based on keywords
Version: 1.0.0
Author: Clarel Antoine <clarel@webagencydubai.com>
Author URI: https://clarelantoine.com
Text Domain: ptd-fr-cities-autocomplete
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/



/*
 * Enqueue js and css files from assets folder
 */
add_action( 'wp_enqueue_scripts', 'my_enqueued_assets' );

function my_enqueued_assets() {

	//custom scripts
	wp_enqueue_script( 'ptd-fr-cities-autocomplete', plugin_dir_url( __FILE__ ) . 'assets/js/ptd-fr-cities-autocomplete.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_style( 'ptd-fr-cities-autocomplete', plugin_dir_url( __FILE__ ) . 'assets/css/ptd-fr-cities-autocomplete.css', true );

	//admin-ajax.php
	wp_localize_script( 'ptd-fr-cities-autocomplete', 'my_ajax_object',
	       array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	//autocomplete library scripts (https://goodies.pixabay.com/jquery/auto-complete/demo.html)
	wp_enqueue_script( 'autocomplete', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.auto-complete.js', array( 'jquery' ), true );
	wp_enqueue_style( 'autocomplete', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.auto-complete.css', true );

	//jquery-popup-overlay library scripts (http://dev.vast.com/jquery-popup-overlay/)
	wp_enqueue_script( 'jquery-popup-overlay', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.popupoverlay.js', array( 'jquery' ), true );
}


/*
 * Add search box
 */
add_action('wp_head', 'add_input_field');

function add_input_field(){

	//echo '<input type="text" id="company_works_at" name="company_works_at">';

	echo ' <!-- Add an optional button to open the popup -->
		

		  <!-- Add content to the popup -->
		  <div id="my_popup">

		  <input type="text" id="company_works_at" name="company_works_at">

		  <!-- Add an optional button to close the popup -->
		  <button class="my_popup_close">Close</button>

		  </div>';

}


//get listings for 'works at' on submit listing page
add_action('wp_ajax_nopriv_get_listing_names', 'ajax_listings');
add_action('wp_ajax_get_listing_names', 'ajax_listings');
 
function ajax_listings() {

	global $wpdb; //get access to the WordPress database object variable
 
	$name = '%'.$wpdb->esc_like($_POST['name']).'%'; //escape for use in LIKE statement
	$sql = "SELECT * FROM sentinel_villes WHERE `nom_ville` like '$name' OR `code_postal` like '$name'"; 

	//$sql = $wpdb->prepare($sql, $name);
	$results = $wpdb->get_results($sql);

	$titles = array();
	foreach( $results as $r )
		$titles[] = $r->nom_ville.", "."<span class='test'>".$r->code_postal."</span>";
		//$postal[] = addslashes($r->code_postal);
		
	echo json_encode($titles); //encode into JSON format and output
 
	die(); //stop "0" from being output
}



?>

