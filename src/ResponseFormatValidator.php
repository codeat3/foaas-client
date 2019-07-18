<?php

namespace Codeat3\FoaasClient;

use Codeat3\FoaasClient\Response\FoaasResponse;
use Codeat3\FoaasClient\Exceptions\InvalidResponse;

class ResponseFormatValidator
{
    /**
     * Validates and returns the arrays.
     *
     * @param array $responseFormats
     *
     * @return array
     */
    public static function validate(array $responseFormats): array
    {
        if (
            $responseFormats
            && is_array($responseFormats)
            && count($responseFormats) > 0
        ) {
            return array_map(static function (string $class) {
                if (! is_a($class, FoaasResponse::class, true)) {
                    throw new InvalidResponse('A class needs to implement \'Codeat3\FoaasClient\Response\FoaasResponse\'');
                }

                return $class;
            }, $responseFormats);
        }

        return [];
    }
}
