<?php

namespace Ushios\Bundle\ElasticSearchBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ushios\Bundle\ElasticSearchBundle\DependencyInjection\UshiosElasticSearchExtension;

class UshiosElasticSearchExtensionTest extends TestCase
{
    /**
     * service container.
     */
    protected $container;

    /**
     * Setup test.
     *  @return null
     */
    public function setUp()
    {
        if (!class_exists('Elasticsearch\\Client')) {
            $this->markTestSkipped('Elasticsearch is not available.');
        }

        $this->container = new ContainerBuilder();

        $config = array(
            'default' => array(
                'hosts' => array('localhost'),
            ),
        );

        $extension = new UshiosElasticSearchExtension();
        $extension->load(array(array('client' => $config)), $this->container);

        parent::setUp();
    }

    /**
     * Test getting aws client.
     * @return null
     */
    public function testGetEsClient()
    {
        $es = $this->container->get('ushios_elastic_search_client.default');
        
        $this->assertInstanceOf('\ElasticSearch\Client', $es);
    }
}
