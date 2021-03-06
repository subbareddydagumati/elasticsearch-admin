<?php

namespace App\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ElasticsearchNodeManagerTest extends WebTestCase
{
    public function testGetByNameNull()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $elasticsearchNodeManager = self::$container->get('App\Manager\ElasticsearchNodeManager');

        $node = $elasticsearchNodeManager->getByName(uniqid());

        $this->assertNull($node);
    }
}
