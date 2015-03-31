<?php 

$defaultOptions = array(
		'label' => '',
		'panel-label' => '',
		'description' => '',
		'amount' => 0,
		'success-url' => $this->Html->url(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'success'), true),
        'fail-url' => $this->Html->url(array('plugin' => 'gtw_stripe', 'controller' => 'payments', 'action' => 'fail'), true)
);

$options = array_merge($defaultOptions, $options);
?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buyModal">
  <i class="fa fa-shopping-cart"></i> Buy now
</button>

<!-- Modal -->
<div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $options['description'] ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
		    <div class="col-md-12">
		    <div id="payment-validations" class="text-danger"></div>
		        <?php
		        
		        echo $this->Form->create('payment', array(
		            'id' => 'payment-form',
            		'url'=> array("plugin" => 'gtw_stripe', "controller" => "payments", "action" => "confirm_payment"),
		            'inputDefaults' => array(
		                'div' => 'form-group',
		                'wrapInput' => false,
		                'class' => 'form-control'
		            ),
		        ));
		        
        		echo $this->Form->input('email', array('id' => 'card-holder-email', "placeholder" => 'Email'));
		        echo $this->Form->input('card_number', array('id' => 'card-number','label' => 'Card Number <small class="text-muted"><span class="cc-brand"></span></small>', "placeholder" => 'Card Number'));
		        echo $this->Form->input('card-expiry', array('id' => 'card-expiry','label' => 'Card Expiry', 'type' => 'tel', "placeholder" => 'MM / YYYY'));
		        echo $this->Form->input('cvv', array('id' => 'card-cvv', "placeholder" => 'CVV'));
		        echo $this->Form->input('success_url', array('type' => 'hidden', 'value' => $options['success-url']));
		        echo $this->Form->input('fail_url', array('type' => 'hidden', 'value' => $options['fail-url']));
		        echo $this->Form->hidden('amount', array('id' => 'amount', 'value' =>  $options['amount']));
		        
		        echo $this->Form->submit('Pay $'. $options['amount'], array('class' => 'btn btn-success btn-block'));
		        
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
		<?php $this->Require->req('stripe/jquery.payment'); ?>
		<?php $this->Require->req('stripe/one_time_payment'); ?>
      </div>
    </div>
  </div>
</div>


  
 