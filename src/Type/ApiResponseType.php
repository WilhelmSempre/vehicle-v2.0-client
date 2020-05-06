<?php

namespace App\Type;

/**
 * Class ApiResponseType
 * @package App\Type
 */
class ApiResponseType
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