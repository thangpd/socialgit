<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\core\components;



use lema\core\BaseObject;
use lema\core\interfaces\CacheableInterface;
use lema\core\interfaces\ComponentInterface;
use lema\core\interfaces\ModelInterface;
use lema\models\CourseModel;


class ModelManager extends BaseObject implements ComponentInterface, CacheableInterface
{


    /**
     * ModelManager constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $models = $this->getAllModels();
        $models = apply_filters('lema_models', $models);
        foreach ($models as $model) {
            /** @var ModelInterface $model */
            $config = $model::getPosttypeConfig();

            if (isset($config['post'])) {
                /**
                 * Register post type
                 */
                lema()->wp->register_post_type($config['post']['name'], $config['post']['args']);
                /**
                 * Register action for this post type
                 */
                $modelInstance = $model::getInstance();
                lema()->wp->add_action('save_post_' . $config['post']['name'], [$modelInstance, 'afterSave']);
                if (method_exists($modelInstance, 'beforeDelete')) {
                    add_action( 'before_delete_post', [$modelInstance, 'beforeDelete'], 10 );
                }

                /* if (method_exists($modelInstance, 'beforeSave')) {
                     lema()->wp->add_action("");
                 }*/

            }
            if (isset($config['taxonomy'])) {
                /**
                 * Register taxonomy
                 */
                lema()->wp->register_taxonomy($config['taxonomy']['name'], $config['taxonomy']['object_type'], $config['taxonomy']['args']);

            }
            if (isset($config['actions'])) {
                foreach ($config['actions'] as $name => $callback) {
                    lema()->wp->add_action($name, $callback);
                }
            }
            if (isset($config['ajax'])) {
                foreach ($config['ajax'] as $name => $callback) {
                    lema()->wp->add_action("wp_ajax_{$name}", $callback);
                    lema()->wp->add_action("wp_ajax_nopriv_{$name}", $callback);
                }
            }
            if (isset($config['meta'])) {

            }

        }
        lema()->hook->registerHook(Hook::LEMA_MODEL_READY, [$this, 'modelReady']);
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

    }


    /**
     * Scan and index all model object
     * @return array|mixed|null
     */
    public function getAllModels(){
        $models = lema()->cache->get($this->getCahename(), []);
        if (empty($models) || LEMA_DEBUG) {
            $models = lema()->helpers->file->findAllClass('lema', 'Model', ModelInterface::class);
            lema()->cache->set($this->getCahename(), $models);
        }
        return $models;

    }

    /**
     * @return void
     */
    public function modelReady(){

    }



    /**
     * If this object able to cache, it needs provider owner cache name
     * @return mixed
     */
    public function getCahename()
    {
        return "models_manager";
    }

    /**
     * Flush owner cache to refresh data
     * @return mixed
     */
    public function flushCache()
    {
        lema()->cache->delete($this->getCahename());
    }
}