<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\models;


use lema\core\CustomModel;
use lema\core\interfaces\CacheableInterface;
use lema\core\interfaces\MigrableInterface;
use lema\core\interfaces\ModelInterface;

use lema\core\Model;

/**
 * @property integer $id
 * @property string $type
 * @property integer $object_id
 * @property integer $user_id
 * @property integer $rate
 * @property string $created_date
 */
class RatingModel extends CustomModel implements ModelInterface, CacheableInterface, MigrableInterface
{
    const CACHE_PREFIX          = 'lema_rating_';
    const TABLE_NAME            = 'lema_rating';
    /**
     * If this object able to cache, it needs provider owner cache name
     * @return mixed
     */
    public function getCahename()
    {
        return self::CACHE_PREFIX . $this->id;
    }

    /**
     * Flush owner cache to refresh data
     * @return mixed
     */
    public function flushCache()
    {
        // TODO: Implement flushCache() method.
    }

    /**
     * Run this function when plugin was activated
     * We need create something like data table, data roles, caps etc..
     * @return mixed
     */
    public function onActivate()
    {
        global $wpdb;
        $tableName =  $this->getName();
        if ( !in_array($tableName, $wpdb->tables)) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $tableName (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `type` varchar(20) NOT NULL,
                     `object_id` int(11) NOT NULL,
                     `user_id` int(11) DEFAULT NULL,
                     `rate` int(11) NOT NULL DEFAULT '0',
                     `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                     `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                     PRIMARY KEY (`id`)
                ) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Run this function when plugin was deactivated
	 * We need clear all things when we leave.
	 * Please be a polite man!
	 * @return mixed
	 */
	public function onDeactivate() {

	}

	/**
	 * Run if current version need to be upgraded
	 *
	 * @param string $currentVersion
	 *
	 * @return mixed
	 */
	public function onUpgrade( $currentVersion ) {
		if ( version_compare( $currentVersion, '1.0.1', '<' ) ) {

		}
	}

	/**
	 * @return mixed
	 * example :
	 * return [
	 *    'name' => [
	 *        'label' => 'Name',
	 *        'validate' => ['text', ['length' => 200, 'required' => true, 'message' => 'Please enter a valid name']]
	 *    ],
	 *    'age' => [
	 *        'label' => 'Age',
	 *        'validate' => ['number', ['max' => 100, 'min' => 0, 'message' => 'Please enter a valid age']]
	 *    ]
	 * ]
	 */
	public function getAttributes() {
		return [
			'id'           => [
				'label' => 'Rating ID'
			],
			'type'         => [
				'label' => 'Type'
			],
			'object_id'    => [
				'lable' => 'Object ID'
			],
			'user_id'      => [
				'label' => 'User Id'
			],
			'rate'         => [
				'label' => 'Rated value'
			],
			'comment'      => [
				'label' => 'Comment'
			],
			'created_date' => [
				'label' => 'Created Date'
			]
		];
	}

	/**
	 * Abstract function get name of table/model
	 * @return mixed
	 */
	public function getName() {
		return self::getTableName();
	}

	/**
	 * Get table name of this model
	 * @return string
	 */
	public static function getTableName() {
		global $wpdb;

		return $wpdb->prefix . self::TABLE_NAME;
	}


	/**
	 * @param $object_id
	 * @param $type
	 * @param $mode
	 */
	public static function clearCache( $object_id, $type, $mode ) {
		$cacheName = self::CACHE_PREFIX . $type . '_' . $object_id . '_' . $mode;
		lema()->cache->delete( $cacheName );
	}

	/**
	 * Get all rating status of an object
	 *
	 * @param $type
	 * @param $object_id
	 * @param string $mode
	 *
	 * @return object [
	 *      'total' => NUMBER,
	 *      'avg'   => NUMBER
	 * ]
	 */
	public static function getRateStatus( $type = 'course', $object_id, $mode = 'simple' ) {
		global $wpdb;
		$tableName = self::getTableName();
		$cacheName = self::CACHE_PREFIX . $type . '_' . md5( serialize( $object_id ) ) . '_' . $mode;

		$status = lema()->cache->get( $cacheName );
		if ( empty( $status ) || LEMA_DEBUG ) {
			if ( $type == 'course' ) {
				if ( $mode == 'simple' ) {
					if ( ! is_array( $object_id ) ) {
						$result = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(a.id) as total, (SUM(a.rate)/COUNT(a.id)) as `avg` FROM {$tableName} a inner join {$wpdb->users} u on a.user_id=u.ID WHERE a.type = %s AND a.object_id = %d", $type, $object_id ) );
					} else {
						$ids    = implode( ',', $object_id );
						$result = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(a.id) as total, (SUM(a.rate)/COUNT(a.id)) as `avg` FROM {$tableName} a inner join {$wpdb->users} u on a.user_id=u.ID WHERE a.type = %s AND a.object_id IN ({$ids})", $type ) );
					}
				} else {
					$startQuery = [];
					for ( $i = 1; $i <= 5; $i ++ ) {
						$startQuery[] = sprintf( '(SELECT COUNT(rate%4$s.id) FROM %1$s rate%4$s inner join %5$s u on rate%4$s.user_id=u.ID WHERE rate%4$s.type = \'%2$s\' AND rate%4$s.object_id = %3$s AND rate%4$s.rate = %4$s) as rate%4$s', $tableName, $type, $object_id, $i, $wpdb->users );
					}
					$startQuery = implode( ',', $startQuery );
					$result     = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(a.id) as total, (SUM(a.rate)/COUNT(a.id)) as `avg`, {$startQuery} FROM {$tableName} a inner join {$wpdb->users} u on a.user_id=u.ID WHERE a.type = %s AND a.object_id = %d", $type, $object_id ) );
				}
				$result = apply_filters( 'lema_rating_stats', $result, $type, $object_id );
				if ( ! empty( $result ) ) {
					$result = array_shift( $result );
					lema()->cache->set( self::CACHE_PREFIX . $type . '_' . md5( json_encode( $object_id ) ) . '_' . $mode, $result );

					return $result;
				}
			} else {
				$status = (object) [ 'total' => 0, 'avg' => 0 ];
			}
		}

		return $status;
	}

	/**
	 * @param $objectId
	 *
	 * @return array|null|object
	 */
	public static function getAllReviews( $objectId ) {
		global $wpdb;
		$table         = $wpdb->prefix . self::TABLE_NAME;
		$list_comments = $wpdb->get_results( $wpdb->prepare( "SELECT a.* FROM {$table} a inner join {$wpdb->users} u on a.user_id=u.ID  WHERE object_id = %d ", $objectId ), OBJECT );

		return $list_comments;
	}


	/**
	 * Delete a object by primary key
	 *
	 * @param $id
	 *
	 * @return boolean
	 */
	public function delete() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLE_NAME;
		if ( isset( $this->id ) ) {
			$deleted = $wpdb->delete( $table, array( 'id' => $this->id ), array( '%d' ) );
		} else {
			$deleted = false;
		}

		return $deleted;
	}

	/**
	 * @return mixed
	 */
	public static function getPosttypeConfig() {
		lema()->hook->listenHook( 'lema_rating_refresh', [ self::className(), 'clearCache' ], 10, 3 );

		return [];
	}


	/**
	 * Override current behavior after model was saved
	 *
	 * @param $postId
	 *
	 * @return mixed
	 */
	public function afterSave( $postId, $post = null, $update = false ) {
		return false;
	}


	/**
	 * @param int $limit
	 * @param string $type
	 *
	 * @return array|null|object
	 */
	public static function getTopRating( $limit = 10, $type = 'course' ) {
		global $wpdb;
		$table = self::getTableName();
		$query = $wpdb->prepare( "SELECT a.object_id, sum(a.rate)/count(a.id) total FROM {$table} a inner join {$wpdb->users} u on a.user_id=u.ID WHERE a.type=%s GROUP BY a.object_id ORDER BY a.total DESC LIMIT %d", $type, $limit );

		return $wpdb->get_results( $query );
	}

	/**
	 * check status course
	 *
	 * */

	public function checkStatus() {
		global $wpdb;
		$objectId = $this->object_id;
		$userId   = $this->user_id;
		$table    = self::getTableName();
		$query    = $wpdb->prepare( "SELECT * FROM {$table} a WHERE a.object_id=%d and a.user_id=%d", $objectId, $userId );
		$row      = $wpdb->get_row( $query );
		if ( isset( $row ) ) {
			$this->isNew = false;
			$this->id    = $row->id;
		}

		return true;
	}

	/**
	 * Run when learn master was uninstalled
	 * @return mixed
	 */
	public function onUninstall() {
		global $wpdb;
		$tableName = $this->getName();
		try {
			$wpdb->query( "DROP TABLE {$tableName}" );
		} catch ( \Exception $e ) {

		}
	}
}