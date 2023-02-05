<?php

namespace App;

use Psr\Container\ContainerInterface;
use Shisa\Sofi\Application\Bootstrap as Base;
use Shisa\Sofi\Configurations\Configuration;
use Symfony\Component\Translation\Loader\MoFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap extends Base
{
    /**
     * @param \DI\Container $container
     */
    protected static function configureContainer(ContainerInterface $container)
    {
        $configuration = $container->get(Configuration::class);

        // i18n
        if ($configuration->i18n->enabled) {
            $container->set(
                TranslatorInterface::class,
                static function (Configuration $configuration) {
                    $defaultLocale = $configuration->i18n->locales[0];
                    $translator = new Translator($defaultLocale);

                    // 读取语言配置
                    $langDir = $configuration->i18n->translationPath;
                    $translator->addLoader(MoFileLoader::class, new MoFileLoader());
                    $langs = array_slice(scandir($langDir), 2);
                    foreach ($langs as $lang) {
                        foreach (glob("$langDir/$lang/LC_MESSAGES/*.mo") as $file) {
                            $domain = basename($file, '.mo');
                            $domain === 'default' && $domain = null;
                            $translator->addResource(MoFileLoader::class, $file, $lang, $domain);
                        }
                    }

                    return $translator;
                }
            );
        }
    }
}
