<?php

declare(strict_types=1);

namespace TwigCsFixer\Environment;

use Symfony\Bridge\Twig\TokenParser\DumpTokenParser;
use Symfony\Bridge\Twig\TokenParser\FormThemeTokenParser;
use Symfony\Bridge\Twig\TokenParser\StopwatchTokenParser;
use Symfony\Bridge\Twig\TokenParser\TransChoiceTokenParser;
use Symfony\Bridge\Twig\TokenParser\TransDefaultDomainTokenParser;
use Symfony\Bridge\Twig\TokenParser\TransTokenParser;
use Twig\Environment;
use Twig\Extra\Cache\TokenParser\CacheTokenParser;
use Twig\Loader\ArrayLoader;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Provide stubs for all filters, functions, tests and tags that are not defined in twig's core.
 */
final class StubbedEnvironment extends Environment
{
    /**
     * @var array<string, TwigFilter|null>
     */
    private array $stubFilters = [];

    /**
     * @var array<string, TwigFunction|null>
     */
    private array $stubFunctions = [];

    /**
     * @var array<string, TwigTest|null>
     */
    private array $stubTests = [
        'divisible' => null, // Allow 'divisible by'
        'same'      => null, // Allow 'same as'
    ];

    public function __construct()
    {
        parent::__construct(new ArrayLoader());

        $this->addTokenParser(new DumpTokenParser());
        $this->addTokenParser(new FormThemeTokenParser());
        $this->addTokenParser(new StopwatchTokenParser(false));
        $this->addTokenParser(new TransDefaultDomainTokenParser());
        $this->addTokenParser(new TransTokenParser());

        // TODO: Remove when dropping support for symfony/twig-bridge@4.4
        if (class_exists(TransChoiceTokenParser::class)) {
            // @codeCoverageIgnoreStart
            /** @var TokenParserInterface $transChoiceTokenParser */
            $transChoiceTokenParser = new TransChoiceTokenParser();
            $this->addTokenParser($transChoiceTokenParser);
            // @codeCoverageIgnoreEnd
        }

        // Optional dependency
        if (class_exists(CacheTokenParser::class)) {
            $this->addTokenParser(new CacheTokenParser());
        }
    }

    /**
     * @param string $name
     */
    public function getFilter($name): ?TwigFilter
    {
        if (!\array_key_exists($name, $this->stubFilters)) {
            $this->stubFilters[$name] = new TwigFilter('stub');
        }

        return $this->stubFilters[$name];
    }

    /**
     * @param string $name
     */
    public function getFunction($name): ?TwigFunction
    {
        if (!\array_key_exists($name, $this->stubFunctions)) {
            $this->stubFunctions[$name] = new TwigFunction('stub');
        }

        return $this->stubFunctions[$name];
    }

    /**
     * @param string $name
     */
    public function getTest($name): ?TwigTest
    {
        if (!\array_key_exists($name, $this->stubTests)) {
            $this->stubTests[$name] = new TwigTest('stub');
        }

        return $this->stubTests[$name];
    }
}
