<?php

namespace Reinfi\DependencyInjection\Service\Extractor;

use Reinfi\DependencyInjection\Exception\InjectionTypeUnknownException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
class YamlExtractor implements ExtractorInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Yaml
     */
    protected $yaml;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $injectionNamespace;

    /**
     * @param Yaml   $yaml
     * @param string $filePath
     * @param string $injectionNamespace
     */
    public function __construct(
        Yaml $yaml,
        string $filePath,
        string $injectionNamespace
    ) {
        $this->yaml = $yaml;
        $this->filePath = $filePath;
        $this->injectionNamespace = $injectionNamespace;
    }

    /**
     * @inheritdoc
     */
    public function getPropertiesInjections(string $className): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getConstructorInjections(string $className): array
    {
        $config = $this->getConfig($className);

        if (count($config) === 0) {
            return [];
        }

        $injections = [];
        foreach ($config as $spec) {
            $type = $spec['type'] ?? false;

            if ($type === false) {
                throw new \InvalidArgumentException('Missing property type for class ' . $className);
            }

            unset($spec['type']);

            $injections[] = $this->buildInjection(
                $type,
                $spec
            );
        }

        return $injections;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    protected function getConfig(string $className): array
    {
        if (!is_array($this->config)) {
            $this->config = $this->yaml::parse(
                file_get_contents($this->filePath)
            );
        }

        return $this->config[$className] ?? [];
    }

    /**
     * @param string $type
     * @param array  $spec
     *
     * @return InjectionInterface|object
     * @throws InjectionTypeUnknownException
     */
    protected function buildInjection(
        string $type,
        array $spec
    ): InjectionInterface {
        $injectionClass = $this->injectionNamespace . '\\' . $type;

        if (!class_exists($injectionClass)) {
            throw new InjectionTypeUnknownException('Invalid injection type ' . $type);
        }

        $reflectionClass = new \ReflectionClass($injectionClass);
        if ($reflectionClass->getConstructor() !== null) {
            return $reflectionClass->newInstance($spec);
        }

        $injection = new $injectionClass();
        foreach ($spec as $key => $value) {
            $injection->$key = $value;
        }

        return $injection;
    }
}
