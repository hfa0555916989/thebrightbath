<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جلسة استشارية - {{ $booking->consultant->user->name }}</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    {{-- Jitsi Meet API --}}
    <script src="https://meet.jit.si/external_api.js"></script>
    
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        #jitsi-container { height: calc(100vh - 80px); }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#1F3A63',
                            dark: '#162032',
                            gold: '#F8C524',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900">
    {{-- Header --}}
    <header class="h-20 bg-brand-dark flex items-center justify-between px-6 border-b border-gray-700">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/bright-path-logo.png') }}" alt="الطريق المشرق - Bright Path" class="h-16">
            <div class="text-white">
                <h1 class="font-bold text-lg">جلسة استشارية</h1>
                <p class="text-gray-400 text-sm">
                    مع {{ $userRole === 'consultant' ? $booking->user->name : $booking->consultant->user->name }}
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            {{-- Booking Info --}}
            <div class="hidden md:flex items-center gap-6 text-gray-300 text-sm">
                <span>
                    <i class="fas fa-calendar ml-1"></i>
                    {{ $booking->booking_date->format('Y-m-d') }}
                </span>
                <span>
                    <i class="fas fa-clock ml-1"></i>
                    {{ date('h:i A', strtotime($booking->start_time)) }}
                </span>
                <span>
                    <i class="fas fa-hourglass-half ml-1"></i>
                    {{ $booking->duration_minutes }} دقيقة
                </span>
            </div>
            
            {{-- End Meeting Button --}}
            <form action="{{ route('meeting.end', $booking) }}" method="POST" id="endMeetingForm">
                @csrf
                <button type="button" onclick="confirmEndMeeting()" 
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="fas fa-phone-slash ml-2"></i>
                    إنهاء الجلسة
                </button>
            </form>
        </div>
    </header>
    
    {{-- Jitsi Container --}}
    <div id="jitsi-container"></div>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        // Initialize Jitsi Meet
        const domain = 'meet.jit.si';
        const options = {
            roomName: '{{ $roomName }}',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#jitsi-container'),
            userInfo: {
                displayName: '{{ $displayName }}',
                email: '{{ auth()->user()->email }}'
            },
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                disableDeepLinking: true,
                prejoinPageEnabled: false,
                enableWelcomePage: false,
                enableClosePage: false,
                disableInviteFunctions: true,
                toolbarButtons: [
                    'microphone',
                    'camera',
                    'desktop',
                    'fullscreen',
                    'chat',
                    'raisehand',
                    'tileview',
                    'settings',
                    'filmstrip'
                ],
                // Security settings
                enableLobby: false,
                requireDisplayName: true,
                // Branding
                defaultLanguage: 'ar',
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                SHOW_BRAND_WATERMARK: false,
                BRAND_WATERMARK_LINK: '',
                SHOW_POWERED_BY: false,
                SHOW_PROMOTIONAL_CLOSE_PAGE: false,
                DISABLE_JOIN_LEAVE_NOTIFICATIONS: false,
                MOBILE_APP_PROMO: false,
                HIDE_INVITE_MORE_HEADER: true,
                TOOLBAR_BUTTONS: [
                    'microphone',
                    'camera',
                    'desktop',
                    'fullscreen',
                    'chat',
                    'raisehand',
                    'tileview',
                    'settings',
                    'filmstrip'
                ],
                SETTINGS_SECTIONS: ['devices', 'language'],
                DEFAULT_BACKGROUND: '#1F3A63',
                DISABLE_TRANSCRIPTION_SUBTITLES: true,
            }
        };
        
        const api = new JitsiMeetExternalAPI(domain, options);
        
        // Event listeners
        api.addEventListener('videoConferenceJoined', () => {
            console.log('Joined the meeting');
        });
        
        api.addEventListener('videoConferenceLeft', () => {
            console.log('Left the meeting');
        });
        
        api.addEventListener('participantJoined', (participant) => {
            console.log('Participant joined:', participant.displayName);
        });
        
        // Handle page unload
        window.addEventListener('beforeunload', () => {
            api.dispose();
        });
        
        // Confirm end meeting
        function confirmEndMeeting() {
            if (confirm('هل أنت متأكد من إنهاء الجلسة؟')) {
                api.dispose();
                document.getElementById('endMeetingForm').submit();
            }
        }
        
        // Auto-end meeting based on duration (optional warning)
        const durationMinutes = {{ $booking->duration_minutes }};
        const warningTime = (durationMinutes - 5) * 60 * 1000; // 5 minutes before end
        
        setTimeout(() => {
            alert('تنبيه: متبقي 5 دقائق على انتهاء وقت الجلسة');
        }, warningTime);
    </script>
</body>
</html>

