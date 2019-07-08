<?php
function learnsetup_list_template_page() {
    //config page template
    return array(
        'list_page_template' => array(
            'page-learnmaster-setup'
        ),
        'page-learnmaster-setup' =>
            array('information' => array(
                'post_type'   => 'page',
                'post_title'  => 'Page Setups',
                'post_name'   => 'page-learnmaster-setup',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_parent' => ''
            )
            )
    );
}

function learnsetup_create_template_page() {
    $cfg = learnsetup_list_template_page();
    $list_template_page = $cfg['list_page_template'];
    if ( !empty($list_template_page) ) {
        for ($i=0; $i<count($list_template_page); $i++) {
            $page_template_name = $list_template_page[$i];
            $page_acccount_template = $cfg[$page_template_name];
            if ( !empty($page_acccount_template) ) {
                $post_page = $page_acccount_template['information']['post_name'];
                $id = get_page_by_path($post_page);
                $page =$page_acccount_template['information'];
                if (!isset($id)) {
                    $id = wp_insert_post($page);
                }
            }
        }
    }
}

function learnsetup_page_template( $page_template )
{
    $cfg_setup = get_cf_learn_settup();
    $cfg = learnsetup_list_template_page();
    $list_template_page = $cfg['list_page_template'];
    if ( is_page( $list_template_page ) ) {
        $pagename = get_query_var('pagename');
        $path_file = DIR_LEARN_SETUP. '/'.$cfg_setup['slug'].'/templates/'.$pagename.'.php';
        if (file_exists($path_file ))
            $page_template = $path_file ;
    }
    return $page_template;
}
