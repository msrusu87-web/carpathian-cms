<div class="p-4">
    <div class="mb-4">
        <strong class="text-sm text-gray-500">Subject:</strong>
        <p class="text-gray-900">{{ $template->subject }}</p>
    </div>
    <div class="border rounded-lg overflow-hidden">
        <iframe srcdoc="{{ htmlspecialchars($template->body_html) }}" class="w-full h-96"></iframe>
    </div>
</div>
