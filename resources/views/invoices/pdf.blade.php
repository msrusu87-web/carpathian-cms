<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .container { padding: 40px; }
        .header { margin-bottom: 40px; }
        .company-logo { font-size: 24px; font-weight: bold; color: #8B5CF6; margin-bottom: 10px; }
        .company-info { font-size: 10px; color: #666; line-height: 1.5; }
        .invoice-header { display: table; width: 100%; margin-bottom: 30px; }
        .invoice-header > div { display: table-cell; vertical-align: top; }
        .invoice-title { font-size: 28px; font-weight: bold; color: #8B5CF6; margin-bottom: 10px; }
        .invoice-meta { font-size: 11px; }
        .invoice-meta div { margin-bottom: 5px; }
        .bill-to { margin-bottom: 30px; }
        .bill-to h3 { font-size: 12px; margin-bottom: 10px; text-transform: uppercase; color: #666; }
        .bill-to-content { font-size: 11px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead { background-color: #8B5CF6; color: white; }
        th { padding: 10px; text-align: left; font-size: 11px; }
        td { padding: 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        .text-right { text-align: right; }
        .totals { width: 300px; margin-left: auto; }
        .totals tr td { border-bottom: none; padding: 5px 10px; }
        .totals tr.total td { font-weight: bold; font-size: 14px; border-top: 2px solid #333; padding-top: 10px; }
        .notes { margin-top: 30px; padding: 15px; background-color: #f9f9f9; border-left: 3px solid #8B5CF6; }
        .notes h4 { font-size: 12px; margin-bottom: 10px; }
        .notes p { font-size: 10px; line-height: 1.6; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #666; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 3px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .status-paid { background-color: #10B981; color: white; }
        .status-unpaid { background-color: #EF4444; color: white; }
        .status-draft { background-color: #6B7280; color: white; }
        .status-overdue { background-color: #F59E0B; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-logo">CARPHATIAN</div>
            <div class="company-info">
                Carphatian Web Development<br>
                Str. Exemplu Nr. 123, Cluj-Napoca, România<br>
                CUI: RO12345678 | J12/1234/2024<br>
                Email: contact@carphatian.ro | Tel: +40 123 456 789
            </div>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div style="width: 50%;">
                <div class="invoice-title">FACTURĂ</div>
                <div class="invoice-meta">
                    <div><strong>Nr:</strong> {{ $invoice->invoice_number }}</div>
                    <div><strong>Data:</strong> {{ $invoice->invoice_date->format('d.m.Y') }}</div>
                    <div><strong>Scadență:</strong> {{ $invoice->due_date->format('d.m.Y') }}</div>
                </div>
            </div>
            <div style="width: 50%; text-align: right;">
                <span class="status-badge status-{{ $invoice->payment_status }}">
                    {{ strtoupper($invoice->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Bill To -->
        <div class="bill-to">
            <h3>Facturat către:</h3>
            <div class="bill-to-content">
                <strong>{{ $invoice->client_name }}</strong><br>
                @if($invoice->client_company)
                    {{ $invoice->client_company }}<br>
                    @if($invoice->client_company_reg)
                        CUI: {{ $invoice->client_company_reg }}<br>
                    @endif
                    @if($invoice->client_vat_number)
                        Nr. Înreg.: {{ $invoice->client_vat_number }}<br>
                    @endif
                @endif
                {{ $invoice->client_address }}<br>
                {{ $invoice->client_city }}, {{ $invoice->client_postal_code }}, {{ $invoice->client_country }}<br>
                @if($invoice->client_phone)
                    Tel: {{ $invoice->client_phone }}<br>
                @endif
                Email: {{ $invoice->client_email }}
            </div>
        </div>

        <!-- Invoice Items -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Descriere</th>
                    <th class="text-right" style="width: 10%;">Cant.</th>
                    <th class="text-right" style="width: 15%;">Preț Unitar</th>
                    <th class="text-right" style="width: 10%;">TVA</th>
                    <th class="text-right" style="width: 15%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }} RON</td>
                    <td class="text-right">{{ number_format($item->tax_rate, 0) }}%</td>
                    <td class="text-right">{{ number_format($item->total, 2) }} RON</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ number_format($invoice->subtotal, 2) }} RON</td>
            </tr>
            <tr>
                <td>TVA ({{ number_format($invoice->tax_rate, 0) }}%):</td>
                <td class="text-right">{{ number_format($invoice->tax_amount, 2) }} RON</td>
            </tr>
            @if($invoice->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right">-{{ number_format($invoice->discount_amount, 2) }} RON</td>
            </tr>
            @endif
            <tr class="total">
                <td>TOTAL DE PLATĂ:</td>
                <td class="text-right">{{ number_format($invoice->total, 2) }} RON</td>
            </tr>
        </table>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <h4>Note:</h4>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Payment Info -->
        @if($invoice->payment_status === 'paid')
        <div class="notes" style="background-color: #D1FAE5; border-left-color: #10B981;">
            <h4>Informații Plată:</h4>
            <p>
                Factură achitată la data de {{ $invoice->paid_at->format('d.m.Y') }}<br>
                @if($invoice->payment_method)
                    Metodă plată: {{ $invoice->payment_method }}<br>
                @endif
                @if($invoice->payment_reference)
                    Referință: {{ $invoice->payment_reference }}
                @endif
            </p>
        </div>
        @endif

        <!-- Terms -->
        @if($invoice->terms)
        <div class="notes">
            <h4>Termeni și Condiții:</h4>
            <p>{{ $invoice->terms }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>{{ $invoice->footer_text ?? 'Mulțumim pentru colaborare! | www.carphatian.ro' }}</p>
        </div>
    </div>
</body>
</html>
