<?php

declare(strict_types=1);

namespace Retrofit\Converter\SymfonySerializer;

use Psr\Http\Message\StreamInterface;
use Retrofit\Core\Converter\ResponseBodyConverter;
use Retrofit\Core\Type;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Serializer;

readonly class SymfonySerializerResponseBodyConverter implements ResponseBodyConverter
{
    public function __construct(
        private Serializer $serializer,
        private SymfonySerializerFormat $symfonySerializerFormat,
        private Type $type,
    )
    {
    }

    public function convert(StreamInterface $value): mixed
    {
        if ($this->type->isA(StreamInterface::class)) {
            return $value;
        }

        $contents = $value->getContents();

        $typeIsArray = $this->type->isA('array');
        try {
            $type = $this->type->getRawType();
            if ($typeIsArray) {
                $type = "{$this->type->getParametrizedType()}[]";
            }
            return $this->serializer->deserialize($contents, $type, $this->symfonySerializerFormat->value);
        } catch (NotNormalizableValueException|NotEncodableValueException) {
            // If type is a scalar or scalar list (e.g.: string or string[]), whe must try to decode value.
            if ($typeIsArray) {
                return $this->serializer->decode($contents, $this->symfonySerializerFormat->value);
            }
            return $contents;
        }
    }
}
