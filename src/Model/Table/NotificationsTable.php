<?php
namespace NotificationWithPlivo\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use NotificationWithPlivo\Model\Entity\Notification;

/**
 * Notifications Model
 *
 * @property \Cake\ORM\Association\HasMany $EmailNotification
 * @property \Cake\ORM\Association\HasMany $SmsNotification
 */
class NotificationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('notifications');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EmailNotification', [
            'foreignKey' => 'notification_id',
            'className' => 'NotificationWithPlivo.EmailNotification'
        ]);
        $this->hasMany('SmsNotification', [
            'foreignKey' => 'notification_id',
            'className' => 'NotificationWithPlivo.SmsNotification'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('transport', 'create')
            ->notEmpty('transport');

        $validator
            ->requirePresence('text', 'create')
            ->notEmpty('text');

        $validator
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->requirePresence('sender', 'create')
            ->notEmpty('sender');

        $validator
            ->dateTime('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->allowEmpty('recursive');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }
}
