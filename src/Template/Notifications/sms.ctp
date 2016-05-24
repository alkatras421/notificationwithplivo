<?php $resultTableSMS = $this->get('sms');       
$options = ['id'=> 'id',
        'text' => 'text',
        'address' => 'address', 
        'sender' => 'sender', 
        'date' => 'date', 
        'status' => 'status'];
    
    echo $this->Form->create();
    echo $this->Form->input('Search',['required' => true]);
    echo $this->Form->select('searcher', $options);
    echo $this->Form->button(__('Search'));
    echo $this->Html->link('Cancel',
        ['controller' => 'notifications', 'action' => 'sms'],
        ['class' => 'button']
    );
    echo $this->Form->end();
 ?>
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
            <?= $notifSMS->id ?>
        </td>
        <td>
            <?= $notifSMS->text ?>
        </td>
        <td>
            <?= $notifSMS->address ?>
        </td>
        <td>
            <?= $notifSMS->sender ?>
        </td>
        <td>
            <?= $notifSMS->date ?>
        </td>
        <td>
            <?= $notifSMS->recursive ?>
        </td>
        <td>
            <?= $notifSMS->record_id ?>
        </td>
        <td>
            <?= $notifSMS->status ?>
        </td>
    </tr>

    <?php endforeach; ?>
</table>
<?php endif;?>


