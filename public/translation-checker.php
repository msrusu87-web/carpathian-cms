<?php
/**
 * Web-Based Translation Checker for Carpathian CMS
 * Access via: https://carphatian.ro/translation-checker.php
 *
 * This tool checks pages for translation errors in real-time.
 */

// Security: Only allow with secret key
$secret = $_GET['secret'] ?? '';
$expectedSecret = 'carpathian2024'; // Change this!

if ($secret !== $expectedSecret) {
    http_response_code(403);
    die('Access Denied. Add ?secret=carpathian2024 to URL');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translation Checker - Carpathian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .error-highlight {
            background-color: #fee;
            border: 2px solid #f00;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
        }
        .warning-box {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Translation Checker - Carpathian CMS
            </h1>
            <p class="text-gray-600 mb-4">
                This tool scans pages for untranslated messages (messages.xxx) and reports errors.
            </p>
        </div>

        <!-- URL Checker Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Check Page URL</h2>
            <form id="checkForm" class="flex gap-4">
                <input
                    type="text"
                    id="urlInput"
                    placeholder="Enter page URL or path (e.g., /shop/products/...)"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    value="/shop/products/website-standard"
                >
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
                >
                    Check Page
                </button>
            </form>
        </div>

        <!-- Predefined Pages -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Quick Check - Common Pages</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="checkPage('/')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Home
                </button>
                <button onclick="checkPage('/shop')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Shop
                </button>
                <button onclick="checkPage('/portfolios')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Portfolio
                </button>
                <button onclick="checkPage('/checkout')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Checkout
                </button>
                <button onclick="checkPage('/blog')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Blog
                </button>
                <button onclick="checkPage('/contact')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Contact
                </button>
                <button onclick="checkPage('/shop/products/website-standard')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
                    Product Page
                </button>
                <button onclick="checkPage('/shop/categories')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Categories
                </button>
            </div>
        </div>

        <!-- Results Container -->
        <div id="results" class="hidden bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Scan Results</h2>
            <div id="resultsContent"></div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="hidden bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <p class="mt-4 text-gray-600">Scanning page...</p>
        </div>
    </div>

    <script>
        const form = document.getElementById('checkForm');
        const urlInput = document.getElementById('urlInput');
        const results = document.getElementById('results');
        const resultsContent = document.getElementById('resultsContent');
        const loading = document.getElementById('loading');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            checkPage(urlInput.value);
        });

        function checkPage(url) {
            // Normalize URL
            if (!url.startsWith('http')) {
                url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
            }

            urlInput.value = url;
            results.classList.add('hidden');
            loading.classList.remove('hidden');

            // Fetch the page
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    analyzePage(html, url);
                })
                .catch(error => {
                    showError('Error fetching page: ' + error.message);
                })
                .finally(() => {
                    loading.classList.add('hidden');
                });
        }

        function analyzePage(html, url) {
            // Find all instances of messages.xxx in HTML
            const pattern = /messages\.([a-z0-9_\.]+)/gi;
            const matches = [];
            let match;

            while ((match = pattern.exec(html)) !== null) {
                matches.push(match[0]);
            }

            // Deduplicate
            const unique = [...new Set(matches)].sort();

            displayResults(unique, url);
        }

        function displayResults(keys, url) {
            results.classList.remove('hidden');

            if (keys.length === 0) {
                resultsContent.innerHTML = `
                    <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded">
                        <p class="font-bold text-green-800">All Clear!</p>
                        <p class="text-green-700">No messages.* keys found on this page.</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded mb-6 warning-box">
                    <p class="font-bold text-red-800">Found ${keys.length} message key(s) in HTML</p>
                    <p class="text-red-700">This typically indicates missing translations or hard-coded keys being rendered.</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Checked URL: <code class="bg-gray-100 px-2 py-1 rounded">${escapeHtml(url)}</code></p>
                </div>

                <ul class="list-disc pl-6 space-y-1">
                    ${keys.map(k => `<li><code class="bg-gray-100 px-2 py-1 rounded">${escapeHtml(k)}</code></li>`).join('')}
                </ul>
            `;

            resultsContent.innerHTML = html;
        }

        function showError(message) {
            results.classList.remove('hidden');
            resultsContent.innerHTML = `
                <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded">
                    <p class="font-bold text-red-800">Error</p>
                    <p class="text-red-700">${escapeHtml(message)}</p>
                </div>
            `;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
