<?php
/**
 * @copyright © 2017 by Solazu Co.,LTDaihih
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<?php lema()->helpers->general->registerPjax('save-setting-email', 'div') ?>
    <div class="lema-setting">
        <?php if (isset($message)): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e($message, 'lema'); ?></p>
            </div>
        <?php endif; ?>
        <fieldset class="lema-setting-block">
            <legend><?php echo __('Email settings', 'lema') ?></legend>
            <form method="GET">
                <?php
                foreach ($_GET as $key => $value) {
                    if ($key !== 'template') {
                        echo("<input type='hidden' name='" . esc_html($key) . "' value='" . esc_html($value) . "'/>");
                    }
                }
                ?>
                <?php foreach ($fields as $field) : ?>
                    <div class="la-form-group">
                        <?php echo $field ?>
                    </div>

                <?php endforeach; ?>

            </form>
            <?php if (!empty($_GET['template'])) : ?>

                <?php $template = $_GET['template']; ?>
                <div class="lema-col lema-col-40">
                    <h3><?php echo esc_html__('Supported params', 'lema')?></h3>
                    <?php
                    $item = $mailList[$_GET['template']];
                    foreach ($item['supportedParams'] as $key => $param):
                    ?>
                        <div class="lema-list item">
                            <code>{<?php echo $key?>}</code> : <?php echo $param['label']?>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="lema-col lema-col-60">
                    <form method="POST">
                        <div class="la-form-group">
                            <textarea name="content_template" rows="20"
                                      class="la-form-control"><?php echo $mailTemplate ?></textarea>
                            <input type="hidden" name="name_template" value="<?php echo $template ?>">
                        </div>
                        <div class="la-form-group">
                            <button class="button button-primary" name="edit_email_template" type="submit">
                                <?php echo __('Save your changes', 'lema') ?>
                            </button>
                            <button class="button button-secondary" name="reset_template" type="submit">
                                <?php echo __('Reset to default', 'lema') ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </fieldset>

    </div>
<?php lema()->helpers->general->endPjax('div') ?>