<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Tests\Configuration;

use Doctrine\Common\Inflector\Inflector;
use PhpGuild\ResourceBundle\Tests\Configuration\TestCase\Test001Trait;
use PhpGuild\ResourceBundle\Tests\Configuration\TestCase\Test002Trait;
use PhpGuild\ResourceBundle\Tests\Service\ConfigurationProcessor;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ConfigurationProcessorTest
 */
class ConfigurationProcessorTest extends KernelTestCase
{
    use DefaultModelTrait;
//    use Test001Trait;
    use Test002Trait;

    /** @var array $configurations */
    private $configurations;

    /**
     * setUp
     *
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->configurations = $kernel->getContainer()->get(ConfigurationProcessor::class)->getCollection();
    }

    /**
     * assertObject
     *
     * @param        $expected
     * @param        $actual
     * @param string $path
     *
     * @throws \ReflectionException
     */
    private static function assertObject($expected, $actual, string $path = ''): void
    {
        $expectedMethods = self::getObjectMethods($expected);

        self::assertSame($expectedMethods, self::getObjectMethods($actual));

        foreach ($expectedMethods as $property => $method) {
            $expectedValues = $expected->{$method}();
            $actualValues = $actual->{$method}();
            $isArray = is_array($expectedValues);

            if (!$isArray) {
                $expectedValues = [ $expectedValues ];
                $actualValues = [ $actualValues ];
            }

            foreach ($expectedValues as $index => $expectedValue) {
                $actualValue = $actualValues[$index] ?? null;
                $currentPath = $path . '.' . $property . ($isArray ? '[' . $index . ']' : '');

                if (\is_object($expectedValue) && \is_object($actualValue)) {
                    self::assertObject($expectedValue, $actualValue, $currentPath);
                    continue;
                }

                self::assertSame($expectedValue, $actualValue, $currentPath);
            }
        }
    }

    /**
     * getObjectMethods
     *
     * @param object $object
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    private static function getObjectMethods(object $object): array
    {
        $methods = [];

        foreach ((new \ReflectionClass($object))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();

            foreach ([ 'get', 'is' ] as $prefix) {
                $length = strlen($prefix);

                if (strncmp($methodName, $prefix, $length)) {
                    continue;
                }

                $methods[Inflector::camelize(substr($methodName, $length))] = $methodName;
            }
        }

        return $methods;
    }
}
