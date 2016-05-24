<?php
namespace NotificationWithPlivo\Controller;

use NotificationWithPlivo\Controller\AppController;
use Cake\ORM\TableRegistry;

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
       $this->loadComponent('NotificationWithPlivo.NotifyAPI');
       $this->notification = TableRegistry::get('notifications');
       $this->notif_sms = TableRegistry::get('sms_notification');
       $this->notif_email = TableRegistry::get('email_notification');
    }
    
    public function index()
    {
        $query = $this->notification->find('all')->contain([
            'EmailNotification' => function ($query) {
                return $query->find('all');
                }
        ]);
        foreach ($query as $user) {
            echo $user;
       }
    }
    
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
        var_dump($this->request->data());
        if($this->request->is('post'))
        {   
                if($this->request->data()['searcher'] == 'id')
                {
                    $queryN = $this->notification->find()
                        ->hydrate(false)
                        ->Where(['transport'=> 'email'])    
                        ->andWhere([$this->request->data()['searcher'].' LIKE' => $this->request->data()['Search']])
                        ->toArray();
                }
                else{
                    $queryN = $this->notification->find()
                        ->hydrate(false)
                        ->Where(['transport'=> 'email'])    
                        ->andWhere([$this->request->data()['searcher'].' LIKE' => '%'.$this->request->data()['Search'].'%'])
                        ->toArray();
                }
                foreach($queryN as $notif)
                {
                    $queryE = $this->notif_email->find()
                        ->hydrate(false)
                        ->Where(['notification_id'=> $notif['id']])
                        ->toArray();
                    foreach($queryE as $arr_id=>$sub_array)
                    {
                        $queryN[$arr_id] = array_merge($queryN[$arr_id], $sub_array);   
                    }
                }
                $this->set('email', $queryN);
                
        }
       
    }
}
