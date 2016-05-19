<?php$resultTableEmail=$this->get('email');?>

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
            <?= $notifEmail['notification_id'] ?>
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
            <?= $notifEmail['subject'] ?>
        </td>
        <td>
            <?= $notifEmail['sender_name'] ?>
        </td>
        <td>
            <?= $notifEmail['status'] ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif;?>
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

