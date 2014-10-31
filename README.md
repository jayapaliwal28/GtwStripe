## GtwStripe

This plugin is under development and should not be used in it's current state.

## Installation

1. Copy this plugin to 
 /plugins/ directory
2. load the plugin from the /config/bootstrap.php file

3. now you can use this plugin to your site

Benifits:
- one time payment option and
- subscribes options available

For use this you have to create the plans
For create plan :
there are gtw_stripe/subscribe_plans/create_plans
though this link you can easily build the plan

/gtw_stripe/subscribe_plans

from above page you should edit ,delete and add new plan options available.

/gtw_stripe/subscribe_plans/user_subscribe
from above page you can easily listing the transaction as well as the unsubscribe option

for display subscribe button you have to call the subscribe element with the following informations
following value pass in the options array
1. Description of plan
2. amount of plan
3. label of button
following information pass directly
1.plan_id (eg. gold,silver)
2. subscribe_id (this is for the unsubscribe the plan on the listing page)

eg.
echo $this->element('GtwStripe.subscribe', array('options' => array(
    'description' => $planDescription,
    'amount' => $amount,
    'label' => __('Subscribe Now'),
    'success-url' =>''
),'plan_id'=>$plan_id,'subscribe_id'=>$subscribe_id));


for one time payment option you have to call the following element.
"one_time_payment"
you have to pass the following information in the options array
1.description
2.amount
3.label
4.panel-label

eg.
echo $this->element('GtwStripe.one_time_fix_payment', array('options' => array(
    'description' => 'Donate Now',
    'amount' => $amount,
    'label' => 'Donate Now',
    'panel-label' => 'Donate',
    'return-url' => ''
)));


## Copyright and license   
Author: Philippe Lafrance   
Copyright 2013 [Gintonic Web](http://gintonicweb.com)  
Licensed under the [Apache 2.0 license](http://www.apache.org/licenses/LICENSE-2.0.html)