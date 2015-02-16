<div class="row">
    <div class="col-md-6">
        <?php
        $options = array(
            'success-url' => $this->Html->url(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'success'), true),
            'fail-url' => $this->Html->url(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'fail'), true),
        );
        echo $this->Form->create('payment', array(
            'id' => 'payment-form',
            'url'=> array("plugin" => 'gtw_stripe', "controller" => "payments", "action" => "confirm_payment"),
            'inputDefaults' => array(
                'div' => 'form-group',
                'wrapInput' => false,
                'class' => 'form-control'
            ),
        ));
        echo $this->Form->input('email', array('id' => 'card-holder-email'));
        echo $this->Form->input('card_number', array('id' => 'card-number'));
        echo $this->Form->hidden('amount', array('id' => 'amount', 'value' => $amount));
        $months = [];
        $years = [];
        for ($i=1; $i <= 12; $i++) {
            $months[$i] = $i;
        }
        for ($i=date('Y'); $i <= date('Y')+15; $i++) {
            $years[$i] = $i;
        }
        echo $this->Form->input('month', array('data-stripe' => "exp-month", 'id' => 'card-expiry-month', 'options'=>$months));
        echo $this->Form->input('year', array('data-stripe' => "exp-year", 'id' => 'card-expiry-year', 'options'=>$years));
        echo $this->Form->input('cvv', array('id' => 'cvv'));
        echo $this->Form->input('success_url', array('type' => 'hidden', 'value' => $options['success-url']));
        echo $this->Form->input('fail_url', array('type' => 'hidden', 'value' => $options['fail-url']));
        echo $this->Form->submit('Pay Now', array('class' => 'btn btn-success'));
        echo $this->Form->end();
        ?>
    </div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var PublishableKey = '<?php echo Configure::read('GtwStripe.publishable_key'); ?>'
</script>
<?php echo $this->Require->req('jquery');?>
<?php echo $this->Require->load($this->Html->url('/').'gtw_stripe/js/config'); ?>
<?php $this->Require->req('stripe/common'); ?>