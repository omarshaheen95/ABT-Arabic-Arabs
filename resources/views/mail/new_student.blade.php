<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب موافقة الطالب</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f3f4f6;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .logo {
            display: block;
        }

        .logo-text h3 {
            font-size: 24px;
            font-weight: bold;
            color: #f4ce0f;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            color: #37331f;
            margin-bottom: 16px;
        }

        .description {
            color: #61634b;
            margin-bottom: 24px;
        }

        .info-box {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .info-row {
            display: flex;
            margin-bottom: 16px;
        }

        .info-label {
            width: 120px;
            color: #6b7280;
        }

        .info-value {
            font-weight: 500;
            color: #1f2937;
            margin-right: 16px;
        }

        .profile-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ebdb25;
            text-decoration: none;
            font-size: 14px;
        }

        .profile-link:hover {
            text-decoration: underline;
        }

        .button-group {
            display: flex;
            gap: 16px;
            margin-top: 32px;
        }

        .button {
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
        }

        .button-primary {
            background-color: #25eb4d;
            color: #ffffff;
        }

        .button-primary:hover {
            background-color: #77d81d;
        }

        .button-secondary {
            background-color: #e5e7eb;
            color: #4b5563;
        }

        .button-secondary:hover {
            background-color: #d1d5db;
        }

        .signature {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <div class="logo">
            <img src="{{ asset('logo.png') }}" width="100%" alt="منصة لغيت الأولى"/>
        </div>
    </div>

    <h1 class="title">طلب موافقة طالب جديد</h1>
    <p class="description">يتطلب تسجيل طالب جديد موافقتك. يرجى مراجعة التفاصيل أدناه:</p>

    <div class="info-box">
        <div class="info-row">
            <div class="info-label">اسم الطالب:</div>
            <div class="info-value">{{$user->name}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">المدرسة:</div>
            <div class="info-value">{{$user->school->name}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">المعلم:</div>
            <div class="info-value">{{$user->teacher->name}}</div>
        </div>
        <a href="{{route('manager.user.edit', $user->id)}}" class="profile-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            عرض ملف الطالب
        </a>
    </div>

    <div class="signature">
        <p>مع أطيب التحيات،</p>
        <p>فريق المنصة التعليمية</p>
    </div>
</div>
</body>
</html>
