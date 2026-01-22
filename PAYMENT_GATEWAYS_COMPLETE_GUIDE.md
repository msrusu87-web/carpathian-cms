# 📘 Ghid Complet Payment Gateway - Toate Metodele de Plată

## 🎯 Gateway-uri Disponibile

✅ **5 Gateway-uri de Plată Configure:**

1. **Stripe** - Card international (Quick Links + Checkout)
2. **PayPal** - Plăți internaționale
3. **EuPlatesc** - Gateway românesc (Quick Links + Checkout)
4. **Netopia (MobilPay)** - Gateway românesc 
5. **Transfer Bancar** - Plăți manuale prin IBAN/SWIFT

---

## 1️⃣ STRIPE 💳

### Caracteristici:
- ✅ Plăți rapide prin link
- ✅ Checkout produse
- ✅ Suport carduri internaționale
- ✅ Mod test/live

### Configurare:

1. **Obține credențiale:**
   - Mergi la https://dashboard.stripe.com/apikeys
   - Copiază **Secret key** și **Publishable key**

2. **În Admin Panel:**
   ```
   Admin -> Gateway-uri de plată -> Stripe -> Edit
   
   Credențiale:
   - api_key: sk_test_... (test) sau sk_live_... (live)
   - publishable_key: pk_test_... (test) sau pk_live_... (live)
   
   Comisioane: 2.9% + 0.30 RON (Stripe standard)
   Test Mode: ON (pentru teste)
   Activ: ON
   ```

3. **Webhook URL:**
   ```
   https://carphatian.ro/webhooks/stripe
   ```

### Utilizare Cod:

```php
// Plată rapidă prin link
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::where('slug', 'stripe')->first();
$service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);

$link = $service->createQuickPaymentLink(
    amount: 100.00,
    description: 'Serviciu consultanță',
    metadata: ['user_id' => 123]
);

// Redirecționează la $link
```

---

## 2️⃣ EUPLATESC 🇷🇴

### Caracteristici:
- ✅ Gateway românesc
- ✅ Plăți rapide prin link
- ✅ Checkout produse
- ✅ Suport carduri RON
- ✅ Comisioane mai mici decât Stripe

### Configurare:

1. **Obține credențiale:**
   - Creează cont merchant la https://euplatesc.ro
   - Obține **Merchant ID** și **Secret Key** din contul tău

2. **În Admin Panel:**
   ```
   Admin -> Gateway-uri de plată -> EuPlatesc -> Edit
   
   Credențiale:
   - merchant_id: YOUR_MERCHANT_ID
   - secret_key: YOUR_SECRET_KEY
   
   Comisioane: 1.99% (fără comision fix)
   Test Mode: ON (pentru teste)
   Activ: ON
   ```

3. **Webhook URL:**
   ```
   https://carphatian.ro/webhooks/euplatesc
   ```

### Parametri Disponibili:

- `merchant_id` - ID-ul merchant-ului
- `secret_key` - Cheia secretă pentru semnături HMAC
- `return_url` - URL de return după plată

### Documentație Oficială:
https://euplatesc.ro/documentatie-integrare

### Utilizare Cod:

```php
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::where('slug', 'euplatesc')->first();
$service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);

$result = $service->processCheckoutPayment([
    'amount' => 150.00,
    'currency' => 'RON',
    'order_id' => 'ORD-12345',
    'description' => 'Comandă produse',
    'customer_email' => 'client@example.com',
    'customer_name' => 'Ion Popescu',
    'customer_phone' => '+40721234567',
]);

// $result conține form_data pentru auto-submit
```

---

## 3️⃣ NETOPIA (MobilPay) 📱

### Caracteristici:
- ✅ Gateway românesc (fost MobilPay)
- ✅ Checkout produse
- ✅ Criptare RSA
- ✅ Suport multiple tipuri de plată

### Configurare:

1. **Obține credențiale:**
   - Creează cont merchant la https://netopia-payments.com
   - Descarcă certificatele RSA (public.cer și private.key)
   - Obține **Signature** din cont

2. **Încarcă certificatele:**
   ```bash
   mkdir -p storage/netopia
   # Încarcă fișierele public.cer și private.key în storage/netopia/
   chmod 600 storage/netopia/private.key
   ```

3. **În Admin Panel:**
   ```
   Admin -> Gateway-uri de plată -> Netopia -> Edit
   
   Credențiale:
   - signature: YOUR_SIGNATURE
   - public_key_path: storage/netopia/public.cer
   - private_key_path: storage/netopia/private.key
   - private_key_password: (dacă e setat)
   
   Comisioane: 1.5% (negociabil)
   Test Mode: ON (folosește sandbox)
   Activ: ON
   ```

4. **Webhook URL:**
   ```
   https://carphatian.ro/webhooks/netopia
   ```

### URL-uri Sandbox vs Production:

- **Sandbox:** https://sandbox.netopia-payments.com/payment/card/authorize
- **Production:** https://secure.netopia-payments.com/payment/card/authorize

### Documentație Oficială:
https://netopia-payments.com/en/documentatie/

### Utilizare Cod:

```php
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::where('slug', 'netopia')->first();
$service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);

$result = $service->processCheckoutPayment([
    'amount' => 200.00,
    'currency' => 'RON',
    'order_id' => 'ORD-12345',
    'description' => 'Comandă produse',
    'customer_email' => 'client@example.com',
    'customer_first_name' => 'Ion',
    'customer_last_name' => 'Popescu',
    'customer_phone' => '+40721234567',
    'items' => [
        ['name' => 'Produs 1', 'quantity' => 2, 'price' => 100.00],
    ],
    'confirm_url' => 'https://carphatian.ro/payment/netopia/confirm',
    'return_url' => 'https://carphatian.ro/payment/netopia/return',
]);

// Auto-submit form cu date criptate
```

---

## 4️⃣ TRANSFER BANCAR 🏦

### Caracteristici:
- ✅ Fără comisioane online
- ✅ Plăți prin IBAN/SWIFT
- ✅ Verificare manuală
- ✅ Ideal pentru sume mari

### Configurare:

1. **În Admin Panel:**
   ```
   Admin -> Gateway-uri de plată -> Transfer Bancar -> Edit
   
   Credențiale (Datele tale bancare):
   - bank_name: Banca Transilvania
   - account_holder: Carphatian CMS SRL
   - iban: RO49 AAAA 1B31 0075 9384 0000
   - swift_bic: BTRLRO22
   - bank_address: Cluj-Napoca, Romania
   - account_currency: RON
   
   Opțional (pentru transferuri internaționale):
   - routing_number: (pentru US)
   - sort_code: (pentru UK)
   - bank_code: (alte țări)
   
   Comisioane: 0% (online, pot fi comisioane bancare)
   Test Mode: OFF
   Activ: ON
   ```

### Utilizare Cod:

```php
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::where('slug', 'transfer-bancar')->first();
$service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);

$instructions = $service->processCheckoutPayment([
    'amount' => 500.00,
    'currency' => 'RON',
    'order_id' => 'ORD-12345',
]);

// $instructions conține:
// - bank_details: Datele bancare formatate
// - reference: Referință unică (REF-ORD-12345)
// - instructions: Text pentru client
// - amount, currency, order_id
```

### Afișare în Frontend:

```html
<div class="bank-transfer-instructions">
    <h3>Detalii Transfer Bancar</h3>
    
    <div class="bank-details">
        <p><strong>Bancă:</strong> {{ $bankDetails['bank_name'] }}</p>
        <p><strong>Beneficiar:</strong> {{ $bankDetails['account_holder'] }}</p>
        <p><strong>IBAN:</strong> <code>{{ $bankDetails['iban'] }}</code></p>
        <p><strong>SWIFT/BIC:</strong> <code>{{ $bankDetails['swift_bic'] }}</code></p>
        <p><strong>Adresă Bancă:</strong> {{ $bankDetails['bank_address'] }}</p>
    </div>
    
    <div class="payment-info">
        <p><strong>Sumă de plătit:</strong> {{ number_format($amount, 2) }} {{ $currency }}</p>
        <p><strong>Referință obligatorie:</strong> <code>{{ $reference }}</code></p>
    </div>
    
    <div class="alert alert-warning">
        ⚠️ IMPORTANT: Includeți referința <strong>{{ $reference }}</strong> în descrierea transferului!
    </div>
</div>
```

### Validare IBAN/SWIFT:

```php
use Plugins\PaymentGateway\Services\BankTransferService;

$service = new BankTransferService([]);

// Validează IBAN
$isValidIBAN = $service->validateIBAN('RO49AAAA1B31007593840000'); // true/false

// Validează SWIFT/BIC
$isValidSWIFT = $service->validateSWIFT('BTRLRO22'); // true/false
```

### QR Code pentru Transfer (opțional):

```php
$qrData = $service->generateQRCodeData([
    'order_id' => 'ORD-12345',
    'amount' => 500.00,
]);

// Generează QR code cu $qrData pentru scanare în aplicații bancare mobile
```

---

## 5️⃣ PAYPAL 💙

### Configurare:

1. **Obține credențiale:**
   - Mergi la https://developer.paypal.com
   - Creează aplicație și obține Client ID & Secret

2. **În Admin Panel:**
   ```
   Admin -> Gateway-uri de plată -> PayPal -> Edit
   
   Credențiale:
   - client_id: YOUR_CLIENT_ID
   - client_secret: YOUR_CLIENT_SECRET
   
   Comisioane: 3.4% + 0.35 EUR
   Test Mode: ON (sandbox)
   Activ: ON
   ```

---

## 🔧 Utilizare Generală în Frontend

### 1. Afișare Metode de Plată Disponibile:

```php
// Controller
$gateways = \Plugins\PaymentGateway\Models\PaymentGateway::active()
    ->productCheckout()
    ->get();

return view('checkout', compact('gateways'));
```

```html
<!-- View -->
<div class="payment-methods">
    @foreach($gateways as $gateway)
        <div class="payment-method">
            <input type="radio" name="payment_gateway" value="{{ $gateway->id }}" id="gateway_{{ $gateway->id }}">
            <label for="gateway_{{ $gateway->id }}">
                <span class="name">{{ $gateway->name }}</span>
                
                @if($gateway->provider === 'bank_transfer')
                    <span class="badge">Fără comisioane online</span>
                @endif
                
                @if($gateway->fee_percentage > 0 || $gateway->fee_fixed > 0)
                    <span class="fee">
                        +{{ $gateway->fee_percentage }}% 
                        @if($gateway->fee_fixed > 0)
                            + {{ number_format($gateway->fee_fixed, 2) }} RON
                        @endif
                    </span>
                @endif
                
                @if($gateway->test_mode)
                    <span class="badge badge-warning">Test Mode</span>
                @endif
            </label>
        </div>
    @endforeach
</div>
```

### 2. Procesare Plată:

```php
// Controller
public function processPayment(Request $request)
{
    $gatewayId = $request->input('payment_gateway');
    $gateway = \Plugins\PaymentGateway\Models\PaymentGateway::findOrFail($gatewayId);
    
    $service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);
    
    $orderData = [
        'amount' => $cart->total,
        'currency' => 'RON',
        'order_id' => $order->id,
        'description' => 'Comandă #' . $order->id,
        'customer_email' => $order->email,
        'customer_name' => $order->name,
        'customer_phone' => $order->phone,
        'items' => $cart->items->map(fn($item) => [
            'name' => $item->product->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
        ])->toArray(),
    ];
    
    $result = $service->processCheckoutPayment($orderData);
    
    if ($result['success']) {
        if ($gateway->provider === 'bank_transfer') {
            // Afișează instrucțiuni transfer bancar
            return view('payment.bank-transfer', $result);
        } else {
            // Redirecționează la gateway de plată
            return redirect($result['payment_url']);
        }
    } else {
        return back()->with('error', $result['error']);
    }
}
```

### 3. Calcul Total cu Comisioane:

```php
$gateway = \Plugins\PaymentGateway\Models\PaymentGateway::find($gatewayId);
$amount = 100.00;

$fee = $gateway->calculateFee($amount);
$totalWithFees = $gateway->getTotalWithFees($amount);

// Afișează:
echo "Sumă comandă: {$amount} RON\n";
echo "Comision: {$fee} RON\n";
echo "Total de plătit: {$totalWithFees} RON\n";
```

---

## 🔐 Securitate

### Credențiale Criptate:
- Toate credențialele sunt criptate automat în baza de date
- Folosește `encrypted:array` cast în Eloquent
- Nu stoca niciodată credențialele în git sau cod

### Verificare Webhook-uri:

```php
// Pentru EuPlatesc
$service = new EuPlatescService($credentials, $testMode);
$isValid = $service->verifyCallback($_POST, $secretKey);

// Pentru Netopia
$service = new NetopiaService($credentials, $testMode);
$decryptedData = $service->decryptCallback($_POST['data'], $privateKeyPath, $password);
```

---

## 📊 Comparație Gateway-uri

| Gateway | Comisioane | Quick Links | Checkout | Verificare | Țară |
|---------|-----------|-------------|----------|------------|------|
| **Stripe** | 2.9% + 0.30 | ✅ | ✅ | Auto | Global |
| **EuPlatesc** | 1.99% | ✅ | ✅ | Auto | RO |
| **Netopia** | 1.5% | ❌ | ✅ | Auto | RO |
| **PayPal** | 3.4% + 0.35 | ❌ | ✅ | Auto | Global |
| **Transfer Bancar** | 0% | ❌ | ✅ | Manual | Orice |

---

## 🚀 Recomandări

### Pentru Magazine Românești:
1. **EuPlatesc** - comisioane mici, rapid
2. **Netopia** - alternativă solidă
3. **Transfer Bancar** - comenzi mari
4. **Stripe** - backup internațional

### Pentru Magazine Internaționale:
1. **Stripe** - cel mai popular
2. **PayPal** - cunoscut global
3. **Transfer Bancar** - B2B

---

## 📝 Next Steps

1. ✅ Activează gateway-urile dorite
2. ✅ Configurează credențialele reale (înlocuiește placeholders)
3. ✅ Testează în mod test
4. ✅ Implementează webhook handlers
5. ✅ Activează mod live când ești gata

---

**Toate gateway-urile sunt configurate și gata de utilizare!** 🎉
