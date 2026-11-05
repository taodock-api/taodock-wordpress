# TaoDock AI Provider for WordPress

This plugin connects the WordPress AI Client to TaoDock, a unified AI API
gateway. WordPress stores the API key on its Connectors screen, while the
plugin discovers the text-chat models available to that key and sends text
generation requests through the OpenAI-compatible chat API.

## Requirements

- WordPress 7.0 or newer
- PHP 8.0 or newer
- A TaoDock API key from https://taodock.com/keys

## Install

For local installation, copy the taodock-ai-provider directory into
wp-content/plugins and activate TaoDock AI Provider. Then open
Settings > Connectors and add the TaoDock API key.

To create the upload archive:

    npm run build

The command writes dist/taodock-ai-provider.zip.

## Data flow

After an administrator adds an API key, WordPress can request the key's model
list from https://taodock.com/v1/models. When a WordPress feature asks the AI
Client to generate text, the prompt and selected generation options are sent
to https://taodock.com/v1/chat/completions with that key. TaoDock may retain
service and usage logs as described in its privacy policy:
https://taodock.com/privacy-policy.

The plugin contains no analytics, advertising, remote executable code, or
secondary service endpoint.

## Development

The PHP regression test uses the AI Client bundled with WordPress:

    WP_AI_CLIENT_AUTOLOAD=/path/to/wordpress/wp-includes/php-ai-client/autoload.php php tests/run.php

Run PHP syntax checks before packaging:

    find taodock-ai-provider tests -name '*.php' -print0 | xargs -0 -n1 php -l

## Support

Report reproducible plugin issues at
https://github.com/taodock-api/taodock-wordpress/issues.

## License

GPL-2.0-or-later. See LICENSE.
