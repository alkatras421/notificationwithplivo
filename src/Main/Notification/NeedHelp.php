<?php
namespace NotificationWithPlivo\Main\Notification;

use Cake\ORM\TableRegistry;

Class NeedHelp
{
    public function __construct() {
        
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notif');
        $this->notif_email = TableRegistry::get('email_notif');
    }

    public function delHelp($ndata)
    {
        if($ndata['transport']=='sms')
            {
                $query = $this->notif_sms->query();
                $query->delete()
                  ->where(['id_notif' => $ndata['id']])
                  ->execute();
            }

            elseif($ndata['transport']=='email')
            {
                $query = $this->notif_email->query();
                $query->delete()
                      ->where(['id_notif' => $ndata['id']])
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
                'stat' => 'expect'
                ]);
        $this->notification->save($recursiveEntity);
        
        if($list['transport']=='sms')
        {
            $result_sms->id_notif = $recursiveEntity->id;
            $this->notif_sms->save($result_sms);
        }
        elseif($list['transport']=='email'){
            
            $query = $this->notif_email->find();
            $query -> select();
            $query -> where(['id_notif'=> $list['id']]);
            
            $row = $query->first();
            $result_email->id_notif = $recursiveEntity->id;
            $result_email->theme = $row->theme;
            $result_email->subject = $row->subject;
            $this->notif_email->save($result_email);
        }
    }
    public function manipulationHelp($query,$param)
    {
        foreach($query as $list)
        {
            if(key_exists('stat', $param))
            {
                $list->stat = $param['stat'];
            }
            elseif($list['stat'] == 'expect')
            {
                $list->stat = 'cancel';
            }
            elseif($list['stat'] == 'cancel')
            {
                $list->stat = 'expect';
            }
            if(($list['date'] < new \DateTime('now')) and (key_exists('datetime',$param)))
            {
                $list->date = $param['datetime'];
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
            if((!key_exists('stat', $param)) and (!key_exists('date', $param)) and ((!key_exists('rangeBegin', $param)) and (!key_exists('rangeEnd', $param))))
            {
                $query = $this->notification->find()
                        ->hydrate(false)
                        ->select()
                        ->where(['transport' => $param['transport']])
                        ->toArray();
                return $query;
            }
            elseif(key_exists('stat', $param))
            {
                $query = $this->notification->find()
                        ->hydrate(false)
                        ->select()
                        ->where(['stat'=> $param['stat']])
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
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

