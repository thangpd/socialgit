<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

?>
<div class="lemabox alert">
    <div class="title">
        <form method="post">
            <h3><?php echo __('You are currently in progress to remove Learn Master plugin', 'lema')?></h3>
            <p>
                <?php echo __('Do you want to keep plugin data?', 'lema')?>
            </p>
            <p>
                <?php echo __('Note  : you <strong>can not</strong> undo if you choose delete', 'lema')?>
            </p>
            <p>
                <button type="submit" class="btn btn-primary button-secondary" name="keep">Keep Learn Master data</button>
                <button type="submit" class="btn btn-danger button-primary button-danger" name="delete">Delete All</button>
            </p>
        </form>
    </div>
</div>
