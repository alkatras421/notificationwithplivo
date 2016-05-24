<?php
namespace NotificationWithPlivo\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailNotification Entity.
 *
 * @property int $id
 * @property int $notification_id
 * @property \NotificationWithPlivo\Model\Entity\Notification $notification
 * @property string $subject
 * @property string $sender_name
 */
class EmailNotification extends Entity
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
