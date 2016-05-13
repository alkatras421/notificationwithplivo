<?php
namespace NotificationWithPlivo\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use NotificationWithPlivo\Controller\Component\NotifyAPIComponent;

/**
 * NotificationWithPlivo\Controller\Component\NotifyAPIComponent Test Case
 */
class NotifyAPIComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \NotificationWithPlivo\Controller\Component\NotifyAPIComponent
     */
    public $NotifyAPI;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->NotifyAPI = new NotifyAPIComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NotifyAPI);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
