<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$items = @$control->params['value'];
?>
<label class="lb-text">Choose Audio from library</label>
<div class="la-form-group lema-button-add-audio <?php if ( isset( $items ) && ! empty( $items ) && ( is_object( $items ) || is_array( $items ) ) ) {
	echo count( $items ) ? ' lema-hide ' : '';
} ?> ">
    <button type="button" class="button-secondary button flat modal-button add-media-button" data-type="audio"
            data-callback="updateAudioList" data-multiple="true" data-modal="modal-id-03">
        <i class="fa fa-plus"></i> Add from library
    </button>
</div>
<?php if ( ! empty( $item ) ): ?>
    <input type="hidden" id="la-content-audio-resource-value" name="Lesson[audio]"
           value='<?php echo json_encode( $item ) ?>'>
<?php endif; ?>
<div id="audios-container">
	<?php
	if ( ! empty( $items ) ) {
		$item = json_decode( $items, true );
		?>
        <div class="la-content-video-resource" id="<?php echo $item['id'] ?>">
            <div class="inner">
                <div class="audio-thumb">
                    <div class="la-block-detail audio-content active">
                        <div class="la-element-icon">
                            <span class="la-icon"></span>
                        </div>
                    </div>
                </div>
                <div class="video-desc">
                    <h4 class="file-name"><?php echo $item['filename'] ?></h4>
                    <span class="length"><b>Length:</b> <span
                                class="videoLength"><?php echo $item['fileLength'] ?></span></span>
                </div>
                <span class="btn-remove remove-video"></span>
            </div>
        </div>
		<?php
	}

	?>
</div>