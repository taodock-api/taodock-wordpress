<?php

/**
 * Focused behavior checks against the AI Client bundled with WordPress.
 */

declare(strict_types=1);

namespace {
    $autoload = getenv('WP_AI_CLIENT_AUTOLOAD');
    if (!is_string($autoload) || $autoload === '' || !is_file($autoload)) {
        fwrite(STDERR, "Set WP_AI_CLIENT_AUTOLOAD to the WordPress AI Client autoloader.\n");
        exit(2);
    }

    require dirname(dirname($autoload)) . '/compat.php';
    require $autoload;

    define('ABSPATH', __DIR__ . '/wordpress/');

    $GLOBALS['taodock_test_actions'] = [];

    function add_action(string $hook, callable|string|array $callback, int $priority = 10): void
    {
        $GLOBALS['taodock_test_actions'][$hook][$priority][] = $callback;
    }

    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }

    require dirname(__DIR__) . '/taodock-ai-provider/taodock-ai-provider.php';
}

namespace TaoDock\WordPressAiProvider\Tests {
    use TaoDock\WordPressAiProvider\Metadata\TaoDockModelCatalog;
    use TaoDock\WordPressAiProvider\Models\TaoDockChatModel;
    use WordPress\AiClient\Providers\Http\DTO\Request;
    use WordPress\AiClient\Providers\Http\DTO\Response;
    use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;

    class InspectableModelCatalog extends TaoDockModelCatalog
    {
        public function parse(Response $response): array
        {
            return parent::parseResponseToModelMetadataList($response);
        }

        public function request(HttpMethodEnum $method, string $path): Request
        {
            return parent::createRequest($method, $path);
        }
    }

    class InspectableChatModel extends TaoDockChatModel
    {
        public function request(HttpMethodEnum $method, string $path, array $data): Request
        {
            return parent::createRequest(
                $method,
                $path,
                ['Content-Type' => 'application/json'],
                $data
            );
        }
    }
}

namespace {
    use TaoDock\WordPressAiProvider\Provider\TaoDockProvider;
    use TaoDock\WordPressAiProvider\Tests\InspectableChatModel;
    use TaoDock\WordPressAiProvider\Tests\InspectableModelCatalog;
    use WordPress\AiClient\AiClient;
    use WordPress\AiClient\Providers\Http\DTO\Response;
    use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;

    function expect_same(mixed $expected, mixed $actual, string $label): void
    {
        if ($expected !== $actual) {
            fwrite(
                STDERR,
                $label . "\nExpected: " . var_export($expected, true) .
                "\nActual: " . var_export($actual, true) . "\n"
            );
            exit(1);
        }
    }

    $callbacks = $GLOBALS['taodock_test_actions']['init'][5] ?? [];
    expect_same(1, count($callbacks), 'The provider must register once on init priority 5.');
    $callbacks[0]();

    $registry = AiClient::defaultRegistry();
    expect_same(true, $registry->hasProvider(TaoDockProvider::class), 'Provider registration failed.');

    $providerMetadata = TaoDockProvider::metadata();
    expect_same('taodock', $providerMetadata->getId(), 'Unexpected provider ID.');
    expect_same('TaoDock', $providerMetadata->getName(), 'Unexpected provider name.');
    expect_same(
        'https://taodock.com/keys',
        $providerMetadata->getCredentialsUrl(),
        'Unexpected key URL.'
    );
    expect_same(
        'https://taodock.com/v1/chat/completions',
        TaoDockProvider::url('chat/completions'),
        'Unexpected chat URL.'
    );
    expect_same(
        true,
        is_file((string) $providerMetadata->getLogoPath()),
        'Provider icon is missing.'
    );

    $catalog = new InspectableModelCatalog();
    $payload = [
        'object' => 'list',
        'data' => [
            [
                'id' => 'chat-alpha',
                'supported_endpoint_types' => ['openai'],
            ],
            [
                'id' => 'image-alpha',
                'supported_endpoint_types' => ['image-generation'],
            ],
            [
                'id' => 'chat-beta',
                'supported_endpoint_types' => ['anthropic', 'openai'],
            ],
            [
                'id' => 'metadata-missing',
            ],
            [
                'id' => '',
                'supported_endpoint_types' => ['openai'],
            ],
        ],
    ];
    $models = $catalog->parse(
        new Response(200, ['Content-Type' => 'application/json'], json_encode($payload))
    );
    expect_same(['chat-alpha', 'chat-beta'], array_map(
        static fn ($model): string => $model->getId(),
        $models
    ), 'Model filtering must keep only OpenAI-compatible chat models.');

    $capabilities = array_map(
        static fn ($capability): string => $capability->value,
        $models[0]->getSupportedCapabilities()
    );
    expect_same(['text_generation', 'chat_history'], $capabilities, 'Unexpected capabilities.');

    $options = array_map(
        static fn ($option): string => $option->getName()->value,
        $models[0]->getSupportedOptions()
    );
    expect_same(
        ['systemInstruction', 'maxTokens', 'outputMimeType', 'customOptions'],
        $options,
        'Unexpected text options.'
    );

    $listRequest = $catalog->request(HttpMethodEnum::GET(), 'models');
    expect_same('https://taodock.com/v1/models', $listRequest->getUri(), 'Unexpected model-list URL.');
    expect_same([], $listRequest->getHeaders(), 'Model discovery must not add tracking headers.');

    $chatModel = new InspectableChatModel($models[0], $providerMetadata);
    $chatRequest = $chatModel->request(
        HttpMethodEnum::POST(),
        'chat/completions',
        ['model' => 'chat-alpha', 'messages' => []]
    );
    expect_same('https://taodock.com/v1/chat/completions', $chatRequest->getUri(), 'Unexpected generation URL.');
    expect_same(
        ['model' => 'chat-alpha', 'messages' => []],
        json_decode((string) $chatRequest->getBody(), true),
        'Generation request data changed.'
    );

    echo "All TaoDock provider checks passed.\n";
}
