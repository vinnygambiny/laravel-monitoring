<?php

namespace Vinnygambiny\LaravelMonitoring\Actions;

use Aws\Exception\AwsException;
use Aws\Sdk;

class GenerateAwsGraphAction
{
    protected $client;

    public function __construct(Sdk $sdk)
    {
        $this->client = $sdk->createClient('CloudWatch');
    }

    public function execute()
    {
        $graphs = [];
        $configGraphs = array_filter(config('monitoring.aws.graphs', []), function ($configGraph) {
            return $this->validateConfigGraph($configGraph);
        });

        foreach ($configGraphs as $configGraph) {
            $graphs[$this->getGraphName($configGraph)] = $this->getMetricStatistics(
                $configGraph['namespace'],
                $configGraph['metricName'],
                $configGraph['dimensions']
            );
        }

        return $graphs;
    }

    protected function validateConfigGraph($configGraph)
    {
        return isset($configGraph['namespace']) && isset($configGraph['metricName']) && isset($configGraph['dimensions']) && is_array($configGraph['dimensions']);
    }

    protected function getGraphName($configGraph)
    {
        return str_replace('AWS/', '', $configGraph['namespace']) . ' - ' . $configGraph['metricName'];
    }

    protected function getMetricStatistics(string $namespace,
        string $metricName = 'CPUUtilization',
        ?array $dimensions = [],
        string $startTime = '-1 hours',
        string $endTime = 'now',
        int $period = 300,
        array $statistics = ['Average'],
        string $unit = 'Percent'
    ): array {
        try {
            $result = $this->client->getMetricStatistics(([
                'Namespace' => $namespace,
                'MetricName' => $metricName,
                'Dimensions' => $dimensions,
                'StartTime' => strtotime($startTime),
                'EndTime' => strtotime($endTime),
                'Period' => $period,
                'Statistics' => $statistics,
                'Unit' => $unit
            ]));

            return $result['Datapoints'];
        } catch (AwsException $e) {
            return [$e->getAwsErrorMessage()];
        }
    }
}
