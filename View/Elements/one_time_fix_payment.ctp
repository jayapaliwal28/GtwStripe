<?php
if($this->Session->read('Auth.User.id')){
	$defaultOptions = 	array(
					'label'=>'',
					'panel-label'=>'',
					'description'=>'',
					'amount'=>0,
					'success-url'=>$this->Html->url(array('plugin'=>'gtw_stripe','controller'=>'payments','action'=>'success'),true),
					'fail-url'=>$this->Html->url(array('plugin'=>'gtw_stripe','controller'=>'payments','action'=>'fail'),true),
				);
	$options = array_merge($defaultOptions,$options);
	$amount = $options['amount']*100; // Convert to Stripe Format
	$amountKey = $this->requestAction(array('plugin'=>'gtw_stripe','controller'=>'payments','action'=>'one_time_payment_set_amount','amount'=>$amount));	
	?>
	<?php echo $this->Form->create('Stripe',array('url'=>array('plugin'=>'gtw_stripe','controller'=>'payments','action'=>'one_time_payment')))?>
		<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			  data-key="<?php echo Configure::read('GtwStripe.publishable_key'); ?>"
			  data-name="<?php echo Configure::read('GtwStripe.site_name'); ?>"
			  data-image="<?php echo Configure::read('GtwStripe.site_logo_path'); ?>"
			  data-currency="<?php echo Configure::read('GtwStripe.currency'); ?>"
			  data-amount="<?php echo $amount;?>" 
			  data-description="<?php echo $options['description']?>" 
			  data-label = "<?php echo $options['label']?>"
			  data-panel-label = "<?php echo $options['panel-label']?>"
			  >
		</script>
<?php 
		echo $this->Form->input('key',array('type'=>'hidden','value'=>$amountKey));
		echo $this->Form->input('success_url',array('type'=>'hidden','value'=>$options['success-url']));
		echo $this->Form->input('fail_url',array('type'=>'hidden','value'=>$options['fail-url']));
	echo $this->Form->end();
}else{
?>
	<label class='label label-warning '><?php echo __('Please login to %s',$options['label']);?></label>
<?php } ?>