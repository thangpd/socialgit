<?php
if ( isset($tab) && isset($tab_list) ) {
	foreach ($tab_list as $key => $obj) {
		?>
		<li><a data-tab="<?php echo esc_attr($key) ?>" data-url_key="tab" data-url_value="<?php echo esc_attr($key) ?>" class="tab-link <?php echo $tab == $key ? 'active' : ''?>"><?php echo esc_attr($obj['name']) ?></a></li>
		<?php
	}
}
?>
