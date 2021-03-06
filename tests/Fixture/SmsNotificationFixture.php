<?php
namespace NotificationWithPlivo\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SmsNotificationFixture
 *
 */
class SmsNotificationFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'sms_notification';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'notification_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'record_id' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'notification_id' => ['type' => 'index', 'columns' => ['notification_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'sms_notification_ibfk_1' => ['type' => 'foreign', 'columns' => ['notification_id'], 'references' => ['notifications', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'notification_id' => 1,
            'record_id' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
