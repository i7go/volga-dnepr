<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class XmlResponseFactory
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * @param object|mixed[] $data
     * @param mixed[]        $headers
     * @param mixed[]        $context
     */
    public function create(
        object|array $data,
        string $xmlRoot,
        int $status = Response::HTTP_OK,
        array $headers = [],
        array $context = [],
    ): Response {
        return new Response(
            $this->serializer->serialize(
                $data,
                XmlEncoder::FORMAT,
                array_merge([
                    XmlEncoder::ROOT_NODE_NAME => $xmlRoot,
                    XmlEncoder::ENCODING => 'UTF-8',
                ], $context)
            ),
            $status,
            array_merge(
                $headers,
                [
                    'Content-Type' => 'application/xml;charset=UTF-8',
                ]
            )
        );
    }
}
