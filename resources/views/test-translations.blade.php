<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translation Test - {{ strtoupper(app()->getLocale()) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h1 class="text-4xl font-bold mb-4">Translation Test Page</h1>
            <p class="text-xl mb-4">Current Locale: <span class="font-bold text-blue-600">{{ strtoupper(app()->getLocale()) }}</span></p>
            
            <div class="flex gap-4 mb-8">
                <a href="/en" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Switch to English</a>
                <a href="/ro" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">Switch to Romanian</a>
            </div>
        </div>

        <!-- Navigation Tests -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Navigation Translations</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">home</p>
                    <p class="font-bold text-lg">{{ __('messages.home') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">about</p>
                    <p class="font-bold text-lg">{{ __('messages.about') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">services</p>
                    <p class="font-bold text-lg">{{ __('messages.services') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">blog</p>
                    <p class="font-bold text-lg">{{ __('messages.blog') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">shop</p>
                    <p class="font-bold text-lg">{{ __('messages.shop') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">contact</p>
                    <p class="font-bold text-lg">{{ __('messages.contact') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">admin</p>
                    <p class="font-bold text-lg">{{ __('messages.admin') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">cart</p>
                    <p class="font-bold text-lg">{{ __('messages.cart') }}</p>
                </div>
            </div>
        </div>

        <!-- Hero Translations -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Hero Section Translations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">hero_title</p>
                    <p class="font-bold text-lg">{{ __('messages.hero_title') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">hero_subtitle</p>
                    <p class="font-bold text-lg">{{ __('messages.hero_subtitle') }}</p>
                </div>
            </div>
        </div>

        <!-- Products Translations -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Products Section Translations</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">featured_products</p>
                    <p class="font-bold text-lg">{{ __('messages.featured_products') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">view_details</p>
                    <p class="font-bold text-lg">{{ __('messages.view_details') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">view_all_products</p>
                    <p class="font-bold text-lg">{{ __('messages.view_all_products') }}</p>
                </div>
            </div>
        </div>

        <!-- Blog Translations -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Blog Section Translations</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">latest_blog_posts</p>
                    <p class="font-bold text-lg">{{ __('messages.latest_blog_posts') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">read_more</p>
                    <p class="font-bold text-lg">{{ __('messages.read_more') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">view_all_posts</p>
                    <p class="font-bold text-lg">{{ __('messages.view_all_posts') }}</p>
                </div>
            </div>
        </div>

        <!-- Footer Translations -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Footer Translations</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">footer_tagline</p>
                    <p class="font-bold text-lg">{{ __('messages.footer_tagline') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">quick_links</p>
                    <p class="font-bold text-lg">{{ __('messages.quick_links') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">all_rights_reserved</p>
                    <p class="font-bold text-lg">{{ __('messages.all_rights_reserved') }}</p>
                </div>
            </div>
        </div>

        <!-- Documentation Translations -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Documentation Translations</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">developer_documentation</p>
                    <p class="font-bold text-lg">{{ __('messages.developer_documentation') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">plugin_development</p>
                    <p class="font-bold text-lg">{{ __('messages.plugin_development') }}</p>
                </div>
                <div class="border rounded p-4">
                    <p class="text-sm text-gray-600">template_development</p>
                    <p class="font-bold text-lg">{{ __('messages.template_development') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 text-center">
            <p class="text-xl font-bold text-green-800">✅ If you see Romanian text above when locale is RO, translations are working!</p>
            <p class="text-gray-600 mt-2">Visit the homepage to see translations in action</p>
            <a href="/" class="inline-block mt-4 bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">Go to Homepage</a>
        </div>
    </div>
</body>
</html>
