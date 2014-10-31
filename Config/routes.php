<?php
Router::parseExtensions('rss');

Router::connect('/payments', array('plugin' => 'gtw_stripe', 'controller' => 'payments'));
Router::connect('/payments/index/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'index'));
Router::connect('/payments/success/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'success'));
Router::connect('/payments/fail/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'fail'));
Router::connect('/subscribe_plans', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'index'));
Router::connect('/subscribe_plans/user_subscribe/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'user_subscribe'));
Router::connect('/subscribe_plans/create_plans/*', array('plugin' => 'gtw_stripe', 'controller' => 'subscribe_plans','action'=>'create_plans'));
