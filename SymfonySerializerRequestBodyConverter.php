<?php

declare(strict_types=1);

namespace Retrofit\Converter\SymfonySerializer;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;
use Retrofit\Core\Converter\RequestBodyConverter;
use Retrofit\Core\Type;
use Symfony\Component\Serializer\Serializer;

/**
 * {@link RequestBodyConverter} implementation.
 *
 * @internal
 */
readonly class SymfonySerializerRequestBodyConverter implements RequestBodyConverter
{
    public function __construct(
        private Serializer $serializer,
        private SymfonySerializerFormat $symfonySerializerFormat,
        private Type $type,
    )
    {
    }

    public function convert(mixed $value): StreamInterface
    {
        if ($this->type->isA(StreamInterface::class) && $value instanceof StreamInterface) {
            return $value;
        }

        return Utils::streamFor($this->serializer->serialize($value, $this->symfonySerializerFormat->value));
    }
}
