<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


$template_category_html = array(
	'list' => [
		'general'        => '<div class="column-left col-xs-12">
								<div class="item">
									<div class="lema-widget">%1$s</div></div></div>',
		'title'          => '<h3 class="widget-title">%1$s</h3>',
		'body'           => '<div class="widget-content">
                <div class="content-item">%1$s</div></div>',
		'viewmore'       => '<div class="button-view">
                    			<a href="javascript:void(0)" class="view-more">' . __( "VIEW MORE", "lema" ) . '</a>
                			</div>',
		'template_field' => '<label class="expect">
                                {input}
                                <span class="text">{label}</span>
                                <span class="number">({count})</span>
                            </label>',
	],
	'grid' => [
		'general'        => '<div class="column-left col-md-3 col-sm-6">
                        <div class="item">%1$s</div></div></div>',
		'title'          => '<div class="list-selector dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span>%1$s</span>
							<span class="fa fa-angle-down">
						</span></a>
						<div class="lema-widget dropdown-menu">
                            <h3 class="widget-title">%1$s</h3>',
		'body'           => '<div class="widget-content">%1$s</div></div>',
		'viewmore'       => '<div class="button-view">
                    			<a href="javascript:void(0)" class="view-more">' . __( "VIEW MORE", "lema" ) . '</a>
                			</div>',
		'template_field' => '<label class="expect">
								{input}
								<span class="text">{label}</span>
								<span class="number">({count})</span>
						</label>
                            ',
	],
);
$res_html               = '';
$layout                 = ! empty( $data['filter_layout'] ) ? esc_attr( $data['filter_layout'] ) : 'list';

?>


<div class="lema-course-filter-left <?php echo $layout ?>">
    <div id="lema-filtered-filters">

        <input type="hidden" name="category" value="<?php isset( $data['category'] ) ? $data['category'] : '' ?> "/>

		<?php
		if ( ! empty( $_GET ) && $layout == 'grid' ):?>

            <!--ACTIVE FILTER-->
            <div class="lema-active-filter">
                <div class="title-active-filter">
					<?php echo esc_html__( 'Active Filters:', 'lema' ); ?>
                </div>
				<?php

				$_filters = [];
				foreach ( $_GET as $name => $id ):
					if ( in_array( $name, array_keys( $context->defaultTerms ) ) ) {
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
				//
				?>
                <!--		--><?php
				$template = <<<EOF
			<label class="expect">{input}<span class="text">{label}</span></label>	
EOF;
				$maxItems = ! empty( $maxItems ) ? $maxItems : '';
				echo lema()->helpers->form->createFilters( $_filters, $template, $maxItems );
				?>
            </div>
		<?php endif; ?>

		<?php foreach ( $context->filters as $name => $filter ): ?>
			<?php
			if ( $filter['type'] == 'term' && ! empty( $filter['options'] ) ):
				$_filters = [];
				foreach ( $filter['options'] as $term ) {
					/** @var WP_Term $term */
					$_filters[] = [
						'label'     => $term->name,
						'name'      => "courseFilter[$name][{$term->term_id}]",
						'filter_id' => "course_filter_{$name}_{$term->term_id}",
						'count'     => $term->count
					];

				}
			else:
				$_filters = [];
				try {
					foreach ( $filter['options'] as $option ) {
						$_filters[] = [
							'label'     => $option['label'],
							'name'      => "courseFilter[$name][{$option['value']}",
							'filter_id' => "course_filter_{$name}_{$option['value']}",
							'count'     => isset( $option['count'] ) ? $option['count'] : ''
						];
					}
				} catch ( \Exception $e ) {
					continue;
				}
			endif;
			$title          = sprintf( $template_category_html[ $layout ]['title'], esc_html( $filter['label'] ) );
			$template_field = lema()->helpers->form->createFilters( $_filters, $template_category_html[ $layout ]['template_field'], $maxItems );
			if ( count( $filter['options'] ) > $maxItems ) :
				$template_field .= $template_category_html[ $layout ]['viewmore'];
			endif;
			$body = sprintf( $template_category_html[ $layout ]['body'], $template_field );

			$res_html .= sprintf( $template_category_html[ $layout ]['general'], $title . $body );
		endforeach;
		echo '<div class="row">' . $res_html . '</div>';
		?>

    </div>
</div>