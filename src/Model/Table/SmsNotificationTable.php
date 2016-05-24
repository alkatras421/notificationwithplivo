<?php
namespace NotificationWithPlivo\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use NotificationWithPlivo\Model\Entity\SmsNotification;

/**
 * SmsNotification Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Notifications
 * @property \Cake\ORM\Association\BelongsTo $Records
 */
class SmsNotificationTable extends Table
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

        $this->table('sms_notification');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Notifications', [
            'foreignKey' => 'notification_id',
            'joinType' => 'LEFT',
            'className' => 'NotificationWithPlivo.Notifications'
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

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['notification_id'], 'Notifications'));
        $rules->add($rules->existsIn(['record_id'], 'Records'));
        return $rules;
    }
}
