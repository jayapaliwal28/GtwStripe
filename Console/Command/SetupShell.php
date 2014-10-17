<?php

class SetupShell extends AppShell {
    public $uses = array('GtwStripe.TransactionType','GtwStripe.Transaction');
	
    public function main() {
        $this->TransactionType->setDefaultData();
        echo "Initial Setup Completed\n";
    }
}