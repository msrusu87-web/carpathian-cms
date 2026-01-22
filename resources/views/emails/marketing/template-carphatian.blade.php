<!DOCTYPE html>
<html lang="ro" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>Carphatian - Soluții Web Profesionale</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background-color: #f0f4f8;
            -webkit-font-smoothing: antialiased;
        }

        /* Wrapper */
        .email-wrapper {
            width: 100%;
            background-color: #f0f4f8;
            padding: 40px 20px;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        /* Header with gradient */
        .header {
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 50%, #d946ef 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.5;
        }

        .logo-container {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .header-tagline {
            color: rgba(255, 255, 255, 0.95);
            font-size: 16px;
            margin-top: 10px;
            font-weight: 300;
        }

        /* Content area */
        .content {
            padding: 40px 35px;
        }

        .greeting {
            font-size: 22px;
            color: #1a1a2e;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .intro-text {
            font-size: 16px;
            line-height: 1.7;
            color: #4a5568;
            margin-bottom: 30px;
        }

        /* Services Section */
        .services-section {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
        }

        .section-title {
            font-size: 20px;
            color: #1a1a2e;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        /* Service Cards */
        .service-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #8b5cf6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .service-card:hover {
            transform: translateX(5px);
        }

        .service-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .service-title {
            font-size: 17px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .service-description {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .service-price {
            font-size: 18px;
            font-weight: 700;
            color: #8b5cf6;
        }

        /* CTA Button */
        .cta-container {
            text-align: center;
            margin: 35px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.35);
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            box-shadow: 0 12px 35px rgba(139, 92, 246, 0.5);
            transform: translateY(-2px);
        }

        /* Benefits Section */
        .benefits-section {
            padding: 25px 0;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 18px;
            padding: 0 10px;
        }

        .benefit-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-size: 20px;
        }

        .benefit-text h4 {
            font-size: 15px;
            color: #1a1a2e;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .benefit-text p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Stats Bar */
        .stats-bar {
            display: table;
            width: 100%;
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
            padding: 25px 0;
        }

        .stat-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px 5px;
        }

        .stat-number {
            font-size: 26px;
            font-weight: 700;
            color: #00d9ff;
            display: block;
        }

        .stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer */
        .footer {
            background: #f8fafc;
            padding: 35px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-logo {
            font-size: 20px;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 15px;
        }

        .footer-links {
            margin: 20px 0;
        }

        .footer-link {
            display: inline-block;
            color: #64748b;
            text-decoration: none;
            margin: 0 12px;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: #8b5cf6;
        }

        .contact-info {
            margin: 20px 0;
            font-size: 14px;
            color: #64748b;
        }

        .contact-info a {
            color: #8b5cf6;
            text-decoration: none;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-link {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #00d9ff 0%, #8b5cf6 100%);
            border-radius: 50%;
            margin: 0 8px;
            line-height: 40px;
            text-align: center;
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
        }

        .unsubscribe {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }

        .unsubscribe a {
            color: #64748b;
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media screen and (max-width: 600px) {
            .email-wrapper {
                padding: 15px 10px;
            }
            
            .email-container {
                border-radius: 12px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .logo-text {
                font-size: 24px;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .services-section {
                padding: 20px 15px;
            }
            
            .service-card {
                padding: 15px;
            }
            
            .cta-button {
                padding: 14px 30px;
                font-size: 15px;
            }
            
            .stat-item {
                display: block;
                width: 50%;
                float: left;
                margin-bottom: 15px;
            }
            
            .stats-bar::after {
                content: '';
                display: table;
                clear: both;
            }
            
            .footer {
                padding: 25px 20px;
            }
            
            .footer-link {
                display: block;
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <div class="logo-container">
                    <a href="https://carphatian.ro" class="logo-text" style="color: #ffffff; text-decoration: none;">
                        🏔️ CARPHATIAN
                    </a>
                </div>
                <div class="header-tagline">
                    Soluții Web Profesionale pentru Businessul Tău
                </div>
            </div>

            <!-- Main Content -->
            <div class="content">
                <h1 class="greeting">Bună{{ isset($contact_name) && $contact_name ? ', ' . $contact_name : '' }}! 👋</h1>
                
                <p class="intro-text">
                    Ești pregătit să-ți duci afacerea la următorul nivel? La <strong>Carphatian</strong>, 
                    transformăm ideile în realitate digitală cu soluții web moderne, optimizate și 
                    personalizate pentru nevoile tale.
                </p>

                <!-- Services Section -->
                <div class="services-section">
                    <h2 class="section-title">🚀 Serviciile Noastre Premium</h2>
                    
                    <div class="service-card">
                        <div class="service-icon">🌐</div>
                        <div class="service-title">Website Standard</div>
                        <div class="service-description">
                            Prezența ta online profesională cu design modern, optimizare SEO și hosting inclus.
                        </div>
                        <div class="service-price">de la 1.499 RON</div>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">🛒</div>
                        <div class="service-title">E-Commerce Complete</div>
                        <div class="service-description">
                            Magazin online complet cu plăți integrate, gestiune stocuri și design responsive.
                        </div>
                        <div class="service-price">de la 2.999 RON</div>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">💻</div>
                        <div class="service-title">Aplicații Web Custom</div>
                        <div class="service-description">
                            Dezvoltăm aplicații web personalizate pentru automatizarea proceselor tale de business.
                        </div>
                        <div class="service-price">de la 5.999 RON</div>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">🤖</div>
                        <div class="service-title">Integrare AI & Chatboți</div>
                        <div class="service-description">
                            Automatizează suportul clienți cu chatboți inteligenți și integrări AI moderne.
                        </div>
                        <div class="service-price">Preț personalizat</div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="cta-container">
                    <a href="https://carphatian.ro/shop" class="cta-button">
                        🛍️ Vezi Toate Serviciile
                    </a>
                </div>

                <!-- Benefits -->
                <div class="benefits-section">
                    <h2 class="section-title" style="margin-bottom: 20px;">De Ce Să Ne Alegi?</h2>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">✓</div>
                        <div class="benefit-text">
                            <h4>Expertiză Dovedită</h4>
                            <p>Peste 500 de clienți mulțumiți și proiecte de succes în portofoliu.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">⚡</div>
                        <div class="benefit-text">
                            <h4>Livrare Rapidă</h4>
                            <p>Site-ul tău poate fi gata în doar 7-14 zile lucrătoare.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">🛡️</div>
                        <div class="benefit-text">
                            <h4>Suport 24/7</h4>
                            <p>Suntem mereu aici pentru tine cu suport tehnic dedicat.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">💎</div>
                        <div class="benefit-text">
                            <h4>Calitate Premium</h4>
                            <p>Cod curat, design modern și optimizare pentru performanță maximă.</p>
                        </div>
                    </div>
                </div>

                <!-- Secondary CTA -->
                <div class="cta-container">
                    <a href="https://carphatian.ro/contact" class="cta-button" style="background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);">
                        📞 Contactează-ne Acum
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Clienți</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">99.9%</span>
                    <span class="stat-label">Uptime</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Suport</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">5.0⭐</span>
                    <span class="stat-label">Rating</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-logo">🏔️ CARPHATIAN</div>
                
                <div style="margin: 20px 0; font-size: 14px; color: #1a1a2e; line-height: 1.8;">
                    <strong>Carpathian Web Solutions</strong><br>
                    Aziz Ride Sharing SRL<br>
                    Strada Ploiești 47-49, Cluj-Napoca
                </div>
                
                <div class="footer-links">
                    <a href="https://carphatian.ro" class="footer-link">Acasă</a>
                    <a href="https://carphatian.ro/shop" class="footer-link">Servicii</a>
                    <a href="https://carphatian.ro/portfolio" class="footer-link">Portfolio</a>
                    <a href="https://carphatian.ro/blog" class="footer-link">Blog</a>
                    <a href="https://carphatian.ro/contact" class="footer-link">Contact</a>
                </div>

                <div class="contact-info">
                    🌐 <a href="https://www.carphatian.ro">www.carphatian.ro</a><br>
                    📧 <a href="mailto:contact@carphatian.ro">contact@carphatian.ro</a><br>
                    📞 Tel/WhatsApp: <a href="tel:+40774077860">+40 774 077 860</a>
                </div>

                <div class="unsubscribe">
                    <p>Ai primit acest email pentru că ești în lista noastră de contacte.</p>
                    <p><a href="{{ $unsubscribe_url ?? '#' }}">🚫 Dezabonare</a></p>
                </div>

                <p style="margin-top: 20px; font-size: 11px; color: #94a3b8;">
                    © {{ date('Y') }} Aziz Ride Sharing S.R.L. - Carpathian Web Solutions. Toate drepturile rezervate.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
