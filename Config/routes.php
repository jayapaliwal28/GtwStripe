<?php
Router::parseExtensions('rss');

Router::connect('/payments', array('plugin' => 'gtw_stripe', 'controller' => 'payments'));
Router::connect('/payments/index/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'index'));
Router::connect('/payments/success/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'success'));
Router::connect('/payments/fail/*', array('plugin' => 'gtw_stripe', 'controller' => 'payments','action'=>'fail'));