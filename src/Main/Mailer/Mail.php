<?php
namespace NotificationWithPlivo\Main\Mailer;


use Cake\Mailer\Email;
use NotificationWithPlivo\Main\Notification\NeedHelp;
use Cake\ORM\TableRegistry;

Class Mail 
{
    public function __construct() 
    {
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notification');
        $this->notif_email = TableRegistry::get('email_notification');
        
        $this->help = new NeedHelp();
    }
    
    public function multipleEmail($list)
    {
        $query = $this->notif_email->find()
            -> select()
            -> where(['notification_id'=> $list['id']])
            -> first();
        
        $this->help->delHelp($list);
 
        $address = explode(">", $list['address']);
        
        
        foreach($address as $dst)
        {
            $result_notif = $this->notification->newEntity(['transport'=> $list['transport'],
                                         'text' => $list['text'],
                                         'address' => $dst,
                                         'sender' => $list['sender'],
                                         'date' => $list['date'],
                                         'recursive' => $list['recursive'],
                                         'status' => 'delivered'
                ]);
            $this->notification->save($result_notif);
            $result_email = $this->notif_email->newEntity(['notification_id' => $result_notif->id,
                                                           'subject'=> $query['subject'],
                                                           'sender_name'=>$query['sender_name']]);
            $this->notif_email->save($result_email); 
            
            $param = array(
                'sender' => $list['sender'],
                'name' => $query['sender_name'],
                'to' => $dst,
                'theme' => $query['subject'],
                'text'=> $list['text']);
            $this->sendEM($param);
        }
    }
    
    public function sendEM($param)
    {
        $email = new Email('default');
        $email->from([$param['sender'] => $param['name']])
              ->to($param['to'])
              ->subject($param['subject'])
              ->send($param['text']);
        
        return $email;    
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

