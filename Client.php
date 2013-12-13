<?php

namespace Ushios\Bundle\ElasticSearchBundle;

use Psr\Log\LoggerInterface;

class Client extends \Elasticsearch\Client {

    /**
     * Client constructor
     *
     * @param array $params Array of injectable parameters
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Psr\Log\LoggerInterface $tracer
     */
    public function __construct($params = array(), LoggerInterface $logger = null, LoggerInterface $tracer = null)
    {
        if ($logger || $tracer) {
            $params['logging'] = true;
        }
        if ($logger) {
            $params['logObject'] = $logger;
        }
        if ($tracer) {
            $params['traceObject'] = $tracer;
        }
        parent::__construct($params);
    }
}