<?php $resultTableEmail = $this->get('email');
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
    echo $this->Form->end();
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
        <th>subject</th>
        <th>name</th>
        <th>status</th>
    </tr>
    
    <?php foreach ($resultTableEmail as $notifEmail): ?>
    <tr>
        <td>
            <?= $notifEmail->id ?>
        </td>
        <td>
            <?= $notifEmail->text ?>
        </td>
        <td>
            <?= $notifEmail->address ?>
        </td>
        <td>
            <?= $notifEmail->sender ?>
        </td>
        <td>
            <?= $notifEmail->date ?>
        </td>
        <td>
            <?= $notifEmail->recursive ?>
        </td>
        <td>
            <?= $notifEmail->subject ?>
        </td>
        <td>
            <?= $notifEmail->sender_name ?>
        </td>
        <td>
            <?= $notifEmail->status ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif;?>


