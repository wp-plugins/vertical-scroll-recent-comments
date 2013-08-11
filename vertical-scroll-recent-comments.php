<?php

/*
Plugin Name: Vertical scroll recent comments
Description: Vertical scroll recent comments wordpress plugin will scroll the recent post comment vertically (bottom to top) in the widget.
Author: Gopi.R
Author URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/
Plugin URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/
Version: 10.1
Tags: Vertical, scroll, recent, comments, comment, widget
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
vsrc means Vertical scroll recent comments
*/

function vsrc() 
{
	
	global $wpdb;
	?>
    <style type="text/css">
	.vsrc-regimag img { 
	float: left ;
	border: 1px solid #CCCCCC ;
	vertical-align:bottom; 
	padding: 3px ;
	margin-right: 2px;
	};
    </style>
    <?php
	$num_user = get_option('vsrc_select_num_user');
	$dis_num_user = get_option('vsrc_dis_num_user');
	$dis_num_height = get_option('vsrc_dis_num_height');
	$vsrc_select_character = get_option('vsrc_select_character');
	if(!is_numeric($num_user))
	{
		$num_user = 5;
	} 
	if(!is_numeric($dis_num_height))
	{
		$dis_num_height = 30;
	}
	if(!is_numeric($dis_num_user))
	{
		$dis_num_user = 5;
	}
	if(!is_numeric($vsrc_select_character))
	{
		$vsrc_select_character = 75;
	}

	$vsrc_data = $wpdb->get_results("SELECT * from $wpdb->comments WHERE comment_approved= '1' and comment_type<>'pingback' ORDER BY comment_date DESC LIMIT 0, $num_user");

	$vsrc_html = "";
	$post_link = "";
	$avatar = "";
	$vsrc_x = "";
	if ( ! empty($vsrc_data) ) 
	{
		$vsrc_count = 0;

		foreach ( $vsrc_data as $vsrc_data ) 
		{
			$vsrc_post_title = $vsrc_data->comment_content 	;
			$vsrc_post_title = strip_tags($vsrc_post_title);
			$vsrc_post_title = preg_replace("/[\n\t\r]/"," ",$vsrc_post_title);
			$vsrc_comment_author = $vsrc_data->comment_author 	;
			$vsrc_post_title = substr($vsrc_post_title, 0, $vsrc_select_character);
			$avatar = get_avatar( $vsrc_data->comment_author_email, 30 );
			$dis_height = $dis_num_height."px";
			$vsrc_html = $vsrc_html . "<div class='vsrc_div' style='height:$dis_height;padding:2px 0px 2px 0px;'>"; 
			if(get_option('vsrc_dis_image_or_name') == "NAME" )
			{
				$vsrc_html = $vsrc_html . "<span>$vsrc_comment_author: </span>";
				$vsrc_js_html = "<span>$vsrc_comment_author: </span>";
			}
			elseif(get_option('vsrc_dis_image_or_name') == "IMAGE")
			{
				$vsrc_html = $vsrc_html . "<span class='vsrc-regimag'>$avatar</span>";
				$avatar = mysql_real_escape_string($avatar);
				$vsrc_js_html = "<span class=\'vsrc-regimag\'>$avatar</span>";
			}
			$vsrc_html = $vsrc_html . "<span>$vsrc_post_title...</span>";
			$vsrc_html = $vsrc_html . "</div>";
			$vsrc_post_title = mysql_real_escape_string(trim($vsrc_post_title));
			$post_link    = get_permalink($vsrc_data->comment_post_ID);
			$comment_link = $post_link ."#comment-$vsrc_data->comment_ID";
			$vsrc_post_title = "<a href=\'$comment_link\'>$vsrc_post_title ...</a>";
			$vsrc_x = $vsrc_x . "vsrc_array[$vsrc_count] = '<div class=\'vsrc_div\' style=\'height:$dis_height;padding:2px 0px 2px 0px;\'>$vsrc_js_html<span>$vsrc_post_title</span></div>'; ";	
			$vsrc_count++;
		}

		$dis_num_height = $dis_num_height + 4;
		if($vsrc_count >= $dis_num_user)
		{
			$vsrc_count = $dis_num_user;
			$vsrc_height = ($dis_num_height * $dis_num_user);
		}
		else
		{
			$vsrc_count = $vsrc_count;
			$vsrc_height = ($vsrc_count*$dis_num_height);
		}
		$vsrc_height1 = $dis_num_height."px";
		?>	
		<div style="padding-top:8px;padding-bottom:8px;">
			<div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 1px; height: <?php echo $vsrc_height1; ?>;" id="vsrc_Holder">
				<?php echo $vsrc_html; ?>
			</div>
		</div>
		<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/vertical-scroll-recent-comments/vertical-scroll-recent-comments.js"></script>
		<script type="text/javascript">
		var vsrc_array	= new Array();
		var vsrc_obj	= '';
		var vsrc_scrollPos 	= '';
		var vsrc_numScrolls	= '';
		var vsrc_heightOfElm = '<?php echo $dis_num_height; ?>'; // Height of each element (px)
		var vsrc_numberOfElm = '<?php echo $vsrc_count; ?>';
		var vsrc_scrollOn 	= 'true';
		function vsrc_createscroll() 
		{
			<?php echo $vsrc_x; ?>
			vsrc_obj = document.getElementById('vsrc_Holder');
			vsrc_obj.style.height = (vsrc_numberOfElm * vsrc_heightOfElm) + 'px'; // Set height of DIV
			vsrc_content();
		}
		</script>
		<script type="text/javascript">
		vsrc_createscroll();
		</script>
		<?php
	}
	else
	{
		echo "<div style='padding-bottom:5px;padding-top:5px;'>No data available!</div>";
	}
}

function vsrc_install() 
{
	add_option('vsrc_title', "Recent Comments");
	add_option('vsrc_select_num_user', "10");
	add_option('vsrc_dis_num_user', "5");
	add_option('vsrc_dis_num_height', "60");
	add_option('vsrc_dis_image_or_name', "NAME");
	add_option('vsrc_select_character', "50");
}

function vsrc_control() 
{
	echo 'Vertical scroll recent comments.';
}

function vsrc_admin_options()
{
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32"></div>
		<h2>Vertical scroll recent comments</h2>
		<?php	
		$vsrc_title = get_option('vsrc_title');
		$vsrc_select_num_user = get_option('vsrc_select_num_user');
		$vsrc_dis_num_user = get_option('vsrc_dis_num_user');
		$vsrc_dis_num_height = get_option('vsrc_dis_num_height');
		$vsrc_dis_image_or_name = get_option('vsrc_dis_image_or_name');
		$vsrc_select_character = get_option('vsrc_select_character');
		if (isset($_POST['vsrc_form_submit']) && $_POST['vsrc_form_submit'] == 'yes')
		{
			check_admin_referer('vsrc_form_setting');
			$vsrc_title = stripslashes($_POST['vsrc_title']);
			$vsrc_select_num_user = stripslashes($_POST['vsrc_select_num_user']);
			$vsrc_dis_num_user = stripslashes($_POST['vsrc_dis_num_user']);
			$vsrc_dis_num_height = stripslashes($_POST['vsrc_dis_num_height']);
			$vsrc_dis_image_or_name = stripslashes($_POST['name_ava']);
			$vsrc_select_character = stripslashes($_POST['vsrc_select_character']);
			update_option('vsrc_title', $vsrc_title );
			update_option('vsrc_select_num_user', $vsrc_select_num_user );
			update_option('vsrc_dis_num_user', $vsrc_dis_num_user );
			update_option('vsrc_dis_num_height', $vsrc_dis_num_height );
			update_option('vsrc_dis_image_or_name', $vsrc_dis_image_or_name );
			update_option('vsrc_select_character', $vsrc_select_character );
			?>
			<div class="updated fade">
				<p><strong>Details successfully updated.</strong></p>
			</div>
			<?php
		}
		if($vsrc_dis_image_or_name == "NAME")
		{
			$display_name = 'checked="checked"';
		}
		elseif($vsrc_dis_image_or_name == "IMAGE")
		{
			$display_avator = 'checked="checked"';
		}
		else
		{
			$display_none = 'checked="checked"';
		}
		?>
		<form name="vsrc_form" method="post" action="">
		    <h3>Widget setting</h3>
		
			<label for="tag-width">Widget title</label>
			<input name="vsrc_title" type="text" value="<?php echo $vsrc_title; ?>"  id="vsrc_title" size="50" maxlength="150">
			<p>Please enter your widget title.</p>
			
			<label for="tag-width">Height</label>
			<input name="vsrc_dis_num_height" type="text" value="<?php echo $vsrc_dis_num_height; ?>"  id="vsrc_dis_num_height" maxlength="4">
			<p>Please enter your height. If any overlap in the scroll at front end, <br />You should arrange this height (increase/decrease this height). (Example: 10)</p>
			
			<label for="tag-width">Display count</label>
			<input name="vsrc_dis_num_user" type="text" value="<?php echo $vsrc_dis_num_user; ?>"  id="vsrc_dis_num_user" maxlength="2">
			<p>Please enter your display count. Display number of comments at the same time in scroll. (Example: 5)</p>
			
			<label for="tag-width">Scroll comment count</label>
			<input name="vsrc_select_num_user" type="text" value="<?php echo $vsrc_select_num_user; ?>"  id="vsrc_select_num_user" maxlength="3">
			<p>Please enter your scroll comment count. Enter max number of comments to scroll. (Example: 10)</p>
			
			<label for="tag-width">Comments length</label>
			<input name="vsrc_select_character" type="text" value="<?php echo $vsrc_select_character; ?>"  id="vsrc_select_character" maxlength="3">
			<p>Please enter number of comment characters you like to display in the scroll. (Example: 50)</p>
			
			<label for="tag-width"></label>
			Display name : <input name="name_ava" id="name_ava" type="radio" value="NAME" <?php echo $display_name; ?> /> &nbsp;  &nbsp; 
			Display Avator : <input name="name_ava" id="name_ava" type="radio" value="IMAGE" <?php echo $display_avator; ?> /> &nbsp;  &nbsp; 
			None : <input name="name_ava" id="name_ava" type="radio" value="NONE" <?php echo $display_none; ?> />
			<p>Please select your disply option.</p>
			
			<p class="submit">
				<input name="vsrc_submit" id="vsrc_submit" class="button" value="Submit" type="submit" />&nbsp;
				<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/">Help</a>
			</p>
			<input type="hidden" name="vsrc_form_submit" value="yes"/>
			<?php wp_nonce_field('vsrc_form_setting'); ?>
		</form>
		</div>
		<h3>Plugin configuration option</h3>
		<ol>
			<li>Add directly in to the theme using PHP code.</li>
			<li>Drag and drop the widget to your sidebar.</li>
		</ol>
	  <p class="description">Check official website for more information <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/">click here</a></p>
	</div>
	<?php
}

function vsrc_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('vsrc_title');
	echo $after_title;
	vsrc();
	echo $after_widget;
}

function vsrc_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('vertical-scroll-recent-comments', 'Vertical scroll recent comments', 'vsrc_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('vertical-scroll-recent-comments', array('Vertical scroll recent comments', 'widgets'), 'vsrc_control');
	} 
}

function vsrc_add_to_menu() 
{
	add_options_page('Vertical scroll recent comments', 'Vertical scroll recent comments', 'manage_options', __FILE__, 'vsrc_admin_options' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'vsrc_add_to_menu');
}

function vsrc_deactivation() 
{
	// No action required.
}

add_action("plugins_loaded", "vsrc_init");
register_activation_hook(__FILE__, 'vsrc_install');
register_deactivation_hook(__FILE__, 'vsrc_deactivation');
?>