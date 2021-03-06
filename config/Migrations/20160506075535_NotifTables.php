<?php
use Migrations\AbstractMigration;

class NotifTables extends AbstractMigration
{
    public function change()
    {
        $tableNotif = $this->table('notifications',['id' => false, 'primary_key' => ['id']]);
        $tableNotif->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'limit' => 11
            ]);
       
        $tableNotif->addColumn('transport', 'string', [
             'default' => null,
            'limit' => 10
        ]);
        $tableNotif->addColumn('text', 'string', [
             'default' => null,
            'limit' => 1000
        ]);
        $tableNotif->addColumn('address', 'string', [
             'default' => null,
            'limit' => 255
        ]);
        $tableNotif->addColumn('sender', 'string', [
             'default' => null,
            'limit' => 50
        ]);
        $tableNotif->addColumn('date', 'datetime');
        $tableNotif->addColumn('recursive', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $tableNotif->addColumn('status', 'text');
        $tableNotif->create();
        
        $tableSMS =$this->table('sms_notification',['id' => false, 'primary_key' => ['id']]);
        $tableSMS
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'limit' => 11
            ]);
        $tableSMS->addColumn('notification_id', 'integer', [
             'default' => null,
            'limit' => 11
        ])
                
                ->addForeignKey('notification_id', 'notifications', 'id');
        $tableSMS->addColumn('record_id', 'string', [
            'default' => null,
            'limit' => 200,
            'null' => true
        ]);
        $tableSMS->create();
        
        $tableEmail =$this->table('email_notification',['id' => false, 'primary_key' => ['id']]);
        $tableEmail
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'limit' => 11
            ]);
        $tableEmail->addColumn('notification_id', 'integer', [
            'limit' => 11
        ])
                   ->addForeignKey('notification_id', 'notifications', 'id');;
        $tableEmail->addColumn('subject', 'string', [
            'limit' => 100
        ]);
        $tableEmail->addColumn('sender_name', 'string', [
            'limit' => 200
        ]);
        $tableEmail->create();
    }
}
