<?php 
if ( !isset($object_id) ) {
    $object_id = $data['object_id'];
}
?>
<a role="button" data-modal='modal-review'>
    <i class="fa fa-star"></i> <?php echo esc_html($data['text_rating']); ?>
</a>
<div class='wrapper lema-modal-container'>
    <div class='lema-modal' id="modal-review">
        <div class="lema-page-advanced-search">
            <div class="title-advanced-search">Reviewing</div>
            <div class="lema-columns lema-column-4">
                <form lema-ajax data-html="lema-rating-sc-<?php echo $object_id?>">
                    <div class="lema-modal-content">
                        <input type="hidden" name="action" value="lema_post_rating">
                        <input type="hidden" name="style" value="<?php echo $data['style']?>">
                        <input type="hidden" name="object_id" value="<?php echo $object_id; ?>">
                        <?php echo $context->render( '_stars', [ 'data' => $data, 'status' => $status ]); ?>
                        <textarea disabled name="comment" cols="3" rows="5"></textarea>
                    </div>
                    <div class="button-wrapper">
                            <button disabled type="submit">
                                <i class="fa fa-star fa-fw"></i> Review
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>