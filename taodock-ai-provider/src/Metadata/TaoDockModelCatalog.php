<?php

/**
 * Converts TaoDock model discovery responses into AI Client metadata.
 *
 * @package TaoDock\WordPressAiProvider
 */

declare(strict_types=1);

namespace TaoDock\WordPressAiProvider\Metadata;

use TaoDock\WordPressAiProvider\Provider\TaoDockProvider;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleModelMetadataDirectory;

class TaoDockModelCatalog extends AbstractOpenAiCompatibleModelMetadataDirectory
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
            $data
        );
    }

    protected function parseResponseToModelMetadataList(Response $response): array
    {
        $responseData = $response->getData();
        if (!is_array($responseData) || !isset($responseData['data']) || !is_array($responseData['data'])) {
            return [];
        }

        $capabilities = [
            CapabilityEnum::textGeneration(),
            CapabilityEnum::chatHistory(),
        ];
        $options = [
            new SupportedOption(OptionEnum::systemInstruction()),
            new SupportedOption(OptionEnum::maxTokens()),
            new SupportedOption(OptionEnum::outputMimeType(), ['text/plain']),
            new SupportedOption(OptionEnum::customOptions()),
        ];

        $modelsById = [];
        foreach ($responseData['data'] as $entry) {
            if (!is_array($entry) || !isset($entry['id']) || !is_string($entry['id'])) {
                continue;
            }

            $modelId = trim($entry['id']);
            if ($modelId === '') {
                continue;
            }

            $endpointTypes = $entry['supported_endpoint_types'] ?? null;
            if (!is_array($endpointTypes) || !in_array('openai', $endpointTypes, true)) {
                continue;
            }

            $modelsById[$modelId] = new ModelMetadata(
                $modelId,
                $modelId,
                $capabilities,
                $options
            );
        }

        return array_values($modelsById);
    }
}
