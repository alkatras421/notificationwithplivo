<?php
namespace NotificationWithPlivo\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use NotificationWithPlivo\Model\Table\NotificationsTable;

/**
 * NotificationWithPlivo\Model\Table\NotificationsTable Test Case
 */
class NotificationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \NotificationWithPlivo\Model\Table\NotificationsTable
     */
    public $Notifications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.notification_with_plivo.notifications',
        'plugin.notification_with_plivo.email_notification',
        'plugin.notification_with_plivo.sms_notification'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Notifications') ? [] : ['className' => 'NotificationWithPlivo\Model\Table\NotificationsTable'];
        $this->Notifications = TableRegistry::get('Notifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Notifications);

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
}
