<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class ServiceTestCase extends WebTestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = static::createClient()->getContainer();
    }
}