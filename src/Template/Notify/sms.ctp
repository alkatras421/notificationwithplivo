<?php $resultTableSMS = $this->get('sms'); ?>

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
            <?= $notifSMS['notification_id'] ?>
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
            <?= $notifSMS['status'] ?>
        </td>
    </tr>

    <?php endforeach; ?>
</table>
<?php endif;?>


