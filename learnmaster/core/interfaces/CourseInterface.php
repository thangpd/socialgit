<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/24/17.
 */


namespace lema\core\interfaces;


interface CourseInterface extends ModelInterface
{
    /**
     * @param CourseCategoryInterface $cat
     * @return CourseInterface[]
     */
    public function findCoursesByCategory(CourseCategoryInterface $cat);

    /**
     * @param CourseCategoryInterface[]
     * @return CourseInterface[]
     */
    public function findCoursesByCategories($cats);
}