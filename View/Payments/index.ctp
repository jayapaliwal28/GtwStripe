<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
            <div class="col-md-8"><h3 class="title">Transactions</h3></div>
            <div class="col-md-4 text-right"></div>
		</div>
	</div>    
    <table class="table table-hover table-striped table-bordered">
		<thead>
			<tr>
                <th width='5%'><?php echo $this->Paginator->sort('id'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('User.first','User'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('transaction_type_id','Transaction Type'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('transaction_id','Transaction Id'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('amount'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('brand','Pay Using'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('created', 'Date Added'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('modified', 'Date Updated'); ?></th>
				<th width='10%' class='text-center'>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($transactions)){?>
				<tr>
					<td colspan='<?php echo $colCount;?>' class='text-warning'><?php echo __('No record found.')?></td>
				</tr>
			<?php 
                }else{
                    foreach ($transactions as $transaction){
            ?>
                        <tr>
                            <td><?php echo $transaction['Transaction']['id']?></td>
                            <td><?php echo $transaction['User']['first'].' '.$transaction['User']['last'];?></td>
                            <td class="text-center"><?php echo $transaction['TransactionType']['name']?></td>
                            <td><?php echo $transaction['Transaction']['transaction_id']?></td>
                            <td><?php echo strtoupper($transaction['Transaction']['currency']).' '.$transaction['Transaction']['amount']?></td>
							<td><?php echo $transaction['Transaction']['brand'].' '.$transaction['Transaction']['last4']?></td>
                            <td><?php echo $this->Time->format('Y-m-d H:i:s', $transaction['Transaction']['created']); ?></td>
                            <td><?php echo $this->Time->format('Y-m-d H:i:s', $transaction['Transaction']['modified']); ?></td>
                            <td class="text-center actions">
                                <?php echo '&nbsp&nbsp';?>
                            </td>
                        </tr>
            <?php
                    }
                }
            ?>
		</tbody>
	</table>	
</div>