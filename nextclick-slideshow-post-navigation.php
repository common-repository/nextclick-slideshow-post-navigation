<?php
/*
Plugin Name: Nextclick Slideshow Post Navigation
Plugin URI: https://nextclick.co/wordpress/plugins/post-navigation/
Description: This plugin replaces the Wordpress internal post pagination functionality, while allowing the user to customize the look / style of next buttons.
Version: 1.1
Author: NextClick
Author URI: https://nextclick.co/
*/

define('NSPN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NSPN_PLUGIN__URL', plugin_dir_url(__FILE__));

/*add plugin in admin menu*/
function postnavigation_admin_actions() {
	add_submenu_page( 'options-general.php','NextClick Post Navigation', 'NextClick Post Navigation', '0', 'post-navigation', 'nspn_custom_menu_page');
}
add_action('admin_menu', 'postnavigation_admin_actions');

/*activate,deactivate plugin*/
register_activation_hook( __FILE__, 'postnave_create_db' );

function nspn_load_custom_wp_admin_style() {
        // Load only on ?page=post-navigation
        if($_GET['page'] != 'post-navigation') {
                return;
        }
        wp_enqueue_style( 'nspn-style', plugins_url('css/style.css', __FILE__) );
        wp_enqueue_script( 'nspn-color-js', plugins_url('js/jscolor.js', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'nspn_load_custom_wp_admin_style' );

function nspn_custom_menu_page(){
global $wpdb;
if($_POST){
	if(isset($_REQUEST['update']))
	{
		$fontFamily = $_REQUEST['fontFamily'];
		$fontSize = $_REQUEST['fontSize'];
		$bgcolor = $_REQUEST['bgcolor'];
		$bghovercolor = $_REQUEST['bghovercolor'];
		$color = $_REQUEST['color'];
		$hovercolor = $_REQUEST['hovercolor'];
		$fb = $_REQUEST['fb'];
		$show_next_btn = $_REQUEST['show_next_btn'];
		$btnlink = $_REQUEST['btnlink'];
		$tablename=$wpdb->prefix.'postnav';
		$data=array(
			'fontfamily' => $fontFamily,
			'fontsize' => $fontSize,
			'bgcolor' => $bgcolor,
			'bgcolor_hover' => $bghovercolor,
			'fontcolor' => $color,
			'fontcolor_hover' => $hovercolor,
			'facebook' => $fb,
			'nextbutton' => $show_next_btn,
			'nextbutton_link' => $btnlink,
		);
		 $wpdb->update($tablename, $data ,array( 'id' => 1 ));
	}
}
?>
<html>
	<head>
	</head>
	<body>
		<div class="main">
			<div class="header">
				<div class="plugin_title">
					<h1>NextClick Post Navigation</h1>
				</div>
			</div>
		</div>
		<div class="content">
			<?php
					global $wpdb;
					$tablename=$wpdb->prefix.'postnav';
					$sql = "SELECT * FROM $tablename where id='1'";
					$result = $wpdb->get_results($sql);
						foreach( $result as $results ) {

			?>
				<form name="frm_post_nav" class="frm_post_nav" method="post" autocomplete="on" action="">
					<table class="tbl_data" cellpadding=20px>
						<tr>
							<td><label>Font family</label></td>
							<td><input type="text" class="txtinput" name="fontFamily" value="<?php echo $results->fontfamily;?>"></td>
						</tr>
						<tr>
							<td><label>Font size</label></td>
							<td><input type="text" name="fontSize" class="txtinput" value="<?php echo $results->fontsize;?>"></td>
						</tr>
						<tr>
							<td><label>Button Background colour</label></td>
<td><input class="jscolor" name="bgcolor" value="<?php echo $results->bgcolor;?>"></td>
						</tr>
						<tr>
							<td><label>Button Background hover colour</label></td>
<td><input class="jscolor" name="bghovercolor" value="<?php echo $results->bgcolor_hover;?>"></td>
						</tr>
						<tr>
							<td><label>Font colour</label></td>
							<td><input class="jscolor" name="color" value="<?php echo $results->fontcolor;?>"></td>
						</tr>
						<tr>
							<td><label>Font Hover colour</label></td>
							<td><input class="jscolor" name="hovercolor" value="<?php echo $results->fontcolor_hover;?>"></td>
						</tr>
						<tr>
							<td><label>Facebook Icon?</label></td>
							<td>
								<input type="radio" name="fb" value="Yes" <?php if($results->facebook == "Yes") echo "checked=checked"; ?>>Yes&nbsp;
								<input type="radio" name="fb" value="No" <?php if($results->facebook == "No") echo "checked=checked"; ?>>No
							</td>
						</tr>
						<tr><td colspan="2">Right now the next button on the last page of your gallery is being sent to another related website through the NextClick Exchange. If you would like to make money off these clicks please head to <a href="https://nextclick.co" target="_blank">https://nextclick.co</a> and register as a publisher, then input your click url below. You can also link the next button on the last page of a post to any url on this domain</td></tr>
						<tr>
							<td><label>Disable the next button on the last page?</label></td>
							<td>
								<input type="radio" name="show_next_btn" value="Yes" <?php if($results->nextbutton == "Yes") echo "checked=checked"; ?>>Yes&nbsp;
								<input type="radio" name="show_next_btn" value="No" <?php if($results->nextbutton == "No") echo "checked=checked"; ?>>No
							</td>
						</tr>
						<tr>
							<td><label>Next Button Link</label></td>
							<td>
								<input type="text" name="btnlink" value="<?php echo $results->nextbutton_link;?>">
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center;"><input type="submit" name="update" value="Update" class="update"></td>
						</tr>
					</table>
				</form>
			<!--<div class="shortcode">
				<h1>Use shortcode</h1>
				<div class="box_shortcode">
					<p>[post-navigation]</p>
				</div>
			</div> -->
		</div>
	</body>
</html>
<?php }
}

/*
//shortcode
add_shortcode( 'post-navigation', 'fnshortcode');
function fnshortcode(){
	?>

	<?php
		global $wpdb;
		$tablename=$wpdb->prefix.'postnav';
		$sql = "SELECT * FROM $tablename where id='1'";
		$result = $wpdb->get_results($sql);
		foreach( $result as $results ) {
			$fontfamily = $results->fontfamily;
			$fontsize = $results->fontsize;
			$bgcolor = $results->bgcolor;
			$bgcolor_hover = $results->bgcolor_hover;
			$fontcolor = $results->fontcolor;
			$fontcolor_hover = $results->fontcolor_hover;
			$facebook = $results->facebook;
		}
	?>
		<style type="text/css">
			.nav-links{display:none;}
			#btnnext{background:<?php echo $bgcolor;?>}
			#btnnext:hover{background:<?php echo $bgcolor_hover;?>;}
			.txtNext p{font-family:<?php echo $fontfamily;?>;font-size:<?php echo $fontsize;?>;color:<?php echo $fontcolor;?>;width:auto;}
			.txtNext p:hover{color:<?php echo $fontcolor_hover;?>}
		</style>
	<?php
		if(get_next_post())
		{
			$next_post = get_next_post();
			$next_title = $next_post->post_title;
			$post_permalink = get_permalink($next_post->ID);
			$next_title = strip_tags(str_replace('"', '', $next_title));
		}
		else
		{
			global $wpdb;
			$posttable=$wpdb->prefix.'posts';
			$sql = "SELECT * FROM $posttable WHERE `post_status` LIKE '%publish%' AND `comment_status` LIKE '%open%'";
			$result = $wpdb->get_results($sql);
			foreach( $result as $results ) {
				$post_title = $results->post_title;
				$next_title = strip_tags(str_replace('"', '', $post_title));
				$post_permalink = get_permalink($results->ID);
				break;
			}
		}
	 ?>
	 <?php
		if($facebook == "Yes")
		{
			echo '<div class="container"><a href="https://www.facebook.com/sharer/sharer.php?u=http://historylocker.com/23-nba-players-that-lost-everything/" id="facebook-btn" target="_blank"><div class="round-arrow"><i class="fa fa-facebook"></i></div></a><a rel="next" href="' . $post_permalink . '" title="' . $next_title. '" id="btnnext"><div class="txtNext"><p>Next<i class="fa fa-chevron-right round-arrow-right"></i></p></div></a></div>';
		}
		else
		{
			?>
<style type="text/css">#btnnext{width:100% !important;}</style>
			<?php
			echo '<div class="container"><a rel="next" href="' . $post_permalink . '" title="' . $next_title. '" id="btnnext"><div class="txtNext"><p>Next<i class="fa fa-chevron-right round-arrow-right"></i></p></div></a></div>';
		}
}
*/

function postnave_create_db() {
   	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'postnav';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		fontfamily varchar(100) NOT NULL,
		fontsize varchar(100) NOT NULL,
		bgcolor varchar(100) NOT NULL,
		bgcolor_hover varchar(100) NOT NULL,
		fontcolor varchar(100) NOT NULL,
		fontcolor_hover varchar(100) NOT NULL,
		facebook varchar(100) NOT NULL,
		nextbutton varchar(100) NOT NULL,
		nextbutton_link varchar(100) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$data=array(
		'fontfamily' => 'FontAwesome',
		'fontsize' => '30pt',
		'bgcolor' => 'BA2727',
		'bgcolor_hover' => 'cea533',
		'fontcolor' => 'ffffff',
		'fontcolor_hover' => 'cccccc',
		'facebook' => 'Yes',
		'nextbutton' => 'No',
		'nextbutton_link' => 'https://nextclick.co/click/28',
	);
     $wpdb->insert($table_name, $data);
}

// Delete table when deactivate
function postnavigation_remove_database() {
     global $wpdb;
	 $table_name = $wpdb->prefix."postnav";
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     function postnavigation_uninstall() {
		 deactivate_plugins( __FILE__ );
		 //delete_option( 'post-navigation' );
	}
	register_uninstall_hook( __FILE__, 'postnavigation_uninstall' );
}
register_deactivation_hook( __FILE__, 'postnavigation_remove_database' );

//register_deactivation_hook( __FILE__, array( 'Post Navigation', 'plugin_deactivation' ) );


add_filter('wp_link_pages_args','nspn_add_next_and_number');
function nspn_add_next_and_number($args){

		global $wpdb;
		global $page, $numpages, $multipage, $more, $pagenow;
		$tablename=$wpdb->prefix.'postnav';
		$sql = "SELECT * FROM $tablename where id='1'";
		$result = $wpdb->get_results($sql);
		foreach( $result as $results ) {
			$fontfamily = $results->fontfamily;
			$fontsize = $results->fontsize;
			$bgcolor = $results->bgcolor;
			$bgcolor_hover = $results->bgcolor_hover;
			$fontcolor = $results->fontcolor;
			$fontcolor_hover = $results->fontcolor_hover;
			$facebook = $results->facebook;
			$nextbutton = $results->nextbutton;
			$nextbutton_link = $results->nextbutton_link;
		}
	if ($numpages > 1) {
	if($facebook == "Yes")
	{
		$current_url= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$facebook = '<div class="container"><a href="https://www.facebook.com/sharer/sharer.php?u='.$current_url.'" id="facebook-btn" target="_blank"><div class="round-arrow"  style="background:#3b5998;font-family:'.$fontfamily.';font-size:'.$fontsize.';color:#ffffff"><i class="fa fa-facebook" style="padding:10px;"></i></div></a><div class="post-navigation-next"style="background:#'.$bgcolor.'; " >';
	}
	else{
		$facebook = '<div class="container"><div class="post-navigation-next"style="background:#'.$bgcolor.'; width:100%" >';
	}

   echo "<style>
			.post-navigation-next:hover{ background-color: #".$bgcolor_hover." !important; }
			.post-navigation-next:hover .spannextlink{color: #".$fontcolor_hover." !important;}
		</style>";
    if($numpages > $page){
		$args['next_or_number'] = 'Next';
		$next = '';
		$args['before'] = $facebook;
		$args['after'] = '</div>';
		$args['nextpagelink'] = '<span class="round-arrow spannextlink" style="font-family:'.$fontfamily.';font-size:'.$fontsize.';color:#'.$fontcolor.'">Next<i class="fa fa-chevron-right round-arrow-right" style="padding:10px;"></i></div></span>';
		$args['previouspagelink'] = '';
	}
	else{
		$args['next_or_number'] = 'Next';
		$next = '';
		$args['before'] = '';
		$args['after'] = '';
		$args['nextpagelink'] = '';
		$args['previouspagelink'] = '';
		if($nextbutton == 'No'){
			if($nextbutton_link == ''){
				$nextbutton_link = 'https://nextclick.co/click/28';
			}
			echo $facebook.'<a href="'.$nextbutton_link.'" rel="external nofollow"><span class="round-arrow spannextlink" style="font-family:'.$fontfamily.';font-size:'.$fontsize.';color:#'.$fontcolor.'">Next<i class="fa fa-chevron-right round-arrow-right" style="padding:10px;"></i></div></span></a>';
		}
	}}
    return $args;
}

function nspn_do_includes(){
        wp_enqueue_style( 'nspn-font-awesome', plugins_url('fafaicon/css/font-awesome.min.css', __FILE__));
		wp_enqueue_style( 'nspn-style', plugins_url('css/style.css', __FILE__) );
    }
add_action('wp_enqueue_scripts', 'nspn_do_includes');

function nspn_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=post-navigation">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'nspn_add_settings_link' );
?>