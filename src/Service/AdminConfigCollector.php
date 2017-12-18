<?php

namespace App\Service;

use App\Model\AdminConfigInterface;

class AdminConfigCollector
{
    /** @var AdminConfigInterface[] */
    private $configurations;

    public function __construct(array $configurations)
    {
        $this->configurations = $configurations;
    }

    public function getConfigForSegment($segment): AdminConfigInterface
    {
        foreach ($this->configurations as $configuration) {
            if ($configuration->getName() === $segment) {
                return $configuration;
            }
        }

        throw new \OutOfBoundsException(sprintf('Segment "%s" not registered.'));
    }
}

