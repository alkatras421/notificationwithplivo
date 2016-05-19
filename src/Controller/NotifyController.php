<?php
namespace NotificationWithPlivo\Controller;

use NotificationWithPlivo\Controller\AppController;

/**
 * Notify Controller
 *
 * @property \NotificationWithPlivo\Model\Table\NotifyTable $Notify
 */
class NotifyController extends AppController
{   
    public function initialize()
    {
       parent::initialize();
       $this->loadComponent('NotificationWithPlivo.NotifyAPI'); // Include the FlashComponent
    }
    
    public function index()
    {}
    
    public function sms()
    {
        $paramS = (['transport' => 'sms']);
        $s = $this->NotifyAPI->showBase($paramS);
        $this->set('sms', $s);
    }
    
    public function email()
    {
        $paramE = (['transport' => 'email']);
        $e = $this->NotifyAPI->showBase($paramE);
        $this->set('email', $e);
    }
}
