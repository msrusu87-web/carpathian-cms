# 💳 Ghid Utilizare Payment Gateway Plugin

## Formular Îmbunătățit - User Friendly

Formularul de editare a fost complet refăcut să fie **simplu, clar și organizat pe taburi**.

---

## 📑 Structura Formularului

Formularul are **4 taburi principale**:

### 1️⃣ **Informații de bază**
- **Nume Gateway**: Numele afișat (ex: "Stripe Checkout")
- **Provider**: Selectează procesatorul (Stripe, PayPal, etc.)
- **Gateway Activ**: ON/OFF - activează gateway-ul
- **Mod Test**: ON/OFF - folosește credențiale de test sau live

### 2️⃣ **Credențiale** ⭐ (TAB PRINCIPAL)
Aici introduci **API keys și coduri** specifice fiecărui provider.

Formularul **se adaptează automat** după provider-ul selectat!

#### 🔵 Pentru **Stripe**:
```
Test Secret Key:       sk_test_...
Test Publishable Key:  pk_test_...
Live Secret Key:       sk_live_...
Live Publishable Key:  pk_live_...
Webhook Secret:        whsec_...
```
📍 **Unde găsești**: Stripe Dashboard → Developers → API keys

#### 🟡 Pentru **PayPal**:
```
Sandbox Client ID:     AXh...
Sandbox Secret:        ED...
Live Client ID:        AYh...
Live Secret:           EO...
Webhook ID:            5KJ...
```
📍 **Unde găsești**: PayPal Developer Dashboard → My Apps & Credentials

#### 🟠 Pentru **EuPlatesc**:
```
Merchant ID:           12345
Secret Key:            xxxxxxxxxxxxxxxx
```
📍 **Unde găsești**: Primit de la EuPlatesc după înregistrare

#### 🔴 Pentru **Netopia** (MobilPay):
```
Signature:             XXXX-XXXX-XXXX-XXXX-XXXX
Public Key File:       [Upload public.cer]
Private Key File:      [Upload private.key]
Private Key Password:  parola (opțional)
```
📍 **Unde găsești**: Primit de la Netopia după înregistrare

#### 🟢 Pentru **Transfer Bancar**:
```
Numele Băncii:         Banca Transilvania
Titular Cont:          SC Compania SRL
IBAN:                  RO49AAAA1B31007593840000
Cod SWIFT/BIC:         BTRLRO22
Adresa Băncii:         Cluj-Napoca, Romania
Moneda Contului:       RON
```

### 3️⃣ **Comisioane**
- **Comision Procentual**: Ex: `2.5` pentru 2.5%
- **Comision Fix**: Ex: `0.50` RON per tranzacție
- **Calculator automat**: Vezi exemplu de calcul în timp real

### 4️⃣ **Setări Avansate**
- Link-uri rapide: ON/OFF
- Checkout produse: ON/OFF
- Webhook URL
- Callback URL
- Config JSON (doar pentru avansați)

---

## ✅ Cum Să Configurezi Un Gateway

### Exemplu: Configurare Stripe în Mod Test

1. **Mergi la**: Admin → Gateway-uri de plată → Edit Stripe

2. **Tab "Informații de bază"**:
   - Nume: `Stripe Checkout`
   - Provider: `Stripe`
   - Gateway Activ: ✅ ON
   - Mod Test: ✅ ON

3. **Tab "Credențiale"**:
   - Loghează-te în [Stripe Dashboard](https://dashboard.stripe.com)
   - Mergi la: Developers → API keys
   - Copiază:
     - `Test Secret Key` (sk_test_...)
     - `Test Publishable Key` (pk_test_...)
   - Pentru webhook:
     - Developers → Webhooks → Add endpoint
     - URL: `https://carphatian.ro/webhooks/stripe`
     - Copiază `Webhook Secret` (whsec_...)

4. **Tab "Comisioane"** (opțional):
   - Stripe percepe ~2.9% + 0.30 EUR
   - Poți adăuga comisionul tău: Ex: `0.5%` și `0.20 RON`

5. **Salvează** ✅

---

## 🎯 Diferențe față de Formularul Vechi

| Înainte ❌ | Acum ✅ |
|-----------|---------|
| KeyValue JSON caracter cu caracter | Câmpuri text clare cu labels |
| 56 linii pentru un singur JSON | 5 câmpuri organizate |
| Nu știai ce să introduci unde | Descrieri clare pentru fiecare câmp |
| Config tehnic pe vertical | Taburi compacte organizate |
| Trebuia să știi structura JSON | Formular intuitiv cu placeholders |
| Credențiale vizibile | Password fields cu reveal |

---

## 📋 Checklist înainte de Activare LIVE

- [ ] API Keys testate în mod **Test** și funcționează
- [ ] Webhook-uri configurate la provider
- [ ] Testat o plată reală în Sandbox
- [ ] Comisioane setate corect
- [ ] Schimbat `Mod Test` → OFF
- [ ] Introdus **Live API Keys** (nu test keys!)
- [ ] Testat o plată mică în LIVE
- [ ] Gateway setat ca **Activ** ✅

---

## 🔐 Securitate

- Toate credențialele sunt salvate **encrypted** în baza de date
- Password fields au opțiune "reveal" pentru verificare
- Fișierele de chei (Netopia) sunt salvate în `storage/` (privat)
- Nu se afișează credențiale în logs

---

## 🆘 Troubleshooting

### "Nu văd formularul de credențiale"
→ Ai selectat Provider-ul? Formularul se afișează după selecție.

### "Credențialele mele dispărute"
→ Nu s-au șters, sunt criptate. Dă click pe "reveal" (👁️) pentru a le vedea.

### "Plata nu funcționează"
→ Verifică:
1. Gateway-ul este **Activ**? ✅
2. Ești în `Mod Test` cu chei de test?
3. Webhook-ul este configurat la provider?

### "Unde pun API key-ul?"
→ Depinde de provider:
- **Stripe**: Tab "Credențiale" → `Test Secret Key` (pentru test)
- **PayPal**: Tab "Credențiale" → `Sandbox Client ID` (pentru test)

---

## 📞 Contacte Support Provideri

- **Stripe**: [support.stripe.com](https://support.stripe.com)
- **PayPal**: [developer.paypal.com/support](https://developer.paypal.com/support)
- **EuPlatesc**: support@euplatesc.ro
- **Netopia**: clienti@netopia-payments.ro

---

**Versiune**: 2.0.0 (User-Friendly Edition)  
**Data actualizare**: 20 Decembrie 2025  
**Status**: ✅ Production Ready
