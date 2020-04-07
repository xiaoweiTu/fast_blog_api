<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Hyperf\Elasticsearch\ClientBuilderFactory;

/**
 * @Command
 */
class ES extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('es');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        $this->line('Hello Hyperf!', 'info');

        $es = $this->container->get(ClientBuilderFactory::class);
        $es = $es->create();
        $es->setHosts(['192.168.134.95:9200']);
        $es->setConnectionPool('\Elasticsearch\ConnectionPool\SimpleConnectionPool');
        $es->setRetries(2);
        $client = $es->build();

        var_dump($client->get(['id'=>'M191223371757100','index'=>'order','type'=>'odi_order']));
    }
}
