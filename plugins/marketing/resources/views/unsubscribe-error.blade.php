<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eroare Dezabonare - Carphatian</title>
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
            background: #ef4444;
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
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            color: #991b1b;
            margin-bottom: 24px;
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
            margin: 10px 5px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.35);
            text-decoration: none;
        }
        .btn-secondary {
            background: #6b7280;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🏔️ CARPHATIAN</div>
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
        </div>
        <h1>Eroare la Dezabonare</h1>
        <div class="message">
            {{ $message ?? 'Link-ul de dezabonare este invalid sau a expirat.' }}
        </div>
        <p>Dacă continui să primești email-uri nedorite, te rugăm să ne contactezi.</p>
        
        <div>
            <a href="https://carphatian.ro/contact" class="btn">📧 Contactează-ne</a>
            <a href="https://carphatian.ro" class="btn btn-secondary">🏠 Înapoi la Site</a>
        </div>
    </div>
</body>
</html>
