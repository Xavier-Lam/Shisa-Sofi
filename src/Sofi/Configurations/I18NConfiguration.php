<?php

namespace Shisa\Sofi\Configurations;

class I18NConfiguration extends ConfigurationObject
{
    public bool $enabled = true;

    public array $locales = ['en_US'];

    public string $translationPath = '';
}
