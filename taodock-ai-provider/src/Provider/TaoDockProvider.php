<?php

/**
 * WordPress AI Client provider for the TaoDock gateway.
 *
 * @package TaoDock\WordPressAiProvider
 */

declare(strict_types=1);

namespace TaoDock\WordPressAiProvider\Provider;

use WordPress\AiClient\Common\Exception\RuntimeException;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiProvider;
use WordPress\AiClient\Providers\ApiBasedImplementation\ListModelsApiBasedProviderAvailability;
use WordPress\AiClient\Providers\Contracts\ModelMetadataDirectoryInterface;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\DTO\ProviderMetadata;
use WordPress\AiClient\Providers\Enums\ProviderTypeEnum;
use WordPress\AiClient\Providers\Http\Enums\RequestAuthenticationMethod;
use WordPress\AiClient\Providers\Models\Contracts\ModelInterface;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use TaoDock\WordPressAiProvider\Metadata\TaoDockModelCatalog;
use TaoDock\WordPressAiProvider\Models\TaoDockChatModel;

class TaoDockProvider extends AbstractApiProvider
{
    protected static function baseUrl(): string
    {
        return 'https://taodock.com/v1';
    }

    protected static function createModel(
        ModelMetadata $modelMetadata,
        ProviderMetadata $providerMetadata
    ): ModelInterface {
        foreach ($modelMetadata->getSupportedCapabilities() as $capability) {
            if ($capability->isTextGeneration()) {
                return new TaoDockChatModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException(
            'The requested TaoDock model does not support text generation: ' .
            $modelMetadata->getId()
        );
    }

    protected static function createProviderMetadata(): ProviderMetadata
    {
        return new ProviderMetadata(
            'taodock',
            'TaoDock',
            ProviderTypeEnum::cloud(),
            'https://taodock.com/keys',
            RequestAuthenticationMethod::apiKey(),
            __('Access compatible text models through the TaoDock AI API gateway.', 'taodock-ai-provider'),
            dirname(__DIR__, 2) . '/assets/icon.png'
        );
    }

    protected static function createProviderAvailability(): ProviderAvailabilityInterface
    {
        return new ListModelsApiBasedProviderAvailability(
            static::modelMetadataDirectory()
        );
    }

    protected static function createModelMetadataDirectory(): ModelMetadataDirectoryInterface
    {
        return new TaoDockModelCatalog();
    }
}
