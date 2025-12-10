<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizare Comandă - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <main class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="/" class="hover:text-blue-600">Acasă</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('shop.index') }}" class="hover:text-blue-600">Shop</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-blue-600 font-medium">Checkout</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Finalizare Comandă</h1>
            <p class="text-gray-600">Completează formularul pentru a plasa comanda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                        Informații de Livrare
                    </h2>

                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nume Complet *
                                </label>
                                <input type="text" name="name" required 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Ion Popescu">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" name="email" required 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="ion@example.com">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Telefon *
                                </label>
                                <input type="tel" name="phone" required 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0712345678">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Oraș *
                                </label>
                                <input type="text" name="city" required 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Cluj-Napoca">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresa Completă *
                            </label>
                            <textarea name="address" required rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Strada, Număr, Bloc, Scară, Apartament"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                Metodă de Plată *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="payment_method" value="card" required class="mr-3">
                                    <i class="fas fa-credit-card text-blue-600 text-xl mr-3"></i>
                                    <span class="font-medium">Card Bancar</span>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="payment_method" value="paypal" class="mr-3">
                                    <i class="fab fa-paypal text-blue-600 text-xl mr-3"></i>
                                    <span class="font-medium">PayPal</span>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="payment_method" value="transfer" class="mr-3">
                                    <i class="fas fa-university text-blue-600 text-xl mr-3"></i>
                                    <span class="font-medium">Transfer Bancar</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-6 border-t">
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-4 rounded-lg hover:from-green-700 hover:to-green-800 transition shadow-lg">
                                <i class="fas fa-check-circle mr-2"></i>
                                Plasează Comanda
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-shopping-bag mr-2 text-blue-600"></i>
                        Sumar Comandă
                    </h3>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span class="font-semibold">{{ number_format($total ?? 0, 2) }} RON</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Transport:</span>
                            <span class="font-semibold text-green-600">Gratuit</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">{{ number_format($total ?? 0, 2) }} RON</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-blue-600 text-2xl mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Plată Securizată</h4>
                                <p class="text-sm text-gray-600">Datele tale sunt protejate prin criptare SSL</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif
</body>
</html>
