<div class="col-md-2"></div>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-8"><h3 style='margin-top:0px'><?php echo (isset($title)?'Edit':'Add')?> Subscribe Plan</h3></div>
                <div class="col-md-4 text-right"><?php echo $this->Html->actionIconBtn('fa fa-reply', ' Back', 'index'); ?></div>
            </div>
        </div>
        <div class="panel-body">
            <?php $this->Helpers->load('GtwRequire.GtwRequire');?>
            <?php echo $this->Form->create('SubscribePlan', array('url'=>array('controller'=>'subscribe_plans','action'=>'create_plans',(isset($title)?$this->request->params['pass'][0]:'')),'inputDefaults' => array('div' => 'col-md-12 form-group', 'class' => 'form-control'), 'class' => 'form-horizontal', 'id' => 'PlanAddForm', 'novalidate' => 'novalidate')); ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $ds=(isset($title)?'disabled':'');
                    echo $this->Form->input('plan_id', array('label' => 'Plan id',$ds,'type'=>'text'));
                    echo $this->Form->input('name', array('label' => 'Plan Name',));
                    echo $this->Form->input('plan_interval', array('label' => 'Plan Interval',$ds, 'options' => array('day'=>'Daily','month'=>'Monthly', 'week'=>'Weekly', 'year'=>'Yearly')));
                    echo $this->Form->input('interval_count', array('label' => 'Plan Interval Count','after' => '<h6>IF you have plan with the per month than write interval count as 1</h6>',$ds));
                    echo $this->Form->input('amount', array('label' => 'Plan Amount', 'min' => 0,$ds));
                    echo $this->Form->input('status', array('label' => 'Plan Status',$ds ,'options' => array('active'=>'Active','deactive'=>'Deactive')));
                    echo $this->Form->submit((isset($title)?'Update Plan':'Create Plan'), array('div' => false,'class' => 'btn btn-primary'));
                    ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<div class="col-md-2"></div>
