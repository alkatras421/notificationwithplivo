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
    public function index()
    {
        $this->loadComponent('NotificationWithPlivo.NotifyAPI');
        $paramE = (['transport' => 'email']);
        $e = $this->NotifyAPI->showBase($paramE);
        $paramS = (['transport' => 'sms']);
        $s = $this->NotifyAPI->showBase($paramS);
        $this->set('email', $e);
        $this->set('sms', $s);
        $param = array('id' => '3', 'subject' => 'checking');
        $this->NotifyAPI->editNotification($param);
        #$this->NotifyAPI->round();
        $this->NotifyAPI->updateStatus();
    }
    
    public function send()
    {
       $this->loadComponent('NotificationWithPlivo.NotifyAPI');
       $param = array ('date' => '2016-05-17');
       $this->NotifyAPI->deleteNotification($param);
    }
    
    public function add()
    {
        $this->loadComponent('NotificationWithPlivo.NotifyAPI');
        
        $params = array(
            'transport' => 'email',
            'text' => 'sad',
            'subject' => 'work?',
            'address' => 'alkatras421@mail.ru',
            'sender' => 'alkatras421@gmail.ru'
        );
        
        $this->NotifyAPI->addToBase($params);
    }
    
    
}
