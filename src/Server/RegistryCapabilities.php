<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Server;

use Mcp\Capability\DeferredRegistryInterface;
use Mcp\Capability\RegistryInterface;
use Mcp\Schema\ServerCapabilities;

/**
 * Answers which capabilities a registry supports, at the time a request asks for them.
 *
 * @internal
 *
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
final class RegistryCapabilities
{
    /**
     * @param array<string, array<string, mixed>> $extensions
     */
    public function __construct(
        private readonly RegistryInterface $registry,
        private readonly bool $hasNotificationBus,
        private readonly array $extensions = [],
    ) {
    }

    public function detect(): ServerCapabilities
    {
        $mayGrow = false;
        if ($this->registry instanceof DeferredRegistryInterface) {
            $this->registry->load();
            // Hiding the kinds of elements that still arrive would hide them from the client for the whole session.
            $mayGrow = !$this->registry->isLoaded();
        }

        $hasTools = $mayGrow || $this->registry->hasTools();
        $hasResources = $mayGrow || $this->registry->hasResources() || $this->registry->hasResourceTemplates();
        $hasPrompts = $mayGrow || $this->registry->hasPrompts();

        // Only the bus delivers list changes, and only if the registry publishes to it.
        $listChanged = $this->hasNotificationBus && $this->registry->publishesListChanges();

        return new ServerCapabilities(
            tools: $hasTools,
            toolsListChanged: $listChanged,
            resources: $hasResources,
            resourcesSubscribe: $hasResources,
            resourcesListChanged: $listChanged,
            prompts: $hasPrompts,
            promptsListChanged: $listChanged,
            logging: true,
            completions: true,
            extensions: $this->extensions ?: null,
        );
    }
}
