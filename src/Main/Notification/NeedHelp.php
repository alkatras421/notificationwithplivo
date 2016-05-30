<?php
namespace NotificationWithPlivo\Main\Notification;

use Cake\ORM\TableRegistry;

Class NeedHelp
{
    public function __construct() {
        
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notification');
        $this->notif_email = TableRegistry::get('email_notification');
    }

    public function delHelp($ndata)
    {
        if($ndata['transport']=='sms')
        {
            $query = $this->notif_sms->query();
            $query->delete()
              ->where(['notification_id' => $ndata['id']])
              ->execute();
        }

        elseif($ndata['transport']=='email')
        {
            $query = $this->notif_email->query();
            $query->delete()
                  ->where(['notification_id' => $ndata['id']])
                  ->execute();
        }
            
        $id = $this->notification->get($ndata['id']);
        $this->notification->delete($id);
    }
    public function recursiveHelp($list)
    {
        $interval = new \DateInterval($list['recursive']);
        $date = new \DateTime($list['date']);
        $result_email = $this->notif_email->newEntity();
        $result_sms = $this->notif_sms->newEntity();
        $recursiveEntity = $this->notification->newEntity([
                'transport' => $list['transport'],
                'text' => $list['text'],
                'date'=> $date->add($interval),
                'address' => $list['address'],
                'recursive' => $list['recursive'],
                'sender' => $list['sender'],
                'status' => 'expect'
                ]);
        $this->notification->save($recursiveEntity);
        
        if($list['transport']=='sms')
        {
            $result_sms->notification_id = $recursiveEntity->id;
            $this->notif_sms->save($result_sms);
        }
        elseif($list['transport']=='email'){
            
            $query = $this->notif_email->find();
            $query -> select();
            $query -> where(['notification_id'=> $list['id']]);
            
            $row = $query->first();
            $result_email->notification_id = $recursiveEntity->id;
            $result_email->subject = $row->subject;
            $result_email->sender_name = $row->sender_name;
            $this->notif_email->save($result_email);
        }
    }
    public function manipulationHelp($query,$param)
    {
        foreach($query as $list)
        {
            if(key_exists('status', $param))
            {
                $list->status = $param['status'];
            }
            elseif($list['status'] == 'expect')
            {
                $list->status = 'cancel';
            }
            elseif($list['status'] == 'cancel')
            {
                $list->status = 'expect';
            }
            if(($list['date'] < new \DateTime('now')) and (key_exists('date',$param)))
            {
                $list->date = $param['date'];
            }
            else{
                $list->date = new \DateTime('+1 day');
            }
            $this->notification->save($list);
        }
    }
    
    public function queryFindHelp($param)
    {
        if(key_exists('transport', $param))
        {
            if((!key_exists('status', $param)) and (!key_exists('date', $param)) and ((!key_exists('rangeBegin', $param)) and (!key_exists('rangeEnd', $param))))
            {
                $query = $this->notification->find()
                        ->hydrate(false)
                        ->select()
                        ->where(['transport' => $param['transport']])
                        ->toArray();
                return $query;
            }
            elseif(key_exists('status', $param))
            {
                $query = $this->notification->find()
                        ->hydrate(false)
                        ->select()
                        ->where(['status'=> $param['status']])
                        ->andWhere(['transport' => $param['transport']])
                        ->toArray();
                return $query;
            }
            elseif((key_exists('rangeBegin', $param)) and (key_exists('rangeEnd', $param)))
            {
                $query = $this->notification->find()
                        ->hydrate(false)
                        ->select()
                        ->where(function ($exp) use ($param){
                        return $exp
                                ->between('date', $param['rangeBegin'], $param['rangeEnd']);
                        })
                        ->andWhere(['transport' => $param['transport']])
                        ->toArray();
                return $query;

            }
            elseif(key_exists('date', $param))
            {
                $query = $this->notification->find()
                        ->hydrate(false)
                        ->select()
                        ->where(function ($exp) use ($param){
                                return $exp
                                        ->lte('date',$param['date']);
                        })
                        ->andWhere(['transport' => $param['transport']])
                        ->toArray();
                return $query;
            }
        }
        else{
            throw new NotFoundException('The \'transport\' is incorrectly specified.');
        }
        
    }
    
    public function checkUnHelp($list,$response_detail)
    {
        $id = $this->notification->get($list['id']);

        $id->status = $response_detail['response']['message_state'];
        $this->notification->save($id); 
    } 
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

