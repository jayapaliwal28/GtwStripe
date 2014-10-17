<?php
/**
 * @property Currency $Currency
 * @property Exchange $Exchange
 * @property Simulation $Simulation
 * @property Portfolio $Portfolio
 * @property Historical $Historical
 * @property Dividend $Dividend
 * @property Journal $Journal
 * @property Title $Title
 */
class SetupShell extends AppShell {
    public $uses = array('GtwStripe.TransactionType','GtwStripe.Transaction');
	
    public function main() {
        $this->TransactionType->setDefaultData();
        echo "Initial Setup Completed\n";
    }
}