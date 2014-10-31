<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8"><h3 class="title"><?php echo __('My Subscribes Transaction'); ?></h3></div>
            <div class="col-md-4 text-right"><?php echo $this->Html->link('<i class="fa fa-reply"></i> Back',Router::url(array('plugin'=>false,'controller'=>'pages','action'=>'subscribes'),true),array('escape'=>false,'class'=>'btn btn-default')); ?></div>
        </div>
    </div>    
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width='10%'>Sr. No.</th>
                <th width='35%'>Plan Id</th>
                <th width='35%'>Plan Description</th>
                <th width='20%'>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($subscribes)) { ?>
                <tr>
                    <td colspan='5' class='text-warning'><?php echo __('No subscribes found.') ?></td>
                </tr>
                <?php
            } else {
                $srNo = 1;
                foreach ($subscribes as $subscribe) {
                    ?>
                    <tr>
                        <td><?php echo $srNo++; ?></td>
                        <td class="text-right">
                            <?php echo $subscribe['Transaction']['plan_id']; ?>
                        </td>
                        <td>
                            <?php echo $subscribe['Transaction']['plan_name']; ?>
                        </td>
                        <td><?php echo $this->Time->format('Y-m-d H:i:s', $subscribe['Transaction']['created']); ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>	
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12"><h3 class="title"><?php echo __('My Subscribes Plans'); ?></h3></div>
        </div>
    </div>    
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width='10%'>Sr. No.</th>
                <th width='30%' class="text-center">Plan Id</th>
                <th width='30%'>Plan Description</th>
                <th width='20%'>Created</th>
                <th width='10%' class='text-center'>Action</th>
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
                            <?php
                            echo $this->Html->link('Unsubscribe',array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans', 'action' => 'unsubscribe_user',$subscribePlan['subscribe_id']),array('class'=>'btn btn-primary'));
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
