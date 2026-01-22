<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($is_test) ? 'Test Dezabonare' : 'Dezabonat cu Succes' }} - Carphatian</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 50%, #d946ef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 20px;
        }
        .icon {
            width: 80px;
            height: 80px;
            background: {{ isset($is_test) ? 'linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%)' : '#10b981' }};
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }
        h1 {
            color: #1f2937;
            margin: 0 0 16px;
            font-size: 24px;
        }
        p {
            color: #6b7280;
            margin: 0 0 24px;
            line-height: 1.6;
        }
        .message {
            background: {{ isset($is_test) ? '#eef2ff' : '#f0fdf4' }};
            border: 1px solid {{ isset($is_test) ? '#c7d2fe' : '#bbf7d0' }};
            border-radius: 8px;
            padding: 16px;
            color: {{ isset($is_test) ? '#4338ca' : '#166534' }};
            margin-bottom: 24px;
        }
        .test-badge {
            display: inline-block;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        a {
            color: #8b5cf6;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            color: white !important;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.35);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🏔️ CARPHATIAN</div>
        
        @if(isset($is_test) && $is_test)
        <div class="test-badge">🧪 Email de Test</div>
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        </div>
        <h1>Link de Test Funcțional! ✅</h1>
        @else
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
        </div>
        <h1>Dezabonat cu Succes</h1>
        @endif
        
        <div class="message">
            {{ $message ?? 'Ai fost eliminat din lista noastră de email-uri.' }}
        </div>
        
        @if(isset($is_test) && $is_test)
        <p>Acesta este un email de test. Într-o campanie reală, ai fi fost dezabonat din lista noastră de corespondență.</p>
        <p>Design-ul email-ului arată bine? 🎨</p>
        @else
        <p>Nu vei mai primi email-uri de marketing de la noi.</p>
        @endif
        
        <a href="https://carphatian.ro" class="btn">🏠 Înapoi la Site</a>
        
        <p style="margin-top: 30px; font-size: 14px;">
            <a href="https://carphatian.ro/contact">📧 Contactează-ne</a>
        </p>
    </div>
</body>
</html>
