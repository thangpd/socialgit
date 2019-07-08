<?php
//slz_print( $course );
//slz_print( $bundle );
$total_price = 0;
?>

<div id="lema-bundle-items">
    <div class="la-form-group">


        <table id="bundle-table" class="lema-bundle-item">
            <tr>
                <th width="80%">Item</th>
                <th width="10%">Price</th>
                <!--<th width="10%">Total</th>-->
                <th width="20%">Del</th>
            </tr>
			<?php
			foreach ( $bundle_item as $item_id ):
				$course_meta = get_post_meta( $item_id );
				$res_price = 0;
				if ( isset( $course_meta['course_sale_price'][0] ) || $course_meta['course_price'][0] ) {
					$res_price = isset( $course_meta['course_sale_price'][0] ) ? $course_meta['course_sale_price'][0] : isset( $course_meta['course_price'][0] ) ? $course_meta['course_price'][0] : $res_price;
				}

				?>
                <tr>

                    <td>
						<?php
						echo esc_html( get_the_title( $item_id ) );
						echo '<div style="display:none">'.$item_id.'</div>';
						?>
                    </td>
                    <td>

						<?php
						$total_price += $res_price;

						/*echo lema()->helpers->general->currencyFormat( $course_meta['course_sale_price'][0] );*/
						echo esc_html( $res_price );
						?>
                    </td>
                    <td>
						<?php if ( isset( $res_price ) ): ?>
                            <a class="bundle_ajax" data-course_id="<?php echo esc_html( $item_id ); ?>"
                               data-action="lema_bundle_remove_item"
                               data-price="<?php echo esc_html( $res_price ); ?>" href=""><i
                                        class="fa fa-close"></i></a>
						<?php endif; ?>
                    </td>
                </tr>

			<?php
			endforeach;
			?>
        </table>
        <div>
            <h3><?php echo esc_html__( 'Total: ', 'lema-software' ) ?><span
                        class="totalprice"><?php echo esc_html( $total_price ); ?></span>
                <input type="hidden" id="total_price" name="total_price" value="<?php echo $total_price ?>"/>
            </h3>
            <h3><label for="lema_sale_price"><?php echo __( 'Sale Price' ); ?></label></h3>
			<?php echo( '<input type="number" id="lema_sale_price" name="lema_sale_price" value="' . $lema_sale_price . '" />' ); ?>
        </div>
    </div>
    <div class="lema-course-list">
        <select id="courselist" ">
		<?php
		foreach ( $course as $cour ) :
			?>
            <option data-price="<?php echo esc_attr( $cour->course_sale_price ); ?>"
                    value="<?php echo $cour->post->ID; ?>"><?php echo $cour->post->post_title ?></option>
			<?php
		endforeach;
		?>
        </select>
        <button data-action="lema_bundle_add_item" class="add-this-course">Add this course</button>
    </div>
    <div class="la-form-group">
        <label><?php echo __( 'Instructor', 'lema' ) ?></label>
        <select name="instructors[]" multiple="multiple" data-select2-ajax data-action="lema_search_instructor"
                class="select2 la-form-control">
			<?php
			if ( ! empty( $instructors ) ):
				foreach ( $instructors as $id => $name ):?>
                    <option value="<?php echo $id ?>" selected="selected"><?php echo $name ?></option>
				<?php endforeach;
			endif;
			?>
        </select>
    </div>
    <div class="la-form-group">
        <label><?php echo __( 'Is Best Selling?', 'lema' );

			?> <input type="checkbox" name="best_selling" <?php echo $best_selling == 'on' ? 'checked' : ''; ?>></label>

    </div>

</div>
