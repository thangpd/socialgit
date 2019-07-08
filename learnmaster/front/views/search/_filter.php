<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
<?php foreach ($filters as $name => $filter):?>


    <div class="lema-widget">
        <div class="widget-title"><?php echo $filter['label']?></div>
        <div class="widget-content">
            <div class="content-item">
                <?php
                if ($filter['type'] == 'term' && !empty($filter['options'])):
                    ?>
                    <?php
                    $_filters = [];
                    foreach ($filter['options'] as $term) {
                        /** @var WP_Term $term */
                        $_filters[] = [
                            'label' => $term->name,
                            'name' => "courseFilter[$name][{$term->term_id}]",
                            'filter_id' => "course_filter_{$name}_{$term->term_id}",
                            'count' => $term->count
                        ];

                    }

                    ?>
                <?php else:?>
                    <?php
                    $_filters = [];
                    try {
                        foreach ($filter['options'] as $option) {
                            $_filters[] = [
                                'label' => $option['label'],
                                'name' => "courseFilter[$name][{$option['value']}",
                                'filter_id' => "course_filter_{$name}_{$option['value']}",
                                'count' => isset($option['count']) ? $option['count'] : ''
                            ];
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                    ?>
                <?php endif;?>
                <?php echo lema()->helpers->form->createFilters($_filters, false, $maxItems);?>
            </div>

        </div>
        <?php if (count($filter['options']) > $maxItems) :?>
            <div class="button-view">
                <a href="javascript:void(0)" class="view-more">VIEW MORE</a>
            </div>
        <?php endif;?>
    </div>

<?php endforeach;?>
