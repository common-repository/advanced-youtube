<?php
/*
Plugin Name: Advanced YouTube
Plugin URI: http://fusionswift.com/advanced-youtube/
Description: Advanced YouTube provides a shortcode to easily insert a YouTube video to a post. This plugin will generate a W3C valid embed code, and provides options for customizing the video player.
Version: 0.1
Author: Tech163
Author URI: http://fusionwift.com/
*/

function adv_youtube_admin() {
	if(!empty($_POST['color1'])) {
		$update['color1'] = $_POST['color1'];
		$update['color2'] = $_POST['color2'];
		$update['sizew'] = $_POST['sizew'];
		$update['sizeh'] = $_POST['sizeh'];
		if(!empty($_POST['related']))
			$update['related'] = $_POST['related'];
		else
			$update['related'] = '';
		if(!empty($_POST['privacy']))
			$update['privacy'] = $_POST['privacy'];
		else
			$update['privacy'] = '';
		if(!empty($_POST['border']))
			$update['border'] = $_POST['border'];
		else
			$update['border'] = '';
		update_option('advanced_youtube', $update);
		?><div id="message" class="updated fade"><p><strong>Advanced YouTube settings saved.</strong></p></div>
	<?php }
	$options = get_option('advanced_youtube');
	if(empty($options)) {
		$default = array(
			'color1' => '3a3a3a',
			'color2' => '999999',
			'sizew' => '560',
			'sizeh' => '340',
			'related' => '',
			'privacy' => 'true',
			'border' => '');
		update_option('advanced_youtube', $default);
		$options = get_option('advanced_youtube');
	}
	$color1 = $options['color1'];
	$color2 = $options['color2'];
	$sizew = $options['sizew'];
	$sizeh = $options['sizeh'];
	if($options['related'] == 'true')
		$related = 'checked';
	if($options['privacy'] == 'true')
		$privacy = 'checked';
	if($options['border'] == 'true')
		$border = 'checked';
	?>
<div class="wrap"><h2>Advanced YouTube</h2>

<div class="wrap" style="max-width:950px !important;">
	<p>It is recommended that you do not change these settings. However, you may if you want to.</p>
	<form action="" method="post">
	<?php wp_nonce_field('update-options'); ?>
	<input type="hidden" name="action" value="update" />
	<table>
		<tr>
			<td>Color 1</td>
			<td><input type="text" name="color1" value="<?php echo $color1; ?>" /></td>
		</tr>
		<tr>
			<td>Color 2</td>
			<td><input type="text" name="color2" value="<?php echo $color2; ?>" /></td>
		</tr>
		<tr>
			<td>Video Width</td>
			<td><input type="text" name="sizew" value="<?php echo $sizew; ?>" /></td>
		</tr>
		<tr>
			<td>Video Height</td>
			<td><input type="text" name="sizeh" value="<?php echo $sizeh; ?>" /></td>
		</tr>
		<tr>
			<td>Show Related Videos</td>
			<td><input type="checkbox" name="related" value="true" <?php echo $related; ?> /></td>
		</tr>
		<tr>
			<td>Privacy Mode</td>
			<td><input type="checkbox" name="privacy" value="true" <?php echo $privacy; ?> /></td>
		</tr>
		<tr>
			<td>Show Border</td>
			<td><input type="checkbox" name="border" value="true" <?php echo $border; ?> /></td>
		</tr>
	</table>
	<p><input type="submit" value="Update Settings" /></p>
	</form> 
</div>
<?php
}

function adv_youtube_parse($content) {
	$options = get_option('advanced_youtube');
	$url = 'http://www.youtube' . ($options['privacy'] == 'true' ? '-nocookie' : null) . '.com/v/$1&amp;hl=en_US&amp;fs=1' . ($options['related'] == 'true' ? '' : '&amp;rel=0') . '&amp;color1=0x' . $options['color1'] . '&amp;color2=0x' . $options['color2'] . ($options['border'] == 'true' ? '&amp;border=1' : null );
	$pattern = '/\[youtube\]http:\/\/www.youtube.com\/watch\?v\=([a-zA-Z0-9-_]+)\[\/youtube\]/i';
	$output = '<object type="application/x-shockwave-flash" style="width:' . $options['sizew'] . 'px; height:' . $options['sizeh'] . 'px;" data="' . $url . '"><param name="movie" value="' . $url . '" />
	<a href="http://www.youtube.com/watch?v=$1"><img src="http://img.youtube.com/vi/$1/0.jpg" width="' . $options['sizew'] . '" height="' . $options['sizeh'] . '" alt="YouTube Video" /></a>
	
	</object>';
	return preg_replace($pattern, $output, $content);
}

function adv_youtube_menu() {
	add_options_page('Advanced Youtube', 'Advanced Youtube', 8, basename(__FILE__), 'adv_youtube_admin');
}
add_action('admin_menu', 'adv_youtube_menu');
add_filter('the_content', 'adv_youtube_parse');
add_filter('the_excerpt', 'adv_youtube_parse');
?>