<?php
namespace NotificationWithPlivo\Controller\Component;

use NotificationWithPlivo\Main\Mailer\Mail;
use NotificationWithPlivo\Main\SMS\SMS;  
use NotificationWithPlivo\Main\Notification\General; 


use Cake\Controller\Component;

/**
 * NotifyAPI component
 */

class NotifyAPIComponent extends Component
{     
    public $SMS;
    public $email;
    public $general;
    
    public function __construct() 
    {
        $this->SMS = new SMS();
        $this->email = new Mail();
        $this->general = new General(); 
    }
    
    /*
     * Вернуть полную информацию о SMS сообщениях.
     * Можно передавать record_id для информации об определенном сообщении.
     */
    public function getDetails($params = null)
    {
        if($params === NULL)
        {
            return $this->SMS->getDAM();
        }
        else{
            return $this->SMS->getDSM($params);
        }
    }

    /*
     * Отправка email письма. Необходимо передавать:
     * address - адресс отправки.
     * sender - отправитель.
     * theme - тема сообщения.
     * sender_name - то, что помещается в кавычки "".
     * text - текст сообщения.
     */
    public function sendEmailMessage($param)
    {
        $this->email->sendEM($param);
    }
    
    /*
     * Добавление нотификаций в базу данных.
     * Обязательными полями являются:
     * 'transport
     * 'text'
     * 'address'
     * 'sender'
     * Так же если транспортом является email, то необходимо указать theme
     * К необязательным полям относятся:
     * 'date' и 'recursive'.
     */
    public function addToBase($param)
    {
        $this->general->addTB($param);
    }
    
    /*
     * Функция проходящая по базе данных и организовывающая отправку нотификаций
     * по способу доставки.
     */
    public function round()
    {
        $this->general->roundDB();
    }
    
    /*
     * Функция отображения записей из базы данных.
     */
    public function showBase($param)
    {
        return $result = $this->general->showDB($param);
    }
    /*
     * Функция редактирования нотификации.
     * Необходимо передавать id и изменения.
     */
    public function editNotification($param)
    {
        $this->general->editNotif($param);
    }
    
    /*
     * Функция удаления записей из базы данных по атрибуту.
     * 'id' - удаление конкретной записи по определенному id.
     * 'date' - удаление записей до определенного момента времени.
     * 'stat' - удаление записей с определенным статусом. 
     */
    public function deleteNotification($param)
    {
        $this->general->dN($param);
    }
    /*
     * Обновление статусов нотификации в базе данных.
     */
    public function updateStat()
    {
        $this->SMS->upStat();
    }
    /*
     * Отправка sms письма. Необходимо передавать:
     * address - адресс отправки.
     * sender - отправитель.
     * text - текст сообщения.
     */
    public function sendSMS($param)
    {
       return $this->SMS->sendSingleSMS($param);
    }
    /*
     * Функция отмены отправки сообщения.
     */
    public function manipulationWithStat($param)
    {
        $this->general->manipulation($param);
    }
    public function migrat()
    {
        $this->general->migration();
    }

    /**
     * Default configuration.
     *
     * @var array
     * 
     * Всякая шелуха:
     * 
     * 
     */

    
    protected $_defaultConfig = [];
}
