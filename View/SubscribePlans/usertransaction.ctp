<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8"><h3 class="title"><?php echo $title; ?></h3></div>
            <div class="col-md-4 text-right"><?php echo $this->Html->link('<i class="fa fa-reply"></i> Back',$backUrl,array('escape'=>false,'class'=>'btn btn-default')); ?></div>
        </div>
    </div>    
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width='5%'><?php echo __d('gtw_stripe', 'Sr. No.');?></th>
                <th width='20%'><?php echo __d('gtw_stripe', 'User Name');?></th>
                <th width='10%'><?php echo __d('gtw_stripe', 'Plan Status');?></th>
                <th width='15%'><?php echo __d('gtw_stripe', 'Last Charged');?></th>
                <th width='15%'><?php echo __d('gtw_stripe', 'Last Modified');?></th>
                <th width='13%'><?php echo __d('gtw_stripe', 'Action');?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($planUsers)) { ?>
                <tr>
                    <td colspan='5' class='text-warning'><?php echo __d('gtw_stripe', 'No users found for this plan.') ?></td>
                </tr>
                <?php
            } else {
                $srNo = 1;
                foreach ($planUsers['SubscribePlanUser'] as $key=>$planUser) {
                ?>
                    <tr>
                        <td><?php echo $srNo++; ?></td>
                        <td><?php echo (isset($userList[$planUser['user_id']])?$userList[$planUser['user_id']]:''); ?></td>
                        <td><?php echo $planUser['status']; ?></td>
                        <td><?php echo $this->Time->format('Y-m-d H:i:s',$planUser['last_charged']); ?></td>
                        <td><?php echo $this->Time->format('Y-m-d H:i:s',$planUser['modified']); ?></td>
                        <td class="text-center">
                            <?php 
                            echo $this->Html->link(__d('gtw_stripe', 'View Transactions') ,array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans', 'action' => 'myplantransaction',$planUsers['SubscribePlan']['plan_id'],$planUser['user_id']),array('class'=>'btn btn-info'));
                            ?>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>	
</div>