<?php
namespace NotificationWithPlivo\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity.
 *
 * @property int $id
 * @property string $transport
 * @property string $text
 * @property string $address
 * @property string $sender
 * @property \Cake\I18n\Time $date
 * @property string $recursive
 * @property string $status
 * @property \NotificationWithPlivo\Model\Entity\EmailNotification[] $email_notification
 * @property \NotificationWithPlivo\Model\Entity\SmsNotification[] $sms_notification
 */
class Notification extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
