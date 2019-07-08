<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\helpers;


use AdamWathan\Form\Elements\FormControl;
use lema\core\BaseObject;
use lema\core\components\Form;
use lema\core\interfaces\HelperInterface;
use lema\core\interfaces\ModelInterface;
use lema\core\Model;
use lema\helpers\form\FormElement;

class FormHelper extends BaseObject implements HelperInterface
{
    /** @var FormElement[] */
    private $fields = [];

    /**
     * Generate form elements
     * @param $attrs
     * @param Form $form
     * @return FormElement[]
     */
    public function generateFormElement(ModelInterface $model, Form $form)
    {
        /** @var Model $model */
        $attrs = $model->attributes;
        $fields = [];
        foreach ($attrs as $attr => $params) {
            $value = $model->getAttribtueValue($attr);
            $formParams = @$params['form'];
            if (!empty($formParams)) {
                if (!empty($value) && $value != 'null') {
                    $formParams['value'] = $value;
	                $formParams['selected'] = $value;
	                $formParams['checked'] = $value;
                }
                $field = $form->field($attr, $formParams);
                $fields[$attr] = $field;
            }
        }
        $this->fields = $fields;
        return  $this->fields;
    }

    /**
     * @param $fieldName
     * @return FormElement
     */
    public function getField($fieldName)
    {
        if (isset($this->fields[$fieldName])) {
            return $this->fields[$fieldName];
        }
    }


    /**
     * @param $array
     * @param $name
     * @param bool $template
     * @return string
     */
    public function createFilters($array, $template = false, $maxItems = 5)
    {
        if ($template === false) {
            $template = <<<EOF
<label class="expect">
                                        {input}
                                        <span class="text">{label}</span>
                                        <span class="number">({count})</span>
                                    </label>
EOF;
        }
        $result = '';
        $courseFilter = isset($_GET) ? $_GET : [];
        $i = 0;
        foreach ($array as $filter) {
            /*if ($filter['count'] == 0) {
                continue;
            }*/
            $label = $filter['label'];
            $name = $filter['name'];
            $input = "<input type='checkbox' name='{$filter['name']}' data-filter='{$filter['filter_id']}' value='1' " . (lema()->helpers->general->arrayIndexSearch($name, $courseFilter) ? ' checked ': '') . "/>";
            $filterCheckbox = str_replace('{input}', $input, $template);
            $filterCheckbox = str_replace('{label}', $label, $filterCheckbox);
            $filterCheckbox = str_replace('{count}', isset($filter['count']) ? $filter['count'] : '', $filterCheckbox);
            $result .= $filterCheckbox;
            $i++;
            if ($i == $maxItems) {
                $result .= "<div class='lema-view-more-button lema-hide'>";
            }

        }
        if ($i >= $maxItems) {
            $result .= '</div>';
        }
        return $result;
    }

}