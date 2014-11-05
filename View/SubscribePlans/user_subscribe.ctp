<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6"><h3 class="title"><?php echo __('My Subscribes Plans'); ?></h3></div>
            <div class="col-md-6 text-right">
                <?php 
                echo $this->Html->link('<i class="fa fa-th-large"></i> View All Transactions',Router::url(array('plugin'=>'gtw_stripe','controller'=>'subscribe_plans','action'=>'myplantransaction'),true),array('escape'=>false,'class'=>'btn btn-primary')); 
                echo '&nbsp;';
                echo $this->Html->link('<i class="fa fa-reply"></i> Back',Router::url(array('plugin'=>false,'controller'=>'pages','action'=>'subscribes'),true),array('escape'=>false,'class'=>'btn btn-default')); 
                ?>
            </div>
        </div>
    </div>    
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width='5%'>Sr. No.</th>
                <th width='15%' class="text-center">Plan Id</th>
                <th width='35%'>Plan Description</th>
                <th width='20%'>Created</th>
                <th width='25%' class='text-center'>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($subscribePlans)) { ?>
                <tr>
                    <td colspan='5' class='text-warning'><?php echo __('No subscribes found.') ?></td>
                </tr>
                <?php
            } else {
                $srNo = 1;
                foreach ($subscribePlans as $subscribePlan) {
                    ?>
                    <tr>
                        <td><?php echo $srNo++; ?></td>
                        <td class="text-center">
                            <?php echo $subscribePlan['plan_id']; ?>
                        </td>
                        <td>
                            <?php echo $subscribePlan['plan_name']; ?>
                        </td>
                        <td><?php echo $this->Time->format('Y-m-d H:i:s', $subscribePlan['created']); ?></td>
                        <td class="text-center actions">
                            <?php echo $this->Html->link('Unsubscribe Now',array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans', 'action' => 'unsubscribe_user',$subscribePlan['subscribe_id']),array('class'=>'btn btn-warning'),'Do you want to really unsubscribe this plan?');?>
                            <?php echo $this->Html->link('View Transactions',array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans', 'action' => 'myplantransaction',$subscribePlan['plan_id']),array('class'=>'btn btn-info'));?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>	
</div>
