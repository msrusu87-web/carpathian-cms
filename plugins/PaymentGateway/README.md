# Payment Gateway Plugin

Plugin standalone pentru gateway-uri de plată cu suport Stripe și PayPal.

## Caracteristici

- ✅ Suport Stripe & PayPal
- ✅ Plăți rapide prin link
- ✅ Checkout produse
- ✅ Comisioane configurabile
- ✅ Mod test/live
- ✅ Credențiale criptate
- ✅ Meniu admin condiționat (apare doar când plugin-ul e activ)

## Instalare

1. Activează plugin-ul din Admin -> Plugins
2. Configurează gateway-urile în Admin -> Gateway-uri de plată

## Configurare Stripe

1. Mergi la Admin -> Gateway-uri de plată -> Creare nouă
2. Selectează Provider: Stripe
3. Adaugă credențiale:
   - `api_key`: sk_test_... (pentru test) sau sk_live_... (pentru live)
   - `api_secret`: (opțional)
4. Configurează comisioanele
5. Activează tipurile de plată dorite (Quick Links / Checkout)

## Configurare PayPal

1. Mergi la Admin -> Gateway-uri de plată -> Creare nouă
2. Selectează Provider: PayPal
3. Adaugă credențiale:
   - `client_id`: Your PayPal Client ID
   - `client_secret`: Your PayPal Client Secret
4. Configurează comisioanele
5. Activează tipurile de plată dorite

## Utilizare

### Plată Rapidă prin Link

```php
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::active()
    ->quickLinks()
    ->first();

$service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);
$paymentLink = $service->createQuickPaymentLink(
    amount: 100.00,
    description: 'Plată pentru serviciu',
    metadata: ['custom_field' => 'value']
);
```

### Checkout Produse

```php
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::active()
    ->productCheckout()
    ->first();

$service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);
$result = $service->processCheckoutPayment([
    'order_id' => 123,
    'customer_email' => 'client@example.com',
    'items' => [
        [
            'name' => 'Produs 1',
            'price' => 50.00,
            'quantity' => 2
        ]
    ]
]);
```

## Note

- Credențialele sunt criptate automat în baza de date
- Plugin-ul poate fi activat/dezactivat din Admin -> Plugins
- Când plugin-ul este dezactivat, meniul "Gateway-uri de plată" dispare automat
- Suportă mod test și live pentru fiecare gateway
