<?php

$selected_tab = isset($_GET['omnivideo-tab']) ? esc_attr($_GET['omnivideo-tab']) : 'youtube';
if (!in_array($selected_tab, array('youtube', 'vimeo', 'dailymotion'))) {
	$selected_tab = 'youtube';
}

if (isset($_POST['omnivideo-submit'])) {
	$shortcode =  stripslashes($_POST['omnivideo-shortcode']);
	return media_send_to_editor($shortcode);
}
else if (isset($_POST['omnivideo-cancel'])) {
	return media_send_to_editor('');
}
?>
<script type="text/javascript">
	$j = jQuery.noConflict();

	function omnivideoAdminHtmlEncode(value){
		return $j('<div/>').text(value).html();
	}

	$j(document).ready(function() {
		$j('#omnivideo-shortcode-form input[type="text"], #omnivideo-shortcode-form select, #omnivideo-shortcode-form input[type="checkbox"]').change(function(event) {
			var comboValues = $j('#omnivideo-shortcode-form').serializeArray();
			var newValues = new Array();
			var len = comboValues.length;
            
            if(len > 0){
    			for (var i=0; i<len; i++) {
    				var individual = comboValues[i];
    				if (individual['name'].trim() != 'omnivideo-shortcode' && individual['name'].trim() != 'omnivideo-submit' &&
    						individual['name'].trim() != 'omnivideo-cancel' && individual['value'].trim() != '') {
    					newValues.push(individual['name'] + "='" + omnivideoAdminHtmlEncode(decodeURIComponent(individual['value'].trim())) + "'");
    				}
    			}
    
    			var shortcode = "[omnivideo source='<?php echo $selected_tab; ?>' ";

    			len = newValues.length;
    			for (var i=0; i<len; i++) {
    				shortcode += newValues[i] + ' ';
    			}
    			shortcode += ']';

    			$j('#omnivideo-preview').text(shortcode);
    			$j('#omnivideo-shortcode').val(shortcode);
            }
            
		});
		$j('#omnivideo-shortcode-form select').change();

        
	});
</script>
<?php              
$fields = array(

	'youtube' => array(
		'name' => __('Youtube', 'omnivideo'),
		'fields' => array(
			array(
				'id' => 'username',
				'name' => __('Channel Name', 'omnivideo'),
				'type' => 'text',
				'hint' => __('Put your Youtube channel name on this field', 'omnivideo'),
			),
			array(
				'id' => 'type',
				'id' => 'type',
				'name' => __('Type', 'omnivideo'),
				'type' => 'select',
				'options' => array(
					'redirect' => __('Redirect', 'omnivideo'),
					'popup' => __('Popup', 'omnivideo'),
				),
				'hint' => __('Choose what media type you want to put on your post/page. Image means watch the video on Youtube page, video means watch the video on your site', 'omnivideo'),
			),
			array(
				'id' => 'result',
				'name' => __('Result', 'omnivideo'),
				'type' => 'text',
				'hint' => __('Set how many media item you want to display by putting a number here', 'omnivideo'),
			),
			array(
				'id' => 'column',
				'name' => __('Column', 'omnivideo'),
				'type' => 'select',
				'options' => array(
					'1' => __('1', 'omnivideo'),
					'2' => __('2', 'omnivideo'),
					'3' => __('3', 'omnivideo'),
					'4' => __('4', 'omnivideo'),
					'5' => __('5', 'omnivideo'),
					'6' => __('6', 'omnivideo'),
					'7' => __('7', 'omnivideo'),
					'8' => __('8', 'omnivideo'),
					'9' => __('9', 'omnivideo'),
				),
				'hint' => __('Set how the video(s) should be displayed in column. You can set how many column should be displayed.', 'omnivideo'),
			),
			array(
				'id' => 'description',
				'name' => __('Show Video Description', 'omnivideo'),
				'type' => 'checkbox',
				'std' => 'true',
				'hint' => __('Display the video description? (this option only work if you choose to displaying a video)', 'omnivideo'),
			),
		),
	),
	'vimeo' => array(
		'name' => __('Vimeo', 'omnivideo'),
		'fields' => array(
			array(
				'id' => 'username',
				'name' => __('UserName', 'omnivideo'),
				'type' => 'text',
				'hint' => __('Put your Vimeo user name on this field', 'omnivideo'),
			),
			array(
				'id' => 'type',
				'name' => __('Type', 'omnivideo'),
				'type' => 'select',
				'options' => array(
					'redirect' => __('Redirect', 'omnivideo'),
					'popup' => __('Popup', 'omnivideo'),
				),
				'hint' => __('Choose what media type you want to put on your post/page. Image means watch the video on Youtube page, video means watch the video on your site', 'omnivideo'),
			),
			array(
				'id' => 'result',
				'name' => __('Result', 'omnivideo'),
				'type' => 'text',
				'hint' => __('Set how many media item you want to display by putting a number here. <br/><b>Note:</b> In Vimeo, the result is generated per page, not item. Then, if you put result = 1 there are about 20 videos will be displayed', 'omnivideo'),
			),
			array(
				'id' => 'column',
				'name' => __('Column', 'omnivideo'),
				'type' => 'select',
				'options' => array(
					'1' => __('1', 'omnivideo'),
					'2' => __('2', 'omnivideo'),
					'3' => __('3', 'omnivideo'),
					'4' => __('4', 'omnivideo'),
					'5' => __('5', 'omnivideo'),
					'6' => __('6', 'omnivideo'),
					'7' => __('7', 'omnivideo'),
					'8' => __('8', 'omnivideo'),
					'9' => __('9', 'omnivideo'),
				),
				'hint' => __('Set how the video(s) should be displayed in column. You can set how many column should be displayed.', 'omnivideo'),
			),
			array(
				'id' => 'description',
				'name' => __('Show Video Description', 'omnivideo'),
				'type' => 'checkbox',
				'std' => 'true',
				'hint' => __('Display the video description? (this option only work if you choose to displaying a video)', 'omnivideo'),
			),
		),
	),
	'dailymotion' => array(
		'name' => __('Daily Motion', 'omnivideo'),
		'fields' => array(
			array(
				'id' => 'username',
				'name' => __('UserName', 'omnivideo'),
				'type' => 'text',
				'hint' => __('Put your Daily Motion user ID on this field', 'omnivideo'),
			),
			array(
				'id' => 'type',
				'name' => __('Type', 'omnivideo'),
				'type' => 'select',
				'options' => array(
					'redirect' => __('Redirect', 'omnivideo'),
					'popup' => __('Popup', 'omnivideo'),
				),
				'hint' => __('Choose what media type you want to put on your post/page. Image means watch the video on Youtube page, video means watch the video on your site', 'omnivideo'),
			),
			array(
				'id' => 'result',
				'name' => __('Result', 'omnivideo'),
				'type' => 'text',
				'hint' => __('Set how many media item you want to display by putting a number here', 'omnivideo'),
			),
			array(
				'id' => 'column',
				'name' => __('Column', 'omnivideo'),
				'type' => 'select',
				'options' => array(
					'1' => __('1', 'omnivideo'),
					'2' => __('2', 'omnivideo'),
					'3' => __('3', 'omnivideo'),
					'4' => __('4', 'omnivideo'),
					'5' => __('5', 'omnivideo'),
					'6' => __('6', 'omnivideo'),
					'7' => __('7', 'omnivideo'),
					'8' => __('8', 'omnivideo'),
					'9' => __('9', 'omnivideo'),
				),
				'hint' => __('Set how the video(s) should be displayed in column. You can set how many column should be displayed.', 'omnivideo'),
			),
			array(
				'id' => 'description',
				'name' => __('Show Video Description', 'omnivideo'),
				'type' => 'checkbox',
				'std' => 'true',
				'hint' => __('Display the video description? (this option only work if you choose to displaying a video)', 'omnivideo'),
			),
		),
	),

); /* fields end */

$tab_list = '';
$tab_fields = '';
$field_list = array();
$prelude = '';
foreach ($fields as $tab => $field_group) {
	$tab_list .= "<li><a href='".esc_url(add_query_arg(array('omnivideo-tab' => $tab)))."' class='".($tab == $selected_tab ? 'current' : '')."'>".esc_attr($field_group['name'])."</a>   </li>";
	if ($tab == $selected_tab) {
		$field_list = $field_group['fields'];
		$prelude = isset($field_group['prelude']) ? $field_group['prelude'] : '';
	}
}

echo "<form id='omnivideo-shortcode-form' method='post' action=''>";
echo "<ul class='subsubsub'>";
if (strlen($tab_list) > 8) {
	$tab_list = substr($tab_list, 0, -8);
}
echo $tab_list;
echo "</ul>";

if (!empty($prelude)) {
	echo "<p class='prelude'>".$prelude."</p>";
}

echo "<div class='omnivideo-form-wrapper'><table class='omnivideo-form'>";
echo "<tr>";"</tr>";
foreach ($field_list as $field) {
	echo "<tr>";
	echo "<th scope='row'>{$field['name']} ".(isset($field['req']) && $field['req'] ? '(*)' : '')." </th>";
	switch ($field['type']) {
		case 'text':
			echo "<td><input type='text' name='{$field['id']}' value='".(isset($field['std']) ? $field['std'] : '')."'/></td>";
			continue;
		case 'select':
			echo "<td><select name='{$field['id']}'>";
			foreach ($field['options'] as $option_name => $option_value) {
				echo "<option value='$option_name'>$option_value</option>";
			}
			echo "</select></td>";
			continue;
		case 'raw':
			echo "<td>".$field['std']."</td>";
			continue;
		case 'checkbox':
			echo "<td><input type='checkbox' name='{$field['id']}' value='true' ".checked($field['std'], true)."/></td>";
		continue;
	}
	echo "<td class='hint'>".(isset($field['hint']) ? $field['hint'] : '')."</td>";
	echo "</tr>";
}
echo "</table></div>";

echo "<div class='preview'>";
echo "<script type='text/javascript'></script>";
echo "<h4>".__('Shortcode preview', 'omnivideo')."</h4>";
echo "<pre class='html' id='omnivideo-preview' name='omnivideo-preview'></pre>";
echo "<input type='hidden' id='omnivideo-shortcode' name='omnivideo-shortcode' />";
echo "</div>";

echo "<div class='button-panel'>";
echo get_submit_button(__('Insert into post', 'omnivideo'), 'primary', 'omnivideo-submit', false);
echo get_submit_button(__('Cancel', 'omnivideo'), 'delete', 'omnivideo-cancel', false);
echo "</div>";
echo "</form>";
?>