<?php

namespace App\Configurations;

use Shisa\Sofi\Configurations\Configuration;

abstract class Common extends Configuration
{
    protected function updateConfiguration(array $envs = [])
    {
        $this->application->name = 'Shisa/Sofi';
        $this->application->version = '1.0.0';
    }
}
