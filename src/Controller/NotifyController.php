<?php
namespace NotificationWithPlivo\Controller;

use NotificationWithPlivo\Controller\AppController;
use Cake\ORM\TableRegistry;
use Symfony\Component\Yaml\Yaml;
use Plivo\RestAPI;
/**
 * Notify Controller
 *
 * @property \NotificationWithPlivo\Model\Table\NotifyTable $Notify
 */
class NotifyController extends AppController
{
    private $plivoInst;
    public function beforeFilter(\Cake\Event\Event $event) {
        $plivo = Yaml::parse(file_get_contents(__DIR__.DS.'..'.DS.'..'.DS.'config'.DS.'PlivoConf.yml'));
        $this->plivoInst = new RestAPI($plivo['auth_id'], $plivo['auth_token']);
    }
    
    public function index()
    {
        $this->loadComponent('NotificationWithPlivo.NotifyAPI');
        $paramE = (['transport' => 'email']);
        $e = $this->NotifyAPI->showBase($paramE);
        $paramS = (['transport' => 'sms']);
        $s = $this->NotifyAPI->showBase($paramS);
        $this->set('email', $e);
        $this->set('sms', $s);
        $this->NotifyAPI->round();
        $this->NotifyAPI->updateStat();
    }
    
    public function send()
    {
        $this->loadComponent('NotificationWithPlivo.NotifyAPI');
        
        $param = array ('record_id' => '9cd4028b-8505-4b2f-8dca-266f77b87249');
        
        #$this->NotifyAPI->migrat(); 
        $result = $this->NotifyAPI->getDetails($param);
        #$result = $this->plivoInst->get_message($param);
        echo '<pre>';
        var_dump($result);
    }
    
    public function add()
    {
        $this->loadComponent('NotificationWithPlivo.NotifyAPI');
        
        $params = array(
            'transport' => 'sms',
            'text' => 'asddsaasdsda',
            'address' => '79137397554>79538789532',
            'recursive'=> 'PT120S',
            'sender' => '79612212652'
        );
        
        $this->NotifyAPI->addToBase($params);
    }
    
    
}
