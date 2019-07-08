<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$questions = \lema\models\QuestionModel::findAll();
 ?>
    <div class="tablenav top filter-bar">
        <div class="alignleft actions bulkactions">
            <div class="la-form-group">
                <label for="categories-id" class="screen-reader-text">Select categories</label>
                <select disabled name="action" class="la-select2 la-categories-select" id="categories-id" multiple="multiple">
                    <option value="all">All Categories</option>
                    <option value="edit">History</option>
                    <option value="edit">chemistry</option>
                    <option value="trash">Physics</option>
                    <option value="trash">English</option>
                    <option value="trash">Biology</option>
                    <option value="trash">Math</option>
                </select>
            </div>
        </div>
        <div class="alignleft actions">
            <div class="la-form-group">
                <label for="filter-by-tag" class="screen-reader-text">Filter by tags</label>
                <select disabled class="la-select2 la-tags-select" name="m" multiple="multiple" id="filter-by-tag">
                    <option value="0">All Tags</option>
                    <option value="Standard course">Standard course</option>
                    <option value="NodeJs">NodeJs</option>
                    <option value="ReactJs">ReactJs</option>
                    <option value="HTML &amp; CSS">HTML &amp; CSS</option>
                </select>
            </div>
            <!-- <input type="button" name="filter_action" id="post-query-submit" class="button" value="Filter">  -->
        </div>
        <button type="button" disabled class="button-secondary flat button">Filter</button>
        <br class="clear">
    </div>
    <!-- course yable -->
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column">
                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                <input id="cb-select-all-1" type="checkbox">
            </td>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                <a href="#"><span>Title</span><span class="sorting-indicator"></span></a>
            </th>
            <th scope="col" id="author" class="manage-column column-author">Categories</th>
            <th scope="col" id="date" class="manage-column column-date sortable asc">
                <a href="#"><span>Tags</span><span class="sorting-indicator"></span></a>
            </th>
        </tr>
        </thead>
        <tbody id="the-list">
        <?php foreach ($questions as $question):?>
        <tr id="question-id-<?php echo $question->ID?>" class="iedit author-self level-0 course-1 type-page status-publish hentry">
            <th scope="row" class="check-column">
                <label class="screen-reader-text" for="cb-select-2999">Select Activity</label>
                <input id="cb-question-<?php echo $question->ID?>" type="checkbox" name="questions[]" value="<?php echo $question->ID?>">
                <div class="locked-indicator">
                    <span class="locked-indicator-icon" aria-hidden="true"></span>
                    <span class="screen-reader-text">“Activity” is locked</span>
                </div>
            </th>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span>
                </div>
                <strong><a class="row-title" href="#" aria-label="“Activity” (Edit)"><?php echo lema()->helpers->general->subString($question->post_content, 30)?></a></strong>

            </td>
            <td class="author column-author" data-colname="Author"><a href="#">none</a>
            </td>
            <td class="date column-date" data-colname="tags">

            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td class="manage-column column-cb check-column">
                <label class="screen-reader-text" for="cb-select-all-2">Select All</label>
                <input id="cb-select-all-2" type="checkbox">
            </td>
            <th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Title</span><span class="sorting-indicator"></span></a>
            </th>
            <th scope="col" class="manage-column column-author">Categories</th>
            <th scope="col" class="manage-column column-date sortable asc"><a href="#"><span>Tags</span><span class="sorting-indicator"></span></a>
            </th>
        </tr>
        </tfoot>
    </table>
    <div class="tablenav bottom">

        <div class="alignleft actions">
        </div>
        <div class="tablenav-pages"><span class="displaying-num">24 items</span>
            <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                                            <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                                            <span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 of <span class="total-pages">2</span></span>
                                            </span>
                                            <a class="next-page" href="#"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                                            <span class="tablenav-pages-navspan" aria-hidden="true">»</span></span>
        </div>
        <br class="clear">
    </div>
