<?php
namespace NotificationWithPlivo\Shell;


use NotificationWithPlivo\Main\Notification\General;
use Cake\Console\Shell;
use NotificationWithPlivo\Main\SMS\SMS;

Class RoundShell extends Shell
{
    public function main()
    {
        $general = new General();
        $sms = new SMS();
        $general->roundDB();
        $sms->upStatus();
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

