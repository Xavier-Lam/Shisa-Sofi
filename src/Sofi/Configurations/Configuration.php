<?php

namespace Shisa\Sofi\Configurations;

use Shisa\Sofi\Configurations\ConfigurationObject;

class Configuration extends ConfigurationObject
{
    public ApplicationConfiguration $application;

    /**Enable debug mode or not */
    public bool $debug = false;

    /**Should this website enable HTTPS */
    public bool $secure = true;

    /**Hosts that allowed to be used */
    public array $hosts = [];

    public I18NConfiguration $i18n;

    /**
     * @param array $envs Environment variables used for updating configuration 
     */
    public final function __construct(array $envs = [])
    {
        $this->initializeConfiguration($envs);
        $this->updateConfiguration($envs);
    }

    protected function initializeConfiguration(array $envs = [])
    {
        $this->application = new ApplicationConfiguration();
        $this->i18n = new I18NConfiguration();
        if (defined('APP_DIR')) {
            $this->i18n->translationPath = APP_DIR . DIRECTORY_SEPARATOR . 'Lang';
        }
    }

    protected function updateConfiguration(array $envs = [])
    {
    }
}
