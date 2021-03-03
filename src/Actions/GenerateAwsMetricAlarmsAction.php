<?php

namespace Vinnygambiny\LaravelMonitoring\Actions;

use Aws\Exception\AwsException;
use Aws\Sdk;

class GenerateAwsMetricAlarmsAction
{
    protected $client;

    public function __construct(Sdk $client)
    {
        $this->client = $client->createClient('CloudWatch');
    }

    public function execute()
    {
        try {
            $alarms = $this->client->describeAlarms();

            return $alarms['MetricAlarms'];
        } catch (AwsException $e) {
            return [$e->getAwsErrorMessage()];
        }
    }
}
