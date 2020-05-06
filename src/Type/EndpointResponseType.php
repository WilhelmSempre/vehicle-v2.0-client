<?php

namespace App\Type;

/**
 * Class EndpointResponseType
 * @package App\Type
 */
class EndpointResponseType
{
    const JSON = 'json';
    const XML = 'xml';

    /**
     * @return array|string[]
     */
    public function getTypes(): array
    {
        return [
            self::JSON,
            self::XML,
        ];
    }
}