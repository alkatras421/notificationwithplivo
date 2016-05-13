<?php
namespace NotificationWithPlivo\Main\Notification;

use NotificationWithPlivo\Main\Mailer\Mail;
use NotificationWithPlivo\Main\SMS\SMS;
use NotificationWithPlivo\Main\Notification\NeedHelp;
use Migrations\Migrations;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Composer\Script\Event;
 
Class General
{
    public function __construct() 
    {
        $this->SMS = new SMS();
        $this->email = new Mail();     
        $this->help = new NeedHelp();
        
        $this->notification = TableRegistry::get('notifications');
        $this->notif_sms = TableRegistry::get('sms_notif');
        $this->notif_email = TableRegistry::get('email_notif');
        
    }
   
    public function addTB($param)
    {
        
        $result_email = $this->notif_email->newEntity();
        $result_sms = $this->notif_sms->newEntity();
        $result_notif = $this->notification->newEntity(['transport'=> $param['transport'],
                                             'text' => $param['text'],
                                             'address' => $param['address'],
                                             'sender' => $param['sender'],
                                             'stat' => 'expect',
                                             
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
            if(($param['transport'] == 'email') and (key_exists('theme',$param)))
            {
                $result_email->theme = $param['theme'];
                
                if(key_exists('sender_name',$param))
                {
                    $result_email->sender_name = $param['sender_name'];
                }
                else{
                    $result_email->sender_name = $param['sender'];
                }
                
                $this->notification->save($result_notif);
                $result_email->id_notif = $result_notif->id;
                $this->notif_email->save($result_email);
            }
            elseif(($param['transport'] == 'email') and (!key_exists('theme',$param)))
            {
                throw new NotFoundException('The \'theme\' is not exist');
            }
            
            elseif(($param['transport'] == 'sms') and preg_match('^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$^',$param['address']))
            {
                $this->notification->save($result_notif);
                $result_sms->id_notif = $result_notif->id;
                $this->notif_sms->save($result_sms);
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
            if ($list['stat']=='expect')
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
                -> where(['id_notif'=> $list['id']])
                -> first();
                
                $param = array(
                    'sender' => $list['sender'],
                    'name' => $query['sender_name'],
                    'to' => $list['address'],
                    'theme' => $query['theme'],
                    'text'=> $list['text']);
                $this->email->sendEM($param);
                
                $stat = $this->notification->get($list['id']);
                $stat->stat = 'delivered';
                $this->notification->save($stat); 
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
        elseif(key_exists('stat',$param)){
            
            $query = $this->notification->find()
                -> select()
                -> hydrate(false)
                -> where(['stat'=> $param['stat']]);

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
                -> where(['id_notif' => $param['id']]);
            
            foreach($emailQ as $row)
            {
                if($row!=NULL)
                {
                    if(key_exists('theme', $param))
                    {
                        $row->theme = $param['theme'];
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
            $query = $this->notification->get($param['id']);

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


