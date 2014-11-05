<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8"><h3 style='margin-top:0px'>Subscribe Plans</h3></div>
            <div class="col-md-4 text-right">
                <?php echo $this->Html->link('<i class="fa fa-th-large"></i> View Subscribe Transactions', Router::url(array('plugin'=>'gtw_stripe','controller'=>'subscribe_plans','action'=>'myplantransaction',0,0,true),true), array('class' => 'btn btn-primary', 'escape' => false, 'title' => 'View Subscribes transactions'));?>
                <?php echo $this->Html->link('<i class="fa fa-plus"> </i>Add Plan', array('controller' => 'subscribe_plans', 'action' => 'create_plans'), array('class' => 'btn btn-primary', 'escape' => false, 'title' => 'Add new plan'));?>
            </div>
        </div>
    </div>
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Plan Id</th>
                <th>Plan Name</th>
                <th>Plan amount</th>				
                <th>Plan Interval</th>				
                <th>Plan Interval Count</th>				
                <th>Plan Users</th>
                <th>Plan Status</th>
                <th>Last Updated</th>
                <th class='text-center'>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($plans)) { ?>
                <tr>
                    <td colspan='10' class='text-warning'>No record found.</td>
                </tr>
            <?php } else { ?>			
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><?php echo $plan['SubscribePlan']['id']; ?></td>
                        <td><?php echo $plan['SubscribePlan']['plan_id']; ?></td>
                        <td><?php echo $plan['SubscribePlan']['name']; ?></td>
                        <td><?php echo $this->Stripe->showPrice($plan['SubscribePlan']['amount']); ?></td>
                        <td><?php echo $plan['SubscribePlan']['plan_interval']; ?></td>
                        <td><?php echo $plan['SubscribePlan']['interval_count']; ?></td>
                        <td><?php echo $plan['SubscribePlan']['plan_user_count']; ?></td>
                        <td><?php echo $plan['SubscribePlan']['status']; ?></td>
                        <td><?php echo $plan['SubscribePlan']['modified']; ?></td>
                        <td class="text-center">
                            <span class="text-center">
                                <?php echo $this->Html->link('<i class="fa fa-pencil"> </i>', array('controller' => 'subscribe_plans', 'action' => 'create_plans', $plan['SubscribePlan']['id']), array('role' => 'button', 'escape' => false, 'title' => 'Edit this plan'));?>
                                &nbsp;
                                <?php echo $this->Html->link('<i class="fa fa-th"> </i>', array('controller' => 'subscribe_plans', 'action' => 'myplantransaction', $plan['SubscribePlan']['plan_id']), array('role' => 'button', 'escape' => false, 'title' => 'View this plan transactions'));?>
                                &nbsp;
                                <?php echo $this->Html->link('<i class="fa fa-user"> </i>', array('controller' => 'subscribe_plans', 'action' => 'usertransaction', $plan['SubscribePlan']['plan_id']), array('role' => 'button', 'escape' => false, 'title' => 'View this plan users'));?>
                                &nbsp;
                                <?php echo $this->Html->link('<i class="fa fa-trash-o"> </i>', array('controller' => 'subscribe_plans', 'action' => 'delete', $plan['SubscribePlan']['id']), array('role' => 'button', 'escape' => false, 'title' => 'Delete this plan'), 'Are you sure? You want to delete this plan.');?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php } ?>
        </tbody>
    </table>	
</div>