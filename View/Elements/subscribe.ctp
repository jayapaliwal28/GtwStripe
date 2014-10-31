<?php
if ($this->Session->read('Auth.User.id')) {    
    if(!empty($subscribe_id)){
        echo $this->Html->link('Unsubscribe Now',array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans', 'action' => 'unsubscribe_user',$subscribe_id),array('class'=>'btn btn-primary'));
    }else{
        $defaultOptions = array(
            'label' => '',
            'panel-label' => 'Subscribe',
            'description' => '',
            'amount' => 0,
            'success-url' => $this->Html->url(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'success'), true),
            'fail-url' => $this->Html->url(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'fail'), true),
        );
        $options = array_merge($defaultOptions, $options);
        $amount = $options['amount'] * 100; // Convert to Stripe Format
        $amountKey = $this->requestAction(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'one_time_payment_set_amount', 'amount' => $amount));
        ?>
        <?php echo $this->Form->create('GtwStripe', array('url' => array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'subscribe'))) ?>
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?php echo Configure::read('GtwStripe.publishable_key'); ?>"
            data-image="<?php echo Configure::read('GtwStripe.site_logo_path'); ?>"
            data-name="<?php echo Configure::read('GtwStripe.site_name'); ?>"
            data-description='<?php echo "Pro Subscription ($" . $options['amount'] . " per month)" ?>'
            data-panel-label="Subscribe"
            data-label="<?php echo $options['label'] ?>"
            data-allow-remember-me="false">
        </script>
        <?php
        echo $this->Form->input('key', array('type' => 'hidden', 'value' => $amountKey));
        echo $this->Form->input('plan_id', array('type' => 'hidden', 'value' => $plan_id));
        echo $this->Form->input('success_url', array('type' => 'hidden', 'value' => $options['success-url']));
        echo $this->Form->input('fail_url', array('type' => 'hidden', 'value' => $options['fail-url']));
        echo $this->Form->end();
    }
} else {
    ?>
    <label class='label label-warning '><?php echo __('Please login to %s', $options['label']); ?></label>
<?php } ?>