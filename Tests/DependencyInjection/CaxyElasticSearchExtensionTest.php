<?php

namespace Caxy\Bundle\ElasticSearchBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Caxy\Bundle\ElasticSearchBundle\DependencyInjection\CaxyElasticSearchExtension;

class CaxyElasticSearchExtensionTest extends TestCase
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

        $extension = new CaxyElasticSearchExtension();
        $extension->load(array(array('client' => $config)), $this->container);

        parent::setUp();
    }

    /**
     * Test getting aws client.
     * @return null
     */
    public function testGetEsClient()
    {
        $es = $this->container->get('caxy_elastic_search_client.default');

        $this->assertInstanceOf('\ElasticSearch\Client', $es);
    }
}
