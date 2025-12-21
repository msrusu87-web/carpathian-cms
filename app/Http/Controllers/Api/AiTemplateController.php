<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroqAiService;
use App\Models\Template;
use Illuminate\Http\Request;

class AiTemplateController extends Controller
{
    protected GroqAiService $aiService;

    public function __construct(GroqAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'name' => 'required|string|max:255',
            'style' => 'nullable|string',
            'color_scheme' => 'nullable|string',
            'features' => 'nullable|array',
        ]);

        $result = $this->aiService->generateTemplate(
            $validated['description'],
            [
                'style' => $validated['style'] ?? 'modern',
                'color_scheme' => $validated['color_scheme'] ?? 'blue',
                'features' => $validated['features'] ?? [],
            ]
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        $templateData = $result['data'];
        
        $template = Template::create([
            'name' => $validated['name'],
            'slug' => \Str::slug($validated['name']),
            'description' => $validated['description'],
            'css' => $templateData['css'] ?? '',
            'js' => $templateData['js'] ?? '',
            'layouts' => $templateData['layouts'] ?? null,
            'color_scheme' => $templateData['color_scheme'] ?? null,
            'ai_generated' => true,
            'ai_generation_id' => $result['generation_id'],
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'template' => $template,
            'message' => 'Template generated successfully!',
        ]);
    }

    public function generateBlock(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:templates,id',
            'block_type' => 'required|string',
            'name' => 'required|string',
            'options' => 'nullable|array',
        ]);

        $result = $this->aiService->generateBlock(
            $validated['block_type'],
            $validated['options'] ?? []
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        $template = Template::find($validated['template_id']);
        $blockData = $result['data'];

        $block = $template->blocks()->create([
            'name' => $validated['name'],
            'type' => $validated['block_type'],
            'html' => $blockData['html'] ?? '',
            'css' => $blockData['css'] ?? '',
            'js' => $blockData['js'] ?? '',
            'ai_generated' => true,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'block' => $block,
            'message' => 'Block generated successfully!',
        ]);
    }
}
