<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Capability;

use Mcp\Capability\Registry\PromptReference;
use Mcp\Capability\Registry\ResourceReference;
use Mcp\Capability\Registry\ResourceTemplateReference;
use Mcp\Capability\Registry\ToolReference;
use Mcp\Exception\PromptNotFoundException;
use Mcp\Exception\ResourceNotFoundException;
use Mcp\Exception\ToolNotFoundException;

/**
 * Finds a single element by its name or URI, without needing the whole catalog.
 *
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
interface ElementResolverInterface
{
    /**
     * Gets a tool reference by name.
     *
     * @throws ToolNotFoundException
     */
    public function getTool(string $name): ToolReference;

    /**
     * Gets a resource reference by URI (includes template matching if enabled).
     *
     * @throws ResourceNotFoundException
     */
    public function getResource(string $uri, bool $includeTemplates = true): ResourceReference|ResourceTemplateReference;

    /**
     * Gets a prompt reference by name.
     *
     * @throws PromptNotFoundException
     */
    public function getPrompt(string $name): PromptReference;
}
