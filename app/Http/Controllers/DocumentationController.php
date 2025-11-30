<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class DocumentationController extends Controller
{
    /**
     * Display the roles and permissions documentation.
     */
    public function rolesPermissions()
    {
        $markdownPath = base_path('ROLES_PERMISSIONS.md');

        if (! File::exists($markdownPath)) {
            abort(404, 'Documentation file not found');
        }

        $markdown = File::get($markdownPath);

        // Configure the Environment with all the CommonMark parsers/renderers
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        // Add the core CommonMark extension
        $environment->addExtension(new CommonMarkCoreExtension());

        // Add GitHub Flavored Markdown extensions (includes tables)
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        // Create the converter
        $converter = new MarkdownConverter($environment);

        $html = $converter->convert($markdown);

        return view('documentation.roles-permissions', [
            'content' => $html,
            'title' => 'Roles & Permissions Documentation',
        ]);
    }
}
