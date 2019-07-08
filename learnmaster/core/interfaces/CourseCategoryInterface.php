<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/24/17.
 */


namespace lema\core\interfaces;


interface CourseCategoryInterface extends ModelInterface
{
    /**
     * Get list of course related to this category
     * @return CourseInterface[]
     */
    public function getCourses();
}