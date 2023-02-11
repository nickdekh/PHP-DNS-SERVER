<?php

require_once __DIR__.'/../vendor/autoload.php';

// JsonResolver created and provided with path to file with json dns records
$jsonResolver = new yswery\DNS\Resolver\JsonResolver([__DIR__.'/record.json', __DIR__.'/example.com.json']);

// System resolver acting as a fallback to the JsonResolver
$systemResolver = new yswery\DNS\Resolver\SystemResolver();

// StackableResolver will try each resolver in order and return the first match
//$stackableResolver = new yswery\DNS\Resolver\StackableResolver([$jsonResolver, $systemResolver]);
$stackableResolver = new yswery\DNS\Resolver\StackableResolver([$jsonResolver]);

// Create the eventDispatcher and add the event subscribers
$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
$eventDispatcher->addSubscriber(new \yswery\DNS\Event\Subscriber\EchoLogger());
$eventDispatcher->addSubscriber(new \yswery\DNS\Event\Subscriber\ServerTerminator());

// Create a new instance of Server class
$server = new yswery\DNS\Server($stackableResolver, $eventDispatcher);

// Start DNS server
$server->start();
