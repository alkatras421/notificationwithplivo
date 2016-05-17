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
        $this->notif_sms = TableRegistry::get('sms_notif');
        $this->notif_email = TableRegistry::get('email_notif');
        
        $this->help = new NeedHelp();
    }
    
    public function multipleEmail($list)
    {
        $query = $this->notif_email->find()
            -> select()
            -> where(['id_notif'=> $list['id']])
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
                                         'stat' => 'delivered'
                ]);
            $this->notification->save($result_notif);
            $result_email = $this->notif_email->newEntity(['id_notif' => $result_notif->id,
                                                           'theme'=> $query['theme'],
                                                           'subject'=>$query['subject']]);
            $this->notif_email->save($result_email); 
            
            $param = array(
                'sender' => $list['sender'],
                'name' => $query['subject'],
                'to' => $dst,
                'theme' => $query['theme'],
                'text'=> $list['text']);
            $this->sendEM($param);
        }
    }
    
    public function sendEM($param)
    {
        $email = new Email('default');
        $email->from([$param['sender'] => $param['name']])
              ->to($param['to'])
              ->subject($param['theme'])
              ->send($param['text']);
        
        return $email;    
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

