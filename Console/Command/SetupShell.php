<?php

class SetupShell extends AppShell {
    public $uses = array('GtwStripe.TransactionType','GtwStripe.Transaction');
	
    public function main() {
        $this->TransactionType->setDefaultData();
        echo __d('gtw_stripe',"Initial Setup Completed"). '\n';
    }
}