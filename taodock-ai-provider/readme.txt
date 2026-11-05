=== TaoDock AI – 40+ AI Models (GPT, Claude, Gemini) ===
Tags: ai, text generation, api, gateway
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect the WordPress AI Client to text-chat models available through TaoDock.

== Description ==

TaoDock is a unified AI API gateway. This plugin registers TaoDock as a cloud
provider for the WordPress AI Client included with WordPress 7.0 and newer.

After you add an API key on the Connectors screen, WordPress can discover the
models available to that key. The plugin exposes models that declare support
for OpenAI-compatible chat and sends text generation requests to TaoDock's
chat completion endpoint.

The plugin supports:

* API key storage through the WordPress Connectors screen.
* Key-scoped model discovery.
* Text generation and chat history through the WordPress AI Client.
* System instructions, output-token limits, plain-text output, and custom
  OpenAI-compatible options.

Create or manage a key at https://taodock.com/keys. Review available models at
https://taodock.com/models and usage at
https://taodock.com/usage-logs/common.

== Installation ==

1. Upload and activate TaoDock AI.
2. Open Settings > Connectors.
3. Select TaoDock and enter an API key.
4. Use a WordPress feature that supports the AI Client.

== Frequently Asked Questions ==

= Which models appear in WordPress? =

The plugin reads the models available to the configured API key and keeps
those that advertise the OpenAI-compatible chat endpoint. Use the exact model
IDs shown at https://taodock.com/models.

= Where can I review account usage? =

Open https://taodock.com/usage-logs/common. Account balance information is
available at https://taodock.com/wallet.

= Where can I report a plugin problem? =

Open an issue at
https://github.com/taodock-api/taodock-wordpress/issues.

== External services ==

This plugin communicates with TaoDock at https://taodock.com.

After an administrator adds an API key, WordPress may send the key to
https://taodock.com/v1/models to verify the connection and obtain the model
list. When a WordPress feature requests text generation, the plugin sends the
selected model ID, prompt content, conversation history, and generation
options, together with the API key, to
https://taodock.com/v1/chat/completions.

No request is sent by this plugin until a TaoDock API key has been configured
and WordPress checks the connector or an AI-enabled feature is used. TaoDock
may retain service and usage logs. Review the privacy policy at
https://taodock.com/privacy-policy and the service documentation at
https://docs.taodock.com.

== Changelog ==

= 1.0.0 =
* Initial TaoDock provider for WordPress text generation.
