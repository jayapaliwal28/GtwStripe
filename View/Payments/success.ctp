<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3 pull-right text-left">
            <h3 ><?php echo !empty(Configure::read('GtwStripe.Invoive.CompanyName'))?Configure::read('GtwStripe.Invoive.CompanyName'):'';?></h3>
            <h5><?php echo !empty(Configure::read('GtwStripe.Invoive.address'))?Configure::read('GtwStripe.Invoive.address'):'';?></h5>
            <h5><?php echo !empty(Configure::read('GtwStripe.Invoive.email'))?Configure::read('GtwStripe.Invoive.email'):'';?></h5>
            <h5><?php echo !empty(Configure::read('GtwStripe.Invoive.phone'))?Configure::read('GtwStripe.Invoive.phone'):'';?></h5>
        </div>
        <div style="border-bottom: 1px solid #d5d5d5" class="col-md-12"></div>
        <div class="col-md-12">
            <?php
                $userName = 'Anonymous';
                if(isset($transactionDetail['User']['first']) || isset($transactionDetail['User']['last'])){
                    $userName = $transactionDetail['User']['first'] . ' ' . $transactionDetail['User']['last'];
                }
            ?>
            <h4><?php echo 'Name : ' . $userName; ?></h4>
            <h5><?php echo 'Email : ' . $transactionDetail['Transaction']['email']; ?></h5>
            <table class="table table-responsive table-striped">
                <tr>
                    <td>Date</td>
                    <td><?php echo $transactionDetail['Transaction']['created']; ?></td>
                <tr>
                <tr>
                    <td>Invoice Id</td>
                    <td><?php echo $transactionDetail['Transaction']['id']; ?></td>
                <tr>
                <tr>
                    <td>Amount</td>
                    <td><?php echo $transactionDetail['Transaction']['amount']; ?></td>
                <tr>
                <tr>
                    <td>Currency</td>
                    <td><?php echo strtoupper($transactionDetail['Transaction']['currency']); ?></td>
                <tr>
                <tr>
                    <td>Payment Method</td>
                    <td><?php echo $transactionDetail['Transaction']['brand']; ?></td>
                <tr>
            </table>
            <button onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
        </div>
    </div>
</div>