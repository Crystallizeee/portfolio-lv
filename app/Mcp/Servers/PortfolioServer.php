<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;
use App\Mcp\Tools\GetProjects;
use App\Mcp\Tools\GetSkills;

#[Name('Portfolio Server')]
#[Version('0.0.1')]
#[Instructions('Server MCP untuk mengambil data public dari portfolio milik pengguna. Dapat digunakan untuk meresume skill, project, dll.')]
class PortfolioServer extends Server
{
    protected array $tools = [
        GetProjects::class,
        GetSkills::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
