<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroqAiService;
use App\Models\Plugin;
use Illuminate\Http\Request;

class AiPluginController extends Controller
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
            'functionality' => 'nullable|string',
            'hooks' => 'nullable|array',
        ]);

        $result = $this->aiService->generatePlugin(
            $validated['description'],
            [
                'functionality' => $validated['functionality'] ?? '',
                'hooks' => $validated['hooks'] ?? [],
            ]
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        $pluginData = $result['data'];
        
        $plugin = Plugin::create([
            'name' => $pluginData['name'] ?? $validated['name'],
            'slug' => $pluginData['slug'] ?? \Str::slug($validated['name']),
            'description' => $pluginData['description'] ?? $validated['description'],
            'code' => $pluginData['code'] ?? '',
            'hooks' => $pluginData['hooks'] ?? null,
            'config' => $pluginData['config'] ?? null,
            'ai_generated' => true,
            'ai_generation_id' => $result['generation_id'],
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'plugin' => $plugin,
            'message' => 'Plugin generated successfully!',
        ]);
    }

    public function improve(Request $request, Plugin $plugin)
    {
        $validated = $request->validate([
            'instructions' => 'required|string',
        ]);

        $result = $this->aiService->improveCode(
            $plugin->code,
            $validated['instructions']
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        $plugin->update([
            'code' => $result['improved_code'],
        ]);

        return response()->json([
            'success' => true,
            'plugin' => $plugin,
            'message' => 'Plugin improved successfully!',
        ]);
    }
}
