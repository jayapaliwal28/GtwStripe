<?php
Router::parseExtensions('rss');

Router::connect('/payments', array('plugin' => 'gtw_stripe', 'controller' => 'payments'));
Router::connect('/payments/index/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'index'));
Router::connect('/payments/success/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'success'));
Router::connect('/payments/fail/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'fail'));
Router::connect('/payments/callback_subscribes/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'callback_subscribes'));
Router::connect('/subscribe_plans', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'index'));
Router::connect('/subscribe_plans/user_subscribe/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'user_subscribe'));
Router::connect('/subscribe_plans/create_plans/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'create_plans'));
Router::connect('/subscribe_plans/myplantransaction/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'myplantransaction'));
Router::connect('/subscribe_plans/usertransaction/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'usertransaction'));
Router::connect('/subscribe_plans/subscribeslist/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'subscribeslist'));
