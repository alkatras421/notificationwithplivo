<?php
echo $this->Form->create(null,  ['url' => ['controller' => 'Notify', 'action' => 'index']]);

$resultTableEmail=$this->get('email');
$resultTableSMS=$this->get('sms');

?>
<?php if($resultTableEmail != NULL): ?>
<h3>EMAIL</h3>
<table>
    <tr>
        <th>Id</th>
        <th>text</th>
        <th>address</th>
        <th>sender</th>
        <th>date</th>
        <th>recursive</th>
        <th>theme</th>
        <th>subject</th>
        <th>status</th>
    </tr>
    
    <?php foreach ($resultTableEmail as $notifEmail): ?>
    <tr>
        <td>
            <?= $notifEmail['id_notif'] ?>
        </td>
        <td>
            <?= $notifEmail['text'] ?>
        </td>
        <td>
            <?= $notifEmail['address'] ?>
        </td>
        <td>
            <?= $notifEmail['sender'] ?>
        </td>
        <td>
            <?= $notifEmail['date'] ?>
        </td>
        <td>
            <?= $notifEmail['recursive'] ?>
        </td>
        <td>
            <?= $notifEmail['theme'] ?>
        </td>
        <td>
            <?= $notifEmail['sender_name'] ?>
        </td>
        <td>
            <?= $notifEmail['stat'] ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif;?>
<?php if($resultTableSMS != NULL): ?>
<h3>SMS</h3>
<table>
    <tr>
        <th>Id</th>
        <th>text</th>
        <th>address</th>
        <th>sender</th>
        <th>date</th>
        <th>recursive</th>
        <th>record id</th>
        <th>status</th>
    </tr>
    
    <?php foreach ($resultTableSMS as $notifSMS): ?>
        
    <tr>
        <td>
            <?= $notifSMS['id_notif'] ?>
        </td>
        <td>
            <?= $notifSMS['text'] ?>
        </td>
        <td>
            <?= $notifSMS['address'] ?>
        </td>
        <td>
            <?= $notifSMS['sender'] ?>
        </td>
        <td>
            <?= $notifSMS['date'] ?>
        </td>
        <td>
            <?= $notifSMS['recursive'] ?>
        </td>
        <td>
            <?= $notifSMS['record_id'] ?>
        </td>
        <td>
            <?= $notifSMS['stat'] ?>
        </td>
    </tr>

    <?php endforeach; ?>
</table>
<?php endif;?>
<!-- A sortable list of contacts would go here....-->