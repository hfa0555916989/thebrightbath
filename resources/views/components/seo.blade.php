@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'canonical' => null,
    'type' => 'website'
])

@php
    $defaultTitle = 'الطريق المشرق للتدريب والتطوير - Bright Path';
    $defaultDescription = 'الطريق المشرق للتدريب والتطوير - اختبارات الميول المهنية والإرشاد الوظيفي في المملكة العربية السعودية';
    $defaultKeywords = 'اختبار الميول المهنية، هولاند، MBTI، الذكاءات المتعددة، الإرشاد المهني، التدريب، التطوير، الطريق المشرق';
    $defaultImage = asset('images/og-image.jpg');
    
    $pageTitle = $title ?? $defaultTitle;
    $pageDescription = $description ?? $defaultDescription;
    $pageKeywords = $keywords ?? $defaultKeywords;
    $pageImage = $image ?? $defaultImage;
    $pageCanonical = $canonical ?? url()->current();
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{{-- Primary Meta Tags --}}
<title>{{ $pageTitle }}</title>
<meta name="title" content="{{ $pageTitle }}">
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="author" content="الطريق المشرق للتدريب والتطوير">
<meta name="robots" content="index, follow">
<meta name="language" content="Arabic">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $pageCanonical }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $pageCanonical }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:locale" content="ar_SA">
<meta property="og:site_name" content="الطريق المشرق للتدريب والتطوير">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $pageCanonical }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">

{{-- Favicon --}}
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

{{-- Theme Color --}}
<meta name="theme-color" content="#1F3A63">
<meta name="msapplication-TileColor" content="#1F3A63">



