<div class="lema-page-course-filter">

<form method="get" id="lema-search-form">
    <div class="lema-page-course-filter">
        <div class="container">
            <div class="lema-row">
                <div id="lema-filtered-filters" class="lema-course-filter-left">
                    <input type="hidden" name="category" value="<?php isset($courseFilter['category']) ? $courseFilter['category'] : ''?> " />
                   <!-- <ul name="courseFilter[category]" class="lema-cat-tree">
                        <?php /*foreach ($categories as $category):*/?>
                            <li class="level1" value="<?php /*echo $category->term_id*/?>"><span><?php /*echo $category->name*/?></span></li>
                            <?php /*if (!empty($category->children)) :*/?>
                                <?php /*foreach ($category->children as $cat) : */?>
                                    <li class="level2" value="<?php /*echo $cat->term_id*/?>"><span><?php /*echo $cat->name*/?></span></li>
                                    <?php /*if (!empty($cat->children)) :*/?>
                                        <?php /*foreach ($cat->children as $subcat) :*/?>
                                            <li class="level3" value="<?php /*echo $subcat->term_id*/?>">
                                                <span><?php /*echo $subcat->name*/?></span>
                                            </li>
                                        <?php /*endforeach;*/?>
                                    <?php /*endif;*/?>
                                <?php /*endforeach;*/?>
                            <?php /*endif;*/?>
                        <?php /*endforeach;*/?>
                    </ul>-->
                    <?php echo $context->render('_filter', [
                            'filters' => $filters,
                            'courseFilter' => $courseFilter,
                            'maxItems' => $maxItems
                        ])?>

                </div>


                <div class="lema-course-filter-right">
                    <div id="lema-filtered-courses">
                        <?php echo $context->render('_result', [
                            'courseFilter' => $courseFilter,
                            'sortables' => $sortables,
                            'sorttypes' => $sorttypes
                        ])?>
                    </div>

                </div>


            </div>
        </div>
    </div>
</form>
</div>