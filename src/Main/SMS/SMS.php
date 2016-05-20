<?php
namespace NotificationWithPlivo\Main\SMS;

use Cake\ORM\TableRegistry;
use Plivo\RestAPI;
use NotificationWithPlivo\Main\Notification\NeedHelp;
use Cake\Core\Configure;


Class SMS 
{   
    #* * * * * cd path/to/project && bin/cake NotificationWithPlivo.Round
    public $plivoInst;
    
    public function __construct() 
    {
        Configure::load('PlivoConfig');
        $plivo = Configure::read('Plivo');
        $this->plivoInst = new RestAPI($plivo['auth_id'], $plivo['auth_token']);
        
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notification');
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
                $result_notif->status = $response_detail['response']['message_state'];
                
                $this->notification->save($result_notif);
                $result_sms->record_id = $record['record_id'];
                $result_sms->notification_id = $result_notif->id;
                $this->notif_sms->save($result_sms);
            }
        }
        else{
            $response_send = $this->plivoInst->send_message($params);
            $record = array('record_id'=> $response_send['response']['message_uuid'][0]);
            $response_detail = $this->plivoInst->get_message($record);

            $query = $this->notif_sms->find();
            $result = $query->select()
                  ->where(['notification_id' => $list['id']])->first();
            $result->record_id = $response_send['response']['message_uuid'][0];
            
            $id = $this->notification->get($list['id']);
            $id->status = $response_detail['response']['message_state'];
            
            $this->notification->save($id);
            $this->notif_sms->save($result);
        }
    }
    
    public function sendSingleSMS($param)
    {
        $send = array('dst' => $param['address'], 'src' => $param['sender'], 'text' => $param['text']);
        $response = $this->plivoInst->send_message($send);
        if(key_exists('error', $response['response']))
        {
            throw new \Exception($response['response']['error']);
        }
        else{
            return $response;
        }
    }
    
    public function upStatus()
    {
        
        $query = $this->notification->find();
        $query->select();
        $query->where(function ($exp) {
            return $exp
                    ->lte('date', new \DateTime('now',  new \DateTimeZone('Asia/Novosibirsk')));
        }); 
        
        foreach($query as $list) 
        {
            if ($list['status'] == 'sent')
            {
                $interval = date_diff(new \DateTime('now',  new \DateTimeZone('Asia/Novosibirsk')), $list['date']);
                
                if($interval->d >= 3)
                {
                    $id = $this->notification->get($list['id']);
                    $id->status = 'unavailable';
                    $this->notification->save($id);
                }
                else{
                    $record_query = $this->notif_sms->find()
                                -> select()
                                -> where(['notification_id'=> $list['id']])
                                ->first();
                
                    $param = array ('record_id' => $record_query->record_id);
                    $response_detail = $this->plivoInst->get_message($param);
                    $id = $this->notification->get($list['id']);

                    $id->status = $response_detail['response']['message_state'];
                    $this->notification->save($id); 
                }
            }
        }
    }
    
    public function checkUn()
    {
        $query = $this->notification->find()
        ->select()
        ->where(['status' => 'unavailable'])
        ->andWhere(function ($exp) {
            return $exp
                    ->lte('date', new \DateTime('now',  new \DateTimeZone('Asia/Novosibirsk')));
        }); 
        
        foreach($query as $list) 
        {
            $record_query = $this->notif_sms->find()
                        -> select()
                        -> where(['notification_id'=> $list['id']])
                        ->first();

            $param = array ('record_id' => $record_query->record_id);
            $response_detail = $this->plivoInst->get_message($param);
            
            if($response_detail['response']['message_state'] == 'delivered')
            {
                $id = $this->notification->get($list['id']);

                $id->status = $response_detail['response']['message_state'];
                $this->notification->save($id); 
            }
            
            elseif($response_detail['response']['message_state'] == 'undelivered')
            {
                $id = $this->notification->get($list['id']);

                $id->status = $response_detail['response']['message_state'];
                $this->notification->save($id); 
            }
        
        }
    }
    
    public function checkUnavailable()
    {
        $query = $this->notification->find()
        ->select()
        ->where(['status' => 'unavailable'])
        ->andWhere(function ($exp) {
            return $exp
                    ->lte('date', new \DateTime('now',  new \DateTimeZone('Asia/Novosibirsk')));
        }); 
        
        foreach($query as $list) 
        {
            $record_query = $this->notif_sms->find()
                        -> select()
                        -> where(['notification_id'=> $list['id']])
                        ->first();

            $param = array ('record_id' => $record_query->record_id);
            $response_detail = $this->plivoInst->get_message($param);
            
            if($response_detail['response']['message_state'] == 'delivered')
            {
                $this->help->checkUnHelp($list,$response_detail);
            }
            
            elseif($response_detail['response']['message_state'] == 'undelivered')
            {
                $this->help->checkUnHelp($list,$response_detail);
            }
        }
    }
}
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

