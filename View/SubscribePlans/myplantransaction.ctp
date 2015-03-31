<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8"><h3 class="title"><?php echo $title; ?></h3></div>
            <div class="col-md-4 text-right"><?php echo $this->Html->link('<i class="fa fa-reply"></i> '. __d('gtw_stripe', ' Back'),$backUrl,array('escape'=>false,'class'=>'btn btn-default')); ?></div>
        </div>
    </div>    
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width='5%'>Sr. No.</th>
                <?php if($all){ ?>
                    <th width='8%'><?php echo $this->Paginator->sort('plan_id',__d('gtw_stripe', 'Plan Name')); ?></th>
                <?php } ?>
                <th width='<?php echo ($all)?'22%':'26%'?>'><?php echo $this->Paginator->sort('plan_name',__d('gtw_stripe', 'Plan Description')); ?></th>
                <th width='<?php echo ($all)?'22%':'26%'?>'><?php echo $this->Paginator->sort('transaction_id',__d('gtw_stripe', 'Transaction Id')); ?></th>
                <th width='7%'><?php echo $this->Paginator->sort('amount', __d('gtw_stripe', 'Amount')); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('brand',__d('gtw_stripe', 'Pay Using')); ?></th>
                <th width='13%'><?php echo $this->Paginator->sort('created', __d('gtw_stripe', 'Date Added')); ?></th>
                <th width='13%'><?php echo $this->Paginator->sort('modified', __d('gtw_stripe', 'Date Updated')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)) { ?>
                <tr>
                    <td colspan='<?php echo ($all)?'8':'7'?>' class='text-warning'><?php echo __d('gtw_stripe', 'No transactions found.') ?></td>
                </tr>
                <?php
            } else {
                $srNo = 1;
                foreach ($transactions as $key=>$transaction) {
                ?>
                    <tr>
                        <td><?php echo $srNo++; ?></td>
                        <?php if($all){ ?>
                        <td><?php echo $transaction['Transaction']['plan_id']; ?></td>
                        <?php } ?>
                        <td><?php echo $transaction['Transaction']['plan_name']; ?></td>
                        <td><?php echo $transaction['Transaction']['transaction_id']; ?></td>
                        <td><?php echo $this->Stripe->showPrice($transaction['Transaction']['amount']); ?></td>
                        <td><?php echo $transaction['Transaction']['brand']; ?></td>
                        <td><?php echo $this->Time->format('Y-m-d H:i:s',$transaction['Transaction']['created']); ?></td>
                        <td><?php echo $this->Time->format('Y-m-d H:i:s',$transaction['Transaction']['modified']); ?></td>
                    </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>	
</div>