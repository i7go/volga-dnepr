<?php

declare(strict_types=1);

namespace App\Service\RequestConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class RequestParamValueResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return object[]
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        /**
         * Тип аргумента - класс, реализующий интерфейс RequestParamInterface.
         *
         * @phpstan-var  class-string<object>|object|null $argumentType
         */
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            return [];
        }

        /**
         * Проверим, что класс существует
         *
         * @var string $class
         */
        $class = $argumentType;
        if (!class_exists($class)) {
            return [];
        }

        // Проверим, что класс реализует интерфейс RequestParamInterface
        $reflClass = new \ReflectionClass($class);
        if (!\in_array(RequestParamInterface::class, $reflClass->getInterfaceNames(), true)) {
            return [];
        }

        // Создадим объект и десереализуем значения из запроса
        $object = new $class();

        /** @var Serializer */
        $serializer = $this->serializer;
        $serializer->denormalize(
            array_merge($request->query->all(), $request->request->all()),
            $class,
            XmlEncoder::FORMAT,
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $object,
            ]
        );

        // create and return the value object
        return [$object];
    }
}
