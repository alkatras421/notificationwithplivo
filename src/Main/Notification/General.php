<?php
namespace NotificationWithPlivo\Main\Notification;

use NotificationWithPlivo\Main\Mailer\Mail;
use NotificationWithPlivo\Main\SMS\SMS;
use NotificationWithPlivo\Main\Notification\NeedHelp;
use Migrations\Migrations;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
 
Class General
{
    public function __construct() 
    {
        $this->SMS = new SMS();
        $this->email = new Mail();     
        $this->help = new NeedHelp();
        
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notification');
        $this->notif_email = TableRegistry::get('email_notification');
        
    }
   
    public function addTB($param)
    {
        
        $result_email = $this->notif_email->newEntity();
        $result_sms = $this->notif_sms->newEntity();
        $result_notif = $this->notification->newEntity(['transport'=> $param['transport'],
                                             'text' => $param['text'],
                                             'address' => $param['address'],
                                             'sender' => $param['sender'],
                                             'status' => 'expect',
                                             
            ]);
        
        if(key_exists('date',$param))
        {
            $result_notif->date = $param['date'];
        }
        else{
            $result_notif->date = new \DateTime('now', new \DateTimeZone('Asia/Novosibirsk'));
        }
        if((key_exists('recursive',$param))) 
        {
            $result_notif->recursive = $param['recursive'];
        }
        else{
            $result_notif->recursive = NULL;
        }
        if((key_exists('transport', $param)) and ($param['transport'] == 'email' || $param['transport'] == 'sms'))
        {
            if(($param['transport'] == 'email') and (key_exists('subject',$param)) and (preg_match('/.+@.+\..+/i', $param['address'])))
            {
                $result_email->subject = $param['subject'];
                
                if(key_exists('sender_name',$param))
                {
                    $result_email->sender_name = $param['sender_name'];
                }
                else{
                    $result_email->sender_name = $param['sender'];
                }
                
                $this->notification->save($result_notif);
                $result_email->notification_id = $result_notif->id;
                $this->notif_email->save($result_email);
                return $result_notif->id;               
            }
            elseif(($param['transport'] == 'email') and (!key_exists('subject',$param)))
            {
                throw new NotFoundException('The \'subject\' is not exist');
            }
            elseif(($param['transport'] == 'email') and (!preg_match('/.+@.+\..+/i', $param['address'])))
            {
                throw new \Exception('The \'address\' is incorrectly specified');
            }
            elseif(($param['transport'] == 'sms') and preg_match('^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$^',$param['address']))
            {
                $this->notification->save($result_notif);
                $result_sms->notification_id = $result_notif->id;
                $this->notif_sms->save($result_sms);
                return $result_notif->id;
            }
            else{
                throw new \Exception('The \'address\' is incorrectly specified');
            }
        }
        else{
            throw new NotFoundException('The \'transport\' is incorrectly specified.');
        }
    }
    
    public function roundDB()
    {
        
        $query = $this->notification->find()
            ->select()
            ->where(function ($exp) {
                return $exp
                        ->lte('date', new \DateTime('now', new \DateTimeZone('Asia/Novosibirsk')));
            });
        
        foreach($query as $list) {
            if ($list['status']=='expect')
            {
                $this->initiate($list);
            }
        }
    }
    
    public function initiate($list)
    {
        if($list['recursive']!=NULL)
        {
            $this->help->recursiveHelp($list);
        }
        if($list['transport']=='sms')
        {
            $param = array (
                'src' => $list['sender'],
                'dst' => $list['address'],
                'text' => $list['text'],
            );
            
            $this->SMS->multipleSMS($param,$list);
        }
        elseif($list['transport']=='email')
        {   
            if(stripos($list['address'], '>'))
            {
                $this->email->multipleEmail($list);
            }
            else{
            $query = $this->notif_email->find()
                -> select()
                -> where(['notification_id'=> $list['id']])
                -> first();
                
                $param = array(
                    'sender' => $list['sender'],
                    'name' => $query['sender_name'],
                    'to' => $list['address'],
                    'theme' => $query['subject'],
                    'text'=> $list['text']);
                $this->email->sendEM($param);
                
                $status = $this->notification->get($list['id']);
                $status->status = 'delivered';
                $this->notification->save($status); 
            }
        }                        
    }
    
    public function dN($param)
    {
        if(key_exists('id',$param))
        {
            $query = $this->notification->find()
               -> select()
               -> hydrate(false)
               -> where(['id'=> $param['id']]);
            
           
            foreach($query as $ndata)
            {
                $this->help->delHelp($ndata);
            }
        }
        elseif(key_exists('date',$param))
        {
            if($param['date'] == NULL)
            {
                $query = $this->notification->find()
                    -> select()
                    -> hydrate(false)
                    -> where(function ($exp) {
                        return $exp
                        ->lte('date', new \DateTime('now', new \DateTimeZone('Asia/Novosibirsk')));
                    });
                
                foreach($query as $ndata)
                {
                    $this->help->delHelp($ndata);
                }
            }
            else{
                 $query = $this->notification->find()
                    -> select()
                    -> hydrate(false)
                    -> where(function ($exp) use ($param) {
                        return $exp
                        ->lte('date',$param['date']);
                    });
                
                foreach($query as $ndata)
                {
                    $this->help->delHelp($ndata);
                }
            }
        }
        elseif(key_exists('status',$param)){
            
            $query = $this->notification->find()
                -> select()
                -> hydrate(false)
                -> where(['status'=> $param['status']]);

            foreach($query as $ndata)
            {
                $this->help->delHelp($ndata);
            }
        }
        elseif(key_exists('transport', $param))
        {
            $query = $this->notification->find()
                    ->select()
                    ->hydrate(false)
                    ->where(['transport' => $param['transport']]);
            
            foreach($query as $ndata)
            {
                $this->help->delHelp($ndata);
            }
        }
    }
    
    public function showDB($param)
    {
        if($param['transport'] == 'email')
        {
           $queryN = $this->help->queryFindHelp($param);
           $queryE = $this->notif_email->find()
                ->hydrate(false)
                ->select()
                ->toArray();

            foreach($queryE as $arr_id=>$sub_array)
            {
                $queryN[$arr_id] = array_merge($queryN[$arr_id], $sub_array);            
            }
            return $queryN;
        }
        
        elseif($param['transport'] == 'sms')
        {
            $queryN = $this->help->queryFindHelp($param);
            $queryS = $this->notif_sms->find()
                ->hydrate(false)
                ->select()
                ->toArray();
            
            foreach($queryS as $arr_id=>$sub_array)
            {
                $queryN[$arr_id] = array_merge($queryN[$arr_id], $sub_array);            
            }
            return $queryN;
        }
    }

    public function editNotif($param)
    {
        if(key_exists('id', $param))
        {
            $editEnt = $this->notification->get($param['id']);
            $this->notification->patchEntity($editEnt,$param);
            
            $emailQ = $this->notif_email->find()
                -> select()
                -> where(['notification_id' => $param['id']]);
            
            foreach($emailQ as $row)
            {
                if($row!=NULL)
                {
                    if(key_exists('subject', $param))
                    {
                        $row->subject = $param['subject'];
                    }
                    if(key_exists('sender_name', $param))
                    {
                        $row->sender_name = $param['sender_name'];
                    }
                    $this->notif_email->save($row);
                }
            }
            $this->notification->save($editEnt);
        }
    }
    
    public function manipulation($param)
    {
        if(key_exists('id', $param))
        {
            $query = $this->notification->find()
                ->select()
                ->where(['id' => $param['id']]);
            $this->help->manipulationHelp($query,$param);
        }
        
        elseif((key_exists('rangeBegin', $param)) and (key_exists('rangeEnd', $param)))
        {
            $query = $this->notification->find()
                ->select()
                ->where(function ($exp) use ($param) {
                    return $exp
                            ->between('date', $param['rangeBegin'], $param['rangeEnd']);
                });
                
            
            $this->help->manipulationHelp($query,$param);
        }
        
        elseif(key_exist('date',$param))
        {
            $query = $this->notification->find()
                ->select()
                ->where(function ($exp) use ($param){
                    return $exp
                            ->lte('date',$param['date']);
                });
                
            $this->help->manipulationHelp($query,$param);
        }
    }
    
    public static function migration()
    {
        $migrations = new Migrations(['plugin'=> 'NotificationWithPlivo']);
        $migrations->migrate();
    }
}


