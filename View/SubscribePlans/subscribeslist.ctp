<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8"><h3 class="title"><?php echo __('Subscribe Now') ?></h3></div>
            <div class="col-md-4 text-right">
                <?php 
                    echo $this->Html->link('My Subscribes',array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans', 'action' => 'user_subscribe'),array('class'=>'btn btn-default'));
                ?>
            </div>
        </div>
    </div>    
    <table class="table table-hover table-striped table-bordered">
        <tbody>	
            <?php if(empty($plans)){ ?>
            <tr>
                <td>
                   <?php echo __('No any Subscribes plan found') ?>
                </td>
            </tr>
            <?php } ?>
            <?php foreach ($plans as $key => $plan) { ?>
            <tr>
                <td>
                    Subscribe this plan ($<?php echo $plan['SubscribePlan']['amount']?> per <?php echo $plan['SubscribePlan']['interval_count']. ' '.$plan['SubscribePlan']['plan_interval'] ?>)
                </td>
                <td width="10%" class="text-center">
                    <?php
                    echo $this->element('GtwStripe.subscribe', array('options' => array(
                            'description' => $plan['SubscribePlan']['name'],
                            'amount' => $plan['SubscribePlan']['amount'],
                            'label' => __('Subscribe Now'),
                            'success-url' =>''
//                            'success-url' => $this->Html->url(array('plugin' => false, 'action' => 'view', $goods['Egood']['slug'], 'type' => 'success'), true),
//                            'fail-url' => $this->Html->url(array('plugin' => false, 'action' => 'view', $goods['Egood']['slug'], 'type' => 'fail'), true),
                    ),'plan_id'=>$plan['SubscribePlan']['plan_id'],'subscribe_id'=>(isset($arrSubscribePlans[$plan['SubscribePlan']['plan_id']])?$arrSubscribePlans[$plan['SubscribePlan']['plan_id']]:null)));
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>	
</div>