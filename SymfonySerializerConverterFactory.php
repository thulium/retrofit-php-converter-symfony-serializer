<?php

declare(strict_types=1);

namespace Retrofit\Converter\SymfonySerializer;

use Retrofit\Core\Converter\ConverterFactory;
use Retrofit\Core\Converter\RequestBodyConverter;
use Retrofit\Core\Converter\ResponseBodyConverter;
use Retrofit\Core\Converter\StringConverter;
use Retrofit\Core\Type;
use Symfony\Component\Serializer\Serializer;

/**
 * {@link https://symfony.com/doc/current/components/serializer.html Symfony Serializer} converter factory implementation.
 *
 * @see ConverterFactory
 *
 * @api
 */
readonly class SymfonySerializerConverterFactory implements ConverterFactory
{
    public function __construct(
        private Serializer $serializer,
        private SymfonySerializerFormat $symfonySerializerFormat = SymfonySerializerFormat::JSON,
    )
    {
    }

    public function requestBodyConverter(Type $type): ?RequestBodyConverter
    {
        return new SymfonySerializerRequestBodyConverter($this->serializer, $this->symfonySerializerFormat, $type);
    }

    public function responseBodyConverter(Type $type): ?ResponseBodyConverter
    {
        return new SymfonySerializerResponseBodyConverter($this->serializer, $this->symfonySerializerFormat, $type);
    }

    public function stringConverter(Type $type): ?StringConverter
    {
        return null;
    }
}
