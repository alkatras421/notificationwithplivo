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
     * subject - тема сообщения.
     * sender_name - имя/наименование отправителя .
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
     * Так же если транспортом является email, то необходимо указать subject
     * К необязательным полям относятся:
     * 'date', 'recursive' и 'sender_name'.
     * 'sender_name' можно добавить, если нотификация отправляется через email. 
     */
    public function addToBase($param)
    {
        return $this->general->addTB($param);
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
     * Необходимо передавать способ отправки.
     * После выполнения возвращается ассоциативный массив со всей информацией о
     * нотификациях, указанного способа доставки.
     */
    public function showBase($param)
    {
        return $result = $this->general->showDB($param);
    }
    /*
     * Функция редактирования нотификации.
     * Необходимо передавать id и необходимые изменения изменения
     * Пример:
     * $param = array ('id' => '1', 'text' => 'example', 'subject' => 'my amazing plugin').
     */
    public function editNotification($param)
    {
        $this->general->editNotif($param);
    }
    
    /*
     * Функция удаления записей из базы данных по атрибуту.
     * 'id' - удаление конкретной записи по определенному id.
     * 'date' - удаление записей до определенного момента времени.
     * 'status' - удаление записей с определенным статусом. 
     */
    public function deleteNotification($param)
    {
        $this->general->dN($param);
    }
    /*
     * Обновление статусов смс нотификации в базе данных.
     */
    public function updateStatus()
    {
        $this->SMS->upStatus();
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
     * Передаваемые ключи:
     * id - отмена/возобновление конкретной нотификации
     * date - отмена/возобновление нотификаций до определенного момента времени
     * rangeBegin и rangeEnd - диапазон времени, в период которого необходимо отменить/возобновить статус нотификации.
     * status - передавать строгое значение статуса. Если не указывать status, то значения статуса будут меняться на противоположные.
     */
    public function manipulationWithStatus($param)
    {
        $this->general->manipulation($param);
    }
    
    /*
     * Функция проверки состояния сообщений со статусом  Unavailable.
     * Вызывает статус сообщений plivo и меняет на delivered или undelivered.
     */
    public function checkUnavailableStatus()
    {
	$this->SMS->checkUnavailable();
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
