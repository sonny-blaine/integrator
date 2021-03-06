<?php

use SonnyBlaine\Integrator\ConnectionManager;
use SonnyBlaine\Integrator\Services\RequestService;
use SonnyBlaine\Integrator\Destination;
use SonnyBlaine\Integrator\Source;
use SonnyBlaine\Integrator\Rabbit;
use SonnyBlaine\Integrator\Services\SourceService;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\Integrator\Services\IntegratorService;
use SonnyBlaine\Integrator\Search;
use SonnyBlaine\Integrator\Services\SearchService;

#destination services and repositories
$app['destination.request.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Destination\Request::class);
};

$app['destination.request.creator'] = function () use ($app) {
    return new Destination\RequestCreator($app['connection_manager.service']);
};

#source services and repositories
$app['source.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Source\Source::class);
};

$app['source.service'] = function () use ($app) {
    return new SourceService($app['source.repository']);
};

$app['source.request.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Source\Request::class);
};

#another services
$app['connection_manager.service'] = function () use ($app) {
    return new ConnectionManager();
};

$app['request.service'] = function () use ($app) {
    return new RequestService(
        $app['destination.request.creator'],
        $app['source.request.repository'],
        $app['destination.request.repository']
    );
};


$app['integrator.service'] = function () use ($app) {
    return new IntegratorService(
        $app['orm.em']->getConnection(),
        $app['source.service'],
        $app['request.service'],
        $app['rabbit.producer']['request_creator_producer'],
        $app['rabbit.producer']['integrator_producer']
    );
};

$app['bridge.factory'] = function () use ($app) {
    return new BridgeFactory($app);
};

/* Rabbit Consumers */
$app['integrator_consumer'] = function () use ($app) {
    return new Rabbit\IntegratorConsumer(
        $app['request.service'],
        $app['rabbit.producer']['integrator_producer'],
        $app['bridge.factory']
    );
};

$app['request_creator_consumer'] = function () use ($app) {
    return new Rabbit\RequestCreatorConsumer(
        $app['request.service'],
        $app['rabbit.producer']['request_creator_producer'],
        $app['rabbit.producer']['integrator_producer']
    );
};

$app['search.source.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Search\SearchSource::class);
};

$app['search.service'] = function () use ($app) {
    return new SearchService($app['search.source.repository'], $app['bridge.factory']);
};

# supervisor services
$app['http_message_factory'] = function () {
    return new \SonnyBlaine\Integrator\Services\MessageFactory();
};

$app['supervisor.http_client'] = function () {
    return new \SonnyBlaine\Integrator\Services\HttpClient(['auth' => [SUPERVISOR_USER, SUPERVISOR_PASSWORD]]);
};

$app['supervisor.xml_rpc.http_adapter_transport'] = function () use ($app) {
    return new \fXmlRpc\Transport\HttpAdapterTransport($app['http_message_factory'], $app['supervisor.http_client']);
};

$app['supervisor.xml_rpc.client'] = function () use ($app) {
    return new \fXmlRpc\Client(
        'http://' . SUPERVISOR_HOST . ':' . SUPERVISOR_PORT . '/RPC2',
        $app['supervisor.xml_rpc.http_adapter_transport']
    );
};

$app['supervisor.xml_rpc.connector'] = function () use ($app) {
    return new \Supervisor\Connector\XmlRpc($app['supervisor.xml_rpc.client']);
};

$app['supervisor'] = function () use ($app) {
    return new \Supervisor\Supervisor($app['supervisor.xml_rpc.connector']);
};