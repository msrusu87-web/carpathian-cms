<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.order_confirmation_title') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .order-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-info h2 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }
        .order-details {
            margin: 15px 0;
        }
        .order-details p {
            margin: 8px 0;
        }
        .order-details strong {
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
            color: #8b5cf6;
        }
        .spam-notice {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .spam-notice strong {
            color: #856404;
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .spam-notice p {
            color: #856404;
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('messages.order_confirmation_title') }}</h1>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">{{ __('messages.hello') }} <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>{{ __('messages.thank_you_order') }}</p>
            
            <div class="order-info">
                <h2>{{ __('messages.order_details') }}</h2>
                <div class="order-details">
                    <p><strong>{{ __('messages.order_number') }}:</strong> #{{ $order->id }}</p>
                    <p><strong>{{ __('messages.order_date') }}:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>{{ __('messages.payment_method') }}:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p><strong>{{ __('messages.order_status') }}:</strong> {{ ucfirst($order->order_status) }}</p>
                </div>
            </div>
            
            <h2>{{ __('messages.ordered_items') }}</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        <th>{{ __('messages.price') }}</th>
                        <th>{{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">{{ __('messages.total') }}:</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="order-info">
                <h2>{{ __('messages.shipping_address') }}</h2>
                <p>{{ $order->shipping_address }}</p>
                <p><strong>{{ __('messages.phone') }}:</strong> {{ $order->customer_phone }}</p>
                <p><strong>{{ __('messages.email') }}:</strong> {{ $order->customer_email }}</p>
            </div>
            
            <div class="spam-notice">
                <strong>⚠️ {{ __('messages.important_notice') }}</strong>
                <p>{{ __('messages.spam_folder_notice') }}</p>
                <p>{{ __('messages.add_to_contacts') }}</p>
            </div>
            
            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" class="button">{{ __('messages.visit_website') }}</a>
            </p>
        </div>
        
        <div class="footer">
            <p>{{ __('messages.email_footer') }}</p>
            <p>&copy; {{ date('Y') }} Carpathian CMS. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </div>
</body>
</html>
