<?php

namespace Ushios\Bundle\ElasticSearchBundle;

/**
 * Deletes and recreates indexes
 */
class Resetter
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var
     */
    private $setup;

    /**
     * @var
     */
    private $index_params;

    /**
     * @var
     */
    private $mapping_params;

    /**
     * @param Client $client
     * @param array  $setup
     * @param array  $index_params
     * @param array  $mapping_params
     */
    public function __construct(Client $client, $setup, $index_params, $mapping_params)
    {
        $this->client = $client;
        $this->setup = $setup;
        $this->index_params = $index_params;
        $this->mapping_params = $mapping_params;
    }

    /**
     * Deletes and recreates all indexes
     */
    public function resetAllIndexes()
    {
        foreach (array_keys($this->setup) as $name) {
            $this->resetIndex($name);
        }
    }

    /**
     * Deletes and recreates the named index
     *
     * @param  string                    $indexName
     * @throws \InvalidArgumentException if no index exists for the given name
     */
    public function resetIndex($indexName)
    {
        $client = $this->client;
        $params = array(
            'index' => $indexName,
        );
        if ($client->indices()->exists($params)) {
            $result = $client->indices()->delete($params);
        }
        if (isset($this->index_params[$indexName])) {
            $params['body'] = $this->index_params[$indexName];
        }
        $result = $client->indices()->create($params);

        foreach ($this->setup[$indexName] as $typeName) {
            if (!$client->indices()->existsType(array('index' => $indexName, 'type' => $typeName)) && isset($this->mapping_params[$typeName])) {
                $params = array(
                    'index' => $indexName,
                    'type' => $typeName,
                    'body' => array(
                        $typeName => $this->mapping_params[$typeName],
                    ),
                );
                $result = $client->indices()->putMapping($params);
            }
        }
    }

    /**
     * Deletes and recreates a mapping type for the named index
     *
     * @param  string                    $indexName
     * @param  string                    $typeName
     * @throws \InvalidArgumentException if no index or type mapping exists for the given names
     */
    public function resetIndexType($indexName, $typeName)
    {
        $client = $this->client;
        if (!isset($this->setup[$indexName]) || !in_array($typeName, $this->setup[$indexName])) {
            throw new \InvalidArgumentException(sprintf('The mapping for index "%s" and type "%s" does not exist.', $indexName, $typeName));
        }

        $settings = $this->mapping_params[$typeName];
        $params = array(
            'index' => $indexName,
            'type' => $typeName,
            'body' => array($typeName => $this->mapping_params[$typeName]),
        );
        $result = $client->indices()->putMapping($params);
    }
}
