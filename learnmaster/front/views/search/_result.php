<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<?php if ( isset( $courseFilter['q'] ) && ! empty( $courseFilter['q'] ) ): ?>
	<?php $courseFilter['q'] = esc_html( $courseFilter['q'] ) ?>
    <div class="block-title">
        <h2><?php echo __( "Result(s) found for \"<strong>{$courseFilter['q']}</strong>\"", 'lema' ) ?></h2> <a
                href="javascript:void(0);" id="lema-clear-search-term"> <i
                    class="fa fa-close"></i> <?php echo __( 'Clear', 'lema' ) ?></a>
        <div class="clear"></div>
    </div>
<?php endif; ?>

<!--ACTIVE FILTER-->

<div class="lema-active-filter">
<!---->
<!--	--><?php
	if ( ! empty( $_GET ) ):
//	?>
       <div class="title-active-filter">
<?php echo esc_html__('Active Filters:','lema'); ?>
        </div>
<?php
//			/*
//		$_GET
//		Array
//			(
//			[cat_course] => 489,478,459
//			[tag_course] => 536,533
//			[level_course] => 529
//			[language_course] => 526
//		    [courseFilter]=> action
//			)
//		*/
		$default_lema_term=[
			'cat_course' =>  __('Category', 'lema'),
			'tag_course' =>  __('Topic', 'lema'),
			'level_course' => __('Level', 'lema'),
			'language_course' => __('Language', 'lema')
		];
		$_filters = [];
		foreach ( $_GET as $name => $id ):
			if ( in_array( $name, array_keys($default_lema_term) ) ) {
				foreach ( explode( ",", $id ) as $value ) {
					$term = get_term( $value, $name );

					$_filters[] = [
						'label'     => $term->name,
						'name'      => "courseFilter[$name][{$term->term_id}]",
						'filter_id' => "course_filter_{$name}_{$term->term_id}",
					];
				}
			}
			?>
<!--		--><?php
		endforeach;
//		?>
<!--		--><?php
	$template = <<<EOF
<label class="expect">
                                        <span class="text">{label}</span>
                                    </label>
                                        {input}
EOF;
        $maxItems=!empty($maxItems)?$maxItems:'';
		echo lema()->helpers->form->createFilters( $_filters, $template, $maxItems );
	endif;
	?>
</div>
<div class="lema-filter-course">
    <div class="lema-filter-nav">
        <div class="lema-filter-category">
			<?php
			foreach ( $context->course_type as $k => $value ) {
				?>
                <a href=""><?php echo esc_html( $value ); ?></a>
				<?php
			}
			?>
        </div>
        <div class="lema-filter-price">
        </div>
        <div class="view-mode-wrap">
            <p class="view-mode">
                <input type="radio" name="_layout" value="list" id="lema-course-mode-list">
                <label class="list-view <?php echo( isset( $courseFilter['_layout'] ) && $courseFilter['_layout'] == 'list' ? ' active' : '' ) ?>"
                       for="lema-course-mode-list"
                       title="List view">
                </label>
                <input type="radio" name="_layout" value="grid" id="lema-course-mode-grid">
                <label class="grid-view <?php echo( isset( $courseFilter['_layout'] ) && $courseFilter['_layout'] == 'grid' ? ' active' : '' ) ?>"
                       title="Grid view" for="lema-course-mode-grid"></label>

            </p>
        </div>
        <div class="lema-filter-sort">
            <div class="view-sort">
                <div class="lema-title-sort"><?php echo __( 'Sort By', 'lema' ); ?></div>
                <select id="lema-shortables-list" name="sort_by" class="lema-list-sort">
                    <option><?php echo __( 'None', 'lema' ); ?></option>
					<?php foreach ( $sortables as $key => $value ) : ?>
                        <option <?php echo ( isset( $courseFilter['sort_by'] ) && $courseFilter['sort_by'] == $key ) ? ' selected ' : '' ?>
                                value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
                </select>
            </div>

        </div>
    </div>
</div>
<?php /*if (!empty($courseFilter)) :

else :*/ ?><!--
    <div class="lema-message error">
        <?php /*echo esc_html__('No result found', 'lema') */ ?>
    </div>
--><?php /*endif; */ ?>
<?php
$attrs = [];
foreach ( $courseFilter as $key => $value ) {
	$attrs[] = " {$key}=\"" . ( is_array( $value ) ? implode( ',', array_keys( $value ) ) : $value ) . "\"";
}
$attrs  = implode( ' ', $attrs );
$config = "summary='1' cols_on_row='2' _posts_per_page='6' ";
$config = apply_filters( 'lema_courselist_search_page_add_attr', $config );
echo lema_do_shortcode( "[lema_courselist_filtered {$config} {$attrs}]" );
?>
