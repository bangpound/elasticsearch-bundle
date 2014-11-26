<?php

namespace Caxy\Bundle\ElasticsearchBundle;

use Psr\Log\LoggerInterface;

class Client extends \Elasticsearch\Client
{
    /**
     * Client constructor
     *
     * @param array           $params Array of injectable parameters
     * @param LoggerInterface $logger
     * @param LoggerInterface $tracer
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
