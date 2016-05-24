<?php
namespace NotificationWithPlivo\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use NotificationWithPlivo\Model\Table\EmailNotificationTable;

/**
 * NotificationWithPlivo\Model\Table\EmailNotificationTable Test Case
 */
class EmailNotificationTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \NotificationWithPlivo\Model\Table\EmailNotificationTable
     */
    public $EmailNotification;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.notification_with_plivo.email_notification',
        'plugin.notification_with_plivo.notifications'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmailNotification') ? [] : ['className' => 'NotificationWithPlivo\Model\Table\EmailNotificationTable'];
        $this->EmailNotification = TableRegistry::get('EmailNotification', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmailNotification);

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
