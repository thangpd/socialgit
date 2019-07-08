<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 

namespace lema\core\interfaces;


interface CourseTagInterface extends ModelInterface
{
    /**
     * Get list of course related to this tag
     * @return CourseInterface[]
     */
    public function getCourses();

}