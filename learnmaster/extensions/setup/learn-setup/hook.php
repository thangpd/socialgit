<?php
require 'config.php';
require 'helper.php';

//page template
if ( is_admin() ) {
    add_action('init', 'learnsetup_create_template_page');
}
add_filter( 'page_template', 'learnsetup_page_template' );
