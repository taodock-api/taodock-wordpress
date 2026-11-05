<?php

/**
 * Sends text generation requests to TaoDock's chat endpoint.
 *
 * @package TaoDock\WordPressAiProvider
 */

declare(strict_types=1);

namespace TaoDock\WordPressAiProvider\Models;

use TaoDock\WordPressAiProvider\Provider\TaoDockProvider;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;

class TaoDockChatModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    protected function createRequest(
        HttpMethodEnum $method,
        string $path,
        array $headers = [],
        $data = null
    ): Request {
        return new Request(
            $method,
            TaoDockProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }
}
