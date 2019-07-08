<div class="lema-sc-course-filter">

    <form method="get" id="lema-search-form">
        <input type="hidden" name="data" value="<?php echo urlencode( json_encode( $data ) ) ?>">
        <div class="lema-page-course-filter">
            <div class="lema-row">
                <?php echo $context->render( '_filter', [
                    'data'     => $data,
                    'maxItems' => $maxItems
                ] ) ?>
                <?php echo $context->render( '_result', [
                    'data' => $data,
                ] ) ?>
            </div>
        </div>
    </form>
</div>