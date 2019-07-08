<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


?>
<div class="lema-course-filter-right <?php echo ! empty( $data['filter_layout'] ) ? esc_attr( $data['filter_layout'] ) : 'list'; ?>">
    <div id="lema-filtered-courses">

		<?php
		if ( ! empty( $_GET ) && $data['filter_layout'] == 'list' ):?>
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
					<label class="expect">
						{input}
						<span class="text">{label}</span>
					</label>			
EOF;
				$maxItems = ! empty( $maxItems ) ? $maxItems : '';
				echo lema()->helpers->form->createFilters( $_filters, $template, $maxItems );
				?>
            </div>
		<?php endif;
		?>

        <div class="lema-filter-course">
            <div class="lema-filter-nav">
                <div class="lema-filter-category">
					    <select id="lema-type-list" name="cptype">
							<?php
							
							
							foreach ( $context->course_type as $k => $value ) :
								 $active = $data['cptype'] == $k ? 'active' : '';
								 if($data['cptype']==$k){
									 $cptype=$k;
								 }
								 ?>
                                <option <?php echo ( isset( $data['cptype'] ) && $data['cptype'] == $k ) ? ' selected ' : '' ?>
                                        value="<?php echo $k ?>"><?php echo $value ?></option>
							<?php endforeach; ?>
                        </select>
						<div class="subfilter">
							<?php

							foreach ( $context->filter_type as $key => $val ) :
								if ( $data['cptype'] == $cptype && $data['subfilter'] == $key ) {
									$active = 'active';
								} else {
									$active = '';
								}
								?>
								<a href="" class="<?php echo $active; ?>"
									data-name="subfilter"
									data-name_type="cptype"
									data-value_type="<?php echo esc_attr($cptype ) ?>"
									data-value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $val ); ?></a>
								<?php
							endforeach;
							?>
						</div>
                </div>
                <div class="lema-filter-sort">
                    <div class="view-sort">
                        <span class="lema-title-sort"><?php echo __( 'Sort By', 'lema' ); ?></span>
                        <select id="lema-shortables-list" name="sort_by" class="lema-list-sort">
                            <option selected><?php echo __( 'None', 'lema' ); ?></option>
							<?php foreach ( $context->sortables as $key => $value ) : ?>
                                <option <?php echo ( isset( $data['sort_by'] ) && $data['sort_by'] == $key ) ? ' selected ' : '' ?>
                                        value="<?php echo $key ?>"><?php echo $value ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>

                    <div class="view-mode-wrap">
                        <p class="view-mode">
                            <input type="radio" name="layout" value="list" id="lema-course-mode-list">
                            <label class="list-view <?php echo( isset( $data['layout'] ) && $data['layout'] == 'list' ? ' active' : '' ) ?>"
                                   for="lema-course-mode-list"
                                   title="List view">
                            </label>
                            <input type="radio" name="layout" value="grid" id="lema-course-mode-grid">
                            <label class="grid-view <?php echo( isset( $data['layout'] ) && $data['layout'] == 'grid' ? ' active' : '' ) ?>"
                                   title="Grid view" for="lema-course-mode-grid"></label>
                        </p>
                    </div>
                </div>
            </div>
        </div>
		<?php
		$context->doShortcodeFilter( $data );
		?>
    </div>
</div>