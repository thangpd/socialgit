<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\helpers\validators;


use lema\core\interfaces\ValidatorInterface;
use lema\core\Validator;

class FileValidator extends Validator implements ValidatorInterface
{
    /**
     * Allowed file extensions
     * @var array
     */
    public $ext = [
        'jpg', 'png', 'bmp'
    ];

    /**
     * Maximum of upload file
     * @var int
     */
    public $maxSize = 0;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        if ($this->maxSize == 0) {
            $this->maxSize = $this->fileUploadMaxSize();
        }
    }

    public function validate()
    {

    }

    function fileUploadMaxSize() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $max_size = $this->parseSize(ini_get('post_max_size'));

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}