<?php 
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
            <div class="col-xs-8">
                <h3 class="title"><?php echo __('Invoice');?></h3>
            </div>
            <div class="col-xs-4 text-right"></div>
		</div>
	</div>
    <div class="panel-body items">
        <div class="row">            
            <div class="col-md-12">
                <h1>Hello <?php echo $transactionDetail['User']['first'].' '.$transactionDetail['User']['last'];?></h1>
				<h2>Thank you for payment of <?php echo strtoupper($transactionDetail['Transaction']['currency']).' '.$transactionDetail['Transaction']['amount'];?></h2>
            </div>
        </div>
    </div>
</div>