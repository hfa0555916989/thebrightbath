<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - الطريق المشرق</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background-color: #f5f7fa;
            direction: rtl;
            line-height: 1.8;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #1a5f7a 0%, #0d3d4d 100%);
            padding: 30px;
            text-align: center;
        }
        .email-header img {
            max-width: 150px;
            height: auto;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            margin-top: 15px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #1a5f7a;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .content {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 25px;
        }
        .btn {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #c9a227 0%, #d4af37 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 162, 39, 0.4);
        }
        .info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-right: 4px solid #1a5f7a;
        }
        .info-box h3 {
            color: #1a5f7a;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6c757d;
            font-weight: 500;
        }
        .info-value {
            color: #2d3748;
            font-weight: 600;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #1a5f7a;
            text-decoration: none;
        }
        .copyright {
            color: #adb5bd;
            font-size: 12px;
        }
        .highlight {
            background: linear-gradient(120deg, #fef3c7 0%, #fef3c7 100%);
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .success-badge {
            background: #d4edda;
            color: #155724;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
        }
        .warning-text {
            color: #856404;
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('images/logo-white.png') }}" alt="الطريق المشرق">
            <h1>@yield('header-title', 'الطريق المشرق للتدريب والتطوير')</h1>
        </div>
        
        <div class="email-body">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p class="footer-text">تواصل معنا</p>
            <div class="social-links">
                <a href="mailto:cs@thebrightbath.com">📧 cs@thebrightbath.com</a>
                <a href="https://wa.me/966543494316">📱 واتساب</a>
            </div>
            <p class="copyright">© {{ date('Y') }} الطريق المشرق للتدريب والتطوير. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>



