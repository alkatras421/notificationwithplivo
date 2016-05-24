<?php
namespace NotificationWithPlivo\Controller;

use NotificationWithPlivo\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * Notifications Controller
 *
 * @property \NotificationWithPlivo\Model\Table\NotificationsTable $Notifications
 */
class NotificationsController extends AppController
{
public function initialize()
    {
       parent::initialize();
       $this->loadComponent('NotificationWithPlivo.NotifyAPI');
       $this->notification_sms = TableRegistry::get('sms_notification');
       $this->notification_email = TableRegistry::get('email_notification');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
       
    }

    /**
     * View method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function email()
    {
        $e = $this->Notifications->find()
                        ->Where(['transport'=> 'email'])  
                        ->contain([
                            'EmailNotification' => function ($query) {
                                return $query->find('all');
                            }
                        ]);           
        $this->set('email', $e);
     
        if($this->request->is('post'))
        {   
                if($this->request->data()['searcher'] == 'id')
                {
                    $queryN = $this->Notifications->find()
                        ->Where(['transport'=> 'email'])  
                        ->contain([
                            'EmailNotification' => function ($query) {
                                return $query->find('all');
                            }
                        ])
                        ->andWhere([$this->request->data()['searcher'].' LIKE' => $this->request->data()['Search']]);
                }
                else{
                    $queryN = $this->Notifications->find()
                        ->contain([
                            'EmailNotification' => function ($query) {
                                return $query->find('all');
                            }
                        ])
                        ->Where(['transport'=> 'email'])    
                        ->andWhere([$this->request->data()['searcher'].' LIKE' => '%'.$this->request->data()['Search'].'%']);
                }
         
                $this->set('email', $queryN);
                
        }
       
    }

    public function sms()
    {
        $s = $this->Notifications->find()
            ->Where(['transport'=> 'sms'])  
            ->contain([
                'SmsNotification' => function ($query) {
                    return $query->find('all');
                }
            ]);  
           
        $this->set('sms', $s);
        
        if($this->request->is('post'))
        {   
                if($this->request->data()['searcher'] == 'id')
                {
                    $queryN = $this->Notifications->find()
                        ->Where(['transport'=> 'sms'])  
                        ->contain([
                            'SmsNotification' => function ($query) {
                                return $query->find('all');
                            }
                        ])
                        ->andWhere([$this->request->data()['searcher'].' LIKE' => $this->request->data()['Search']]);
                }
                else{
                    $queryN = $this->Notifications->find()
                        ->contain([
                            'SmsNotification' => function ($query) {
                                return $query->find('all');
                            }
                        ])
                        ->Where(['transport'=> 'sms'])    
                        ->andWhere([$this->request->data()['searcher'].' LIKE' => '%'.$this->request->data()['Search'].'%']);
                }
         
                $this->set('sms', $queryN);
                
        }
        
    }
}
