# TaoDock AI for WordPress – 40+ AI Models (GPT, Claude, Gemini)

[![Verify](https://github.com/taodock-api/taodock-wordpress/actions/workflows/verify.yml/badge.svg)](https://github.com/taodock-api/taodock-wordpress/actions/workflows/verify.yml)
[![WordPress: 7.0+](https://img.shields.io/badge/WordPress-7.0%2B-21759b?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![PHP: 8.0+](https://img.shields.io/badge/PHP-8.0%2B-777bb4?logo=php&logoColor=white)](https://www.php.net/)
[![License: GPL-2.0-or-later](https://img.shields.io/badge/License-GPL--2.0--or--later-blue)](https://www.gnu.org/licenses/gpl-2.0.html)

Use any AI model available on **[TaoDock](https://taodock.com/)** — the API gateway
for 40+ AI providers — directly inside WordPress with **one key and one bill**.

This plugin registers TaoDock as a cloud provider for the **WordPress AI Client**
bundled with WordPress 7.0 and newer. After you paste an API key on the
Connectors screen, WordPress automatically discovers every chat model your key
can access and sends generation requests through TaoDock's OpenAI-compatible
chat API.

## Features

- **One key, every model** — access OpenAI, Anthropic, Google, and 40+ other
  providers through a single TaoDock API key.
- **Key-scoped model discovery** — the plugin lists exactly the models your key
  can use, with no manual model configuration.
- **First-party AI Client integration** — works with every WordPress feature
  built on the AI Client: text generation, chat history, system instructions,
  output-token limits, and custom OpenAI-compatible options.
- **Key stored by WordPress, not the plugin** — the API key lives on the
  standard Connectors screen; the plugin adds no own settings page.
- **No analytics, no ads, no remote executable code** — the only endpoints it
  talks to are `https://taodock.com/v1/models` and
  `https://taodock.com/v1/chat/completions`.

## Requirements

- WordPress 7.0 or newer (the AI Client ships with WordPress core)
- PHP 8.0 or newer
- A TaoDock API key — [create one for free](https://taodock.com/keys)

## Quick start

1. Get an API key from <https://taodock.com/keys> (a free tier is available).
2. In WordPress, open **Settings → Connectors**, select **TaoDock**, and paste
   the key.
3. Use any AI-enabled WordPress feature and pick a model — browse the available
   models at <https://taodock.com/models>.

### Install from an archive

Run the packaging step and upload the zip through
**Plugins → Add New → Upload Plugin**:

    npm run build
    # dist/taodock-ai-provider.zip

### Install from source

Copy the `taodock-ai-provider` directory into `wp-content/plugins` and activate
**TaoDock AI**.

## How it works

```
WordPress feature
   │  WordPress AI Client
   ▼
TaoDock AI Provider  ──GET /v1/models──▶  key-scoped model list
   │
   ──POST /v1/chat/completions──▶  40+ AI providers, one bill
```

The plugin sends the model ID, prompt content, conversation history, and
generation options to TaoDock together with the API key. No request is sent
until a key is configured and an AI-enabled feature is used. TaoDock may retain
service and usage logs — see the
[privacy policy](https://taodock.com/privacy-policy) and
[service documentation](https://docs.taodock.com).

## FAQ

**Which models appear in WordPress?**
Every model your key can access that advertises an OpenAI-compatible chat
endpoint. Use the exact model IDs shown at <https://taodock.com/models>.

**Where do I review usage and balance?**
Usage logs: <https://taodock.com/usage-logs/common> · Wallet:
<https://taodock.com/wallet>.

**Does the plugin send any other traffic?**
No. There is no telemetry, no update check of its own, and no secondary service
endpoint.

## Development

The PHP regression test uses the AI Client bundled with WordPress:

    WP_AI_CLIENT_AUTOLOAD=/path/to/wordpress/wp-includes/php-ai-client/autoload.php php tests/run.php

Run PHP syntax checks before packaging:

    find taodock-ai-provider tests -name '*.php' -print0 | xargs -0 -n1 php -l

Continuous checks run on every push via the
[Verify workflow](.github/workflows/verify.yml).

## Support

Report reproducible plugin issues at
<https://github.com/taodock-api/taodock-wordpress/issues>.

## More TaoDock integrations

TaoDock ships first-party integrations for other tools you may already use:

| Integration | Repository |
|---|---|
| n8n nodes | [taodock-api/taodock-n8n-nodes](https://github.com/taodock-api/taodock-n8n-nodes) |
| Dify tools & plugins | [taodock-api/taodock-dify-tools](https://github.com/taodock-api/taodock-dify-tools) |
| AstrBot plugin | [taodock-api/taodock-astrbot-plugin](https://github.com/taodock-api/taodock-astrbot-plugin) |
| MCP server | [taodock-api/taodock-mcp](https://github.com/taodock-api/taodock-mcp) |
| TypeScript AI SDK | [taodock-api/taodock-ai-sdk](https://github.com/taodock-api/taodock-ai-sdk) |

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
