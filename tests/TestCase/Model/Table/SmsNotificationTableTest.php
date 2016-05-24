<?php
namespace NotificationWithPlivo\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use NotificationWithPlivo\Model\Table\SmsNotificationTable;

/**
 * NotificationWithPlivo\Model\Table\SmsNotificationTable Test Case
 */
class SmsNotificationTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \NotificationWithPlivo\Model\Table\SmsNotificationTable
     */
    public $SmsNotification;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.notification_with_plivo.sms_notification',
        'plugin.notification_with_plivo.notifications',
        'plugin.notification_with_plivo.email_notification',
        'plugin.notification_with_plivo.records'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SmsNotification') ? [] : ['className' => 'NotificationWithPlivo\Model\Table\SmsNotificationTable'];
        $this->SmsNotification = TableRegistry::get('SmsNotification', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SmsNotification);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
