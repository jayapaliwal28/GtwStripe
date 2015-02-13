<?php
//echo $this->element('GtwStripe.one_time_fix_payment', array('options' => array(
//        'description' => 'Donate Now',
//        'amount' => $amount,
//        'label' => 'Donate Now',
//        'panel-label' => 'Donate',
//        'return-url' => ''
//)));
?>
<div class="container">
    <div class="col-md-12 text-center">
        <h3>You are going to pay amount <?php echo (!empty(Configure::read('GtwStripe.currency'))?Configure::read('GtwStripe.currency').' ':' ').$amount;?></h3>
        <h3>Are you sure to make this payment ?</h3>
    </div>
    <div class="row text-center">
        <?php
        echo $this->element('GtwStripe.one_time_fix_payment', array('options' => array(
                'description' => 'Pay Now',
                'amount' => $amount,
                'label' => 'Pay Now',
                'panel-label' => 'Pay Now',
                'return-url' => ''
        )));
?>
    </div>
</div>