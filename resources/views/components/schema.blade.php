{{-- Schema.org Structured Data --}}
@props(['type' => 'organization'])

@if($type === 'organization')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "الطريق المشرق للتدريب والتطوير",
    "alternateName": "Bright Path Training",
    "url": "https://thebrightbath.com",
    "logo": "https://thebrightbath.com/images/bright-path-logo.png",
    "description": "منصة متخصصة في الإرشاد المهني واختبارات الميول والاستشارات المهنية",
    "foundingDate": "2024",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "SA",
        "addressRegion": "المملكة العربية السعودية"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+966543494316",
        "contactType": "customer service",
        "email": "cs@thebrightbath.com",
        "availableLanguage": ["Arabic", "English"]
    },
    "sameAs": [
        "https://wa.me/966543494316"
    ],
    "areaServed": {
        "@type": "Country",
        "name": "المملكة العربية السعودية"
    },
    "serviceType": [
        "Career Counseling",
        "Aptitude Testing",
        "Professional Development",
        "Career Consultation"
    ]
}
</script>
@endif

@if($type === 'website')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "الطريق المشرق للتدريب والتطوير",
    "url": "https://thebrightbath.com",
    "description": "منصة متخصصة في الإرشاد المهني واختبارات الميول والاستشارات المهنية",
    "inLanguage": "ar",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "https://thebrightbath.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
@endif

@if($type === 'breadcrumb')
@php
    $breadcrumbs = $breadcrumbs ?? [];
@endphp
@if(count($breadcrumbs) > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($breadcrumbs as $index => $crumb)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $crumb['name'] }}",
            "item": "{{ $crumb['url'] }}"
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
@endif

@if($type === 'service')
@php
    $service = $service ?? null;
@endphp
@if($service)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "{{ $service['name'] ?? 'خدمة استشارية' }}",
    "description": "{{ $service['description'] ?? '' }}",
    "provider": {
        "@type": "Organization",
        "name": "الطريق المشرق للتدريب والتطوير",
        "url": "https://thebrightbath.com"
    },
    "serviceType": "{{ $service['type'] ?? 'Consulting' }}",
    "areaServed": {
        "@type": "Country",
        "name": "المملكة العربية السعودية"
    }
}
</script>
@endif
@endif

@if($type === 'consultant')
@php
    $consultant = $consultant ?? null;
@endphp
@if($consultant)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "{{ $consultant->user->name ?? '' }}",
    "jobTitle": "{{ $consultant->specialization ?? 'مستشار مهني' }}",
    "description": "{{ $consultant->bio ?? '' }}",
    "worksFor": {
        "@type": "Organization",
        "name": "الطريق المشرق للتدريب والتطوير"
    },
    "knowsAbout": ["الإرشاد المهني", "التطوير الذاتي", "اختبارات الميول"],
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $consultant->rating ?? 5 }}",
        "reviewCount": "{{ $consultant->reviews_count ?? 0 }}",
        "bestRating": "5",
        "worstRating": "1"
    }
}
</script>
@endif
@endif

@if($type === 'assessment')
@php
    $assessment = $assessment ?? null;
@endphp
@if($assessment)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Quiz",
    "name": "{{ $assessment['name'] ?? 'اختبار الميول' }}",
    "description": "{{ $assessment['description'] ?? '' }}",
    "educationalLevel": "جميع المستويات",
    "provider": {
        "@type": "Organization",
        "name": "الطريق المشرق للتدريب والتطوير"
    },
    "about": {
        "@type": "Thing",
        "name": "Career Assessment",
        "description": "اختبار لاكتشاف الميول والقدرات المهنية"
    }
}
</script>
@endif
@endif

@if($type === 'faq')
@php
    $faqs = $faqs ?? [];
@endphp
@if(count($faqs) > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs as $faq)
        {
            "@type": "Question",
            "name": "{{ $faq['question'] }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ $faq['answer'] }}"
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
@endif

@if($type === 'localBusiness')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "الطريق المشرق للتدريب والتطوير",
    "image": "https://thebrightbath.com/images/bright-path-logo.png",
    "url": "https://thebrightbath.com",
    "telephone": "+966543494316",
    "email": "cs@thebrightbath.com",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "SA"
    },
    "priceRange": "$$",
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        "opens": "00:00",
        "closes": "23:59"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.9",
        "reviewCount": "150"
    }
}
</script>
@endif



