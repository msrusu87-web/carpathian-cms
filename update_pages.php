<?php
require __DIR__ . "/vendor/autoload.php";
\$app = require_once __DIR__ . "/bootstrap/app.php";
\$app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();

// Update Services Page
\$servicesContent = <<<HTML
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-20 mb-16 rounded-2xl shadow-2xl">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-6xl font-extrabold mb-6 tracking-tight">Serviciile Noastre</h1>
        <p class="text-2xl font-light max-w-3xl mx-auto opacity-90">Soluții tehnologice de ultimă generație pentru afacerea ta</p>
    </div>
</div>

<div class="container mx-auto px-6 mb-16">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Ce Oferim</h2>
        <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mx-auto"></div>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mb-16">
        <!-- Web Development -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-8 text-white">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-code text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Dezvoltare Web</h3>
            </div>
            <div class="p-8">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mr-3 mt-1"></i>
                        <span>Pagini web moderne și responsive</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mr-3 mt-1"></i>
                        <span>Aplicații web complexe (PHP, Laravel)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mr-3 mt-1"></i>
                        <span>CMS personalizat (WordPress, custom)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mr-3 mt-1"></i>
                        <span>API-uri RESTful și integrări</span>
                    </li>
                </ul>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">De la</span>
                        <span class="text-3xl font-bold text-blue-600">700 RON</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">*pentru pagini simple</p>
                </div>
            </div>
        </div>

        <!-- E-Commerce -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-8 text-white">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">E-Commerce</h3>
            </div>
            <div class="p-8">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Magazine online complete</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Integrare plăți online</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Administrare produse și comenzi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <span>Rapoarte și analiză vânzări</span>
                    </li>
                </ul>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-sm leading-relaxed">Soluții scalabile pentru orice dimensiune de business</p>
                </div>
            </div>
        </div>

        <!-- AI & Automation -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 p-8 text-white">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-robot text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Platforme AI</h3>
            </div>
            <div class="p-8">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-purple-500 mr-3 mt-1"></i>
                        <span>Chatbots inteligenți</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-purple-500 mr-3 mt-1"></i>
                        <span>Automatizări business</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-purple-500 mr-3 mt-1"></i>
                        <span>Integrare ChatGPT & Claude</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-purple-500 mr-3 mt-1"></i>
                        <span>Analiză date cu AI</span>
                    </li>
                </ul>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-sm leading-relaxed">Transformă-ți afacerea cu inteligență artificială</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Programming Languages & Technologies -->
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 mb-16">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Tehnologii & Limbaje</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Backend -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-server text-blue-600 mr-2"></i>
                    Backend
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-php text-indigo-600 mr-2"></i>
                        <span>PHP & Laravel</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-python text-blue-600 mr-2"></i>
                        <span>Python & Django</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-node-js text-green-600 mr-2"></i>
                        <span>Node.js & Express</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-java text-red-600 mr-2"></i>
                        <span>Java & Spring Boot</span>
                    </div>
                </div>
            </div>

            <!-- Frontend -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-desktop text-purple-600 mr-2"></i>
                    Frontend
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-js text-yellow-500 mr-2"></i>
                        <span>JavaScript & TypeScript</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-react text-blue-500 mr-2"></i>
                        <span>React & Next.js</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-vuejs text-green-500 mr-2"></i>
                        <span>Vue.js & Nuxt</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-wind text-cyan-500 mr-2"></i>
                        <span>Tailwind CSS</span>
                    </div>
                </div>
            </div>

            <!-- Mobile & Desktop -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-mobile-alt text-pink-600 mr-2"></i>
                    Mobile & More
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-android text-green-600 mr-2"></i>
                        <span>Flutter & Kotlin</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-apple text-gray-700 mr-2"></i>
                        <span>Swift (iOS)</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-gem text-red-600 mr-2"></i>
                        <span>Ruby & Rails</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-rust text-orange-600 mr-2"></i>
                        <span>Rust</span>
                    </div>
                </div>
            </div>

            <!-- Database & DevOps -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-database text-orange-600 mr-2"></i>
                    Database & Cloud
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-database text-blue-700 mr-2"></i>
                        <span>MySQL & PostgreSQL</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-leaf text-green-600 mr-2"></i>
                        <span>MongoDB</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-docker text-blue-500 mr-2"></i>
                        <span>Docker & Kubernetes</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fab fa-aws text-orange-500 mr-2"></i>
                        <span>AWS & Azure</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Services -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-mobile-alt text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aplicații Mobile</h3>
            <p class="text-gray-600 text-sm">iOS și Android native sau cross-platform</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-cogs text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Automatizări</h3>
            <p class="text-gray-600 text-sm">Optimizează procesele de business</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-500 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Dashboards</h3>
            <p class="text-gray-600 text-sm">Vizualizare date în timp real</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-plug text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Integrări API</h3>
            <p class="text-gray-600 text-sm">Conectăm sistemele tale existente</p>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-12 text-center text-white shadow-2xl">
        <h2 class="text-4xl font-bold mb-4">Gata să începem?</h2>
        <p class="text-xl mb-8 opacity-90">Consultație gratuită pentru proiectul tău</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/contact" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors shadow-lg">
                <i class="fas fa-envelope mr-2"></i>
                Solicită Ofertă
            </a>
            <a href="tel:+40123456789" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white/10 transition-colors">
                <i class="fas fa-phone mr-2"></i>
                Sună Acum
            </a>
        </div>
    </div>
</div>
HTML;

\$servicesPage = App\\Models\\Page::where("slug", "services")->first();
\$servicesPage->content = \$servicesContent;
\$servicesPage->meta_description = "Servicii complete de dezvoltare software: aplicații web, e-commerce, platforme AI, mobile apps. Tehnologii: PHP, Python, Java, TypeScript, Ruby și multe altele.";
\$servicesPage->save();

echo "✅ Services page updated successfully!\n\n";

// Update About Us Page
\$aboutContent = <<<HTML
<div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-24 mb-16 rounded-2xl shadow-2xl overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="container mx-auto px-6 text-center relative z-10">
        <h1 class="text-6xl font-extrabold mb-6 tracking-tight">Despre Noi</h1>
        <p class="text-2xl font-light max-w-3xl mx-auto opacity-90">Pasiunea noastră este să transformăm viziuni în realitate digitală</p>
    </div>
</div>

<div class="container mx-auto px-6">
    <!-- Mission Section -->
    <div class="grid md:grid-cols-2 gap-16 mb-20 items-center">
        <div>
            <div class="inline-block bg-gradient-to-r from-purple-600 to-pink-500 text-white px-4 py-2 rounded-full text-sm font-semibold mb-4">
                Misiunea Noastră
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Cine Suntem</h2>
            <div class="space-y-4 text-gray-700 text-lg leading-relaxed">
                <p>
                    Suntem o echipă de dezvoltatori pasionați și creativi, dedicați să oferim soluții software de înaltă calitate 
                    care ajută afacerile să prospere în era digitală.
                </p>
                <p>
                    Cu o experiență vastă în dezvoltarea de aplicații web, mobile și sisteme enterprise, combinăm expertiza tehnică 
                    cu înțelegerea profundă a nevoilor de business ale clienților noștri.
                </p>
                <p class="font-semibold text-purple-600">
                    De la startup-uri la corporații, livrăm soluții scalabile și inovatoare care depășesc așteptările.
                </p>
            </div>
        </div>
        <div class="relative">
            <div class="bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl p-8">
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                        <div class="text-4xl font-bold text-purple-600 mb-2">500+</div>
                        <div class="text-gray-600 text-sm">Proiecte Finalizate</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                        <div class="text-4xl font-bold text-pink-600 mb-2">200+</div>
                        <div class="text-gray-600 text-sm">Clienți Mulțumiți</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                        <div class="text-4xl font-bold text-indigo-600 mb-2">10+</div>
                        <div class="text-gray-600 text-sm">Ani Experiență</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                        <div class="text-gray-600 text-sm">Suport Tehnic</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="mb-20">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Valorile Noastre</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mx-auto"></div>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-lightbulb text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inovație</h3>
                <p class="text-gray-600 leading-relaxed">
                    Adoptăm cele mai noi tehnologii și metodologii pentru a oferi soluții de vârf care te poziționează înaintea competiției.
                </p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-heart text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Calitate</h3>
                <p class="text-gray-600 leading-relaxed">
                    Fiecare linie de cod este scrisă cu atenție la detalii. Testăm riguros pentru a asigura performanță și fiabilitate.
                </p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-users text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Parteneriat</h3>
                <p class="text-gray-600 leading-relaxed">
                    Nu suntem doar furnizori, suntem partenerii tăi de încredere în călătoria de transformare digitală.
                </p>
            </div>
        </div>
    </div>

    <!-- Process Section -->
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 mb-20">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Cum Lucrăm</h2>
            <p class="text-xl text-gray-600">Un proces transparent și eficient</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-md relative">
                <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    1
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-3 mt-4">Consultare</h4>
                <p class="text-gray-600 text-sm">Înțelegem nevoile tale și analizăm cerințele proiectului în detaliu.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md relative">
                <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    2
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-3 mt-4">Planificare</h4>
                <p class="text-gray-600 text-sm">Creăm o strategie detaliată cu timeline-uri clare și milestone-uri.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md relative">
                <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    3
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-3 mt-4">Dezvoltare</h4>
                <p class="text-gray-600 text-sm">Construim soluția cu tehnologii moderne și cele mai bune practici.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md relative">
                <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    4
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-3 mt-4">Lansare & Suport</h4>
                <p class="text-gray-600 text-sm">Livrăm proiectul și oferim suport continuu pentru optimizare.</p>
            </div>
        </div>
    </div>

    <!-- Technologies Expertise -->
    <div class="mb-20">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Expertiza Noastră</h2>
            <p class="text-xl text-gray-600">Stack tehnologic divers și avansat</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-server text-indigo-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Backend</h4>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold">PHP</span>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Python</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Node.js</span>
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Java</span>
                    <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Ruby</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-desktop text-purple-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Frontend</h4>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">JavaScript</span>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">TypeScript</span>
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">React</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Vue.js</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-mobile-alt text-pink-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Mobile</h4>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Flutter</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">React Native</span>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">Kotlin</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">Swift</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cloud text-orange-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Cloud & DevOps</h4>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold">AWS</span>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Azure</span>
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">Docker</span>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">K8s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-12 text-center text-white shadow-2xl">
        <h2 class="text-4xl font-bold mb-4">Hai să construim ceva minunat împreună!</h2>
        <p class="text-xl mb-8 opacity-90">Contactează-ne astăzi pentru o consultație gratuită</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/contact" class="bg-white text-purple-600 px-10 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-xl">
                <i class="fas fa-paper-plane mr-2"></i>
                Trimite Mesaj
            </a>
            <a href="/services" class="border-2 border-white text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition-all transform hover:scale-105">
                <i class="fas fa-briefcase mr-2"></i>
                Vezi Servicii
            </a>
        </div>
    </div>
</div>
HTML;

\$aboutPage = App\\Models\\Page::where("slug", "about-us")->first();
\$aboutPage->content = \$aboutContent;
\$aboutPage->meta_description = "Echipă de dezvoltatori pasionați cu 10+ ani experiență în web, mobile și AI. 500+ proiecte finalizate, 200+ clienți mulțumiți. Soluții complete de la startup la enterprise.";
\$aboutPage->save();

echo "✅ About Us page updated successfully!\n\n";
echo "Both pages have been updated with beautiful, modern content!\n";
echo "Visit:\n";
echo "  - https://cms.carphatian.ro/services\n";
echo "  - https://cms.carphatian.ro/page/about-us\n";
