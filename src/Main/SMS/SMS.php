<?php
namespace NotificationWithPlivo\Main\SMS;

use Cake\ORM\TableRegistry;
use Plivo\RestAPI;
use NotificationWithPlivo\Main\Notification\NeedHelp;
use Symfony\Component\Yaml\Yaml;


Class SMS 
{   
    #* * * * * cd /var/www/html/Notifiations_plugin && bin/cake NotificationWithPlivo.Round
    public $plivoInst;
    
    public function __construct() 
    {
        $plivo = Yaml::parse(file_get_contents(dirname(__FILE__).'/../../../config/PlivoConf.yml'));
        $this->plivoInst = new RestAPI($plivo['auth_id'], $plivo['auth_token']);
        
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notif');
        $this->help = new NeedHelp(); 
    }
    
    public function getDSM($param)
    {
        $response = $this->plivoInst->get_message($param);
        return $response;
    }
    
    public function getDAM()
    {
        $response = $this->plivoInst->get_messages();
        return $response;
    }
    
    public function multipleSMS($params,$list)
    {
        if(stripos($params['dst'], '>'))
        {
            $this->help->delHelp($list);
            
            $address = explode(">", $params['dst']);
            foreach($address as $dst)
            {
                $params['dst'] = $dst;
                $response_send = $this->plivoInst->send_message($params);
                $result_sms = $this->notif_sms->newEntity();
                $result_notif = $this->notification->newEntity(['transport'=> $list['transport'],
                                             'text' => $list['text'],
                                             'address' => $dst,
                                             'sender' => $list['sender'],
                                             'date' => $list['date'],
                                             'recursive' => $list['recursive']
                    ]);
                
                $record = array('record_id'=> $response_send['response']['message_uuid'][0]);
                $response_detail = $this->plivoInst->get_message($record);
                $result_notif->stat = $response_detail['response']['message_state'];
                
                $this->notification->save($result_notif);
                $result_sms->record_id = $record['record_id'];
                $result_sms->id_notif = $result_notif->id;
                $this->notif_sms->save($result_sms);
            }
        }
        else{
            $response_send = $this->plivoInst->send_message($params);
            $record = array('record_id'=> $response_send['response']['message_uuid'][0]);
            $response_detail = $this->plivoInst->get_message($record);

            $query = $this->notif_sms->find();
            $result = $query->select()
                  ->where(['id_notif' => $list['id']])->first();
            $result->record_id = $response_send['response']['message_uuid'][0];
            
            $id = $this->notification->get($list['id']);
            $id->stat = $response_detail['response']['message_state'];
            
            $this->notification->save($id);
            $this->notif_sms->save($result);
        }
    }
    
    public function sendSingleSMS($param)
    {
        $response = $this->plivoInst->send_message($param);
        return $response;
    }
    
    public function upStat()
    {
        
        $query = $this->notification->find();
        $query->select();
        $query->where(function ($exp) {
            return $exp
                    ->lte('date', new \DateTime('now',  new \DateTimeZone('Asia/Novosibirsk')));
        }); 
        
        foreach($query as $list) 
        {
            if ($list['stat'] == 'sent')
            {
                $interval = date_diff(new \DateTime('now',  new \DateTimeZone('Asia/Novosibirsk')), $list['date']);
                
                if($interval->d >= 3)
                {
                    $id = $this->notification->get($list['id']);
                    $id->stat = 'error';
                }
                else{
                    $record_query = $this->notif_sms->find()
                                -> select()
                                -> where(['id_notif'=> $list['id']])
                                ->first();
                
                    $param = array ('record_id' => $record_query->record_id);
                    $response_detail = $this->plivoInst->get_message($param);
                    $id = $this->notification->get($list['id']);

                    $id->stat = $response_detail['response']['message_state'];
                    $this->notification->save($id); 
                }
            }
        }
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

