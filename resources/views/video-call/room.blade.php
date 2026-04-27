@extends('layouts.public')

@section('title', 'غرفة الجلسة - ' . $booking->booking_number)

@push('styles')
<style>
    .chat-messages { height: calc(100% - 130px); }
    .chat-messages::-webkit-scrollbar { width: 6px; }
    .chat-messages::-webkit-scrollbar-track { background: #1f2937; }
    .chat-messages::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
    .message-mine { background: linear-gradient(135deg, #d4a855, #b8943f); }
    .message-other { background: #374151; }
    #video-container { transition: all 0.3s ease; }
    .video-only #video-container { width: 100%; }
    .with-chat #video-container { width: calc(100% - 380px); }
    @media (max-width: 1024px) {
        .with-chat #video-container { width: 100%; }
        .chat-panel { position: fixed; right: -100%; width: 100%; max-width: 400px; z-index: 50; transition: right 0.3s; }
        .chat-panel.open { right: 0; }
    }
</style>
@endpush

@section('content')
<div class="h-screen bg-gray-900 flex flex-col overflow-hidden" id="call-container">
    <!-- Header -->
    <div class="bg-black/50 backdrop-blur-sm border-b border-white/10 px-4 py-3 flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="text-white flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-gold to-brand-orange flex items-center justify-center">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h1 class="font-bold">جلسة #{{ $booking->booking_number }}</h1>
                    <p class="text-sm text-gray-400">{{ $isConsultant ? 'العميل: ' : 'المستشار: ' }}{{ $otherUserName }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div id="call-timer" class="text-white font-mono text-lg hidden px-3 py-1 bg-white/10 rounded-lg">00:00</div>
                <span id="connection-status" class="px-3 py-1 rounded-full text-sm bg-yellow-500 text-white">
                    <i class="fas fa-circle text-xs ml-1 animate-pulse"></i> جاري الاتصال
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden with-chat" id="main-content">
        <!-- Video Section -->
        <div id="video-container" class="relative flex-1 bg-gray-800">
            <!-- Remote Video -->
            <div class="absolute inset-0">
                <video id="remote-video" class="w-full h-full object-cover" autoplay playsinline></video>
                <div id="remote-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-white bg-gradient-to-br from-gray-800 to-gray-900">
                    <div class="w-28 h-28 rounded-full bg-gradient-to-br from-brand-gold/30 to-brand-orange/30 flex items-center justify-center mb-4 animate-pulse">
                        <i class="fas fa-user text-5xl text-brand-gold"></i>
                    </div>
                    <p class="text-xl font-bold">{{ $otherUserName }}</p>
                    <p class="text-gray-400 mt-2" id="waiting-message">في انتظار الانضمام...</p>
                </div>
            </div>

            <!-- Local Video (PiP) -->
            <div class="absolute bottom-20 left-4 w-40 h-28 md:w-56 md:h-40 bg-gray-900 rounded-xl overflow-hidden shadow-2xl border-2 border-white/20 z-10 cursor-move" id="local-video-container">
                <video id="local-video" class="w-full h-full object-cover" autoplay playsinline muted></video>
                <div id="local-placeholder" class="absolute inset-0 flex items-center justify-center bg-gray-800 hidden">
                    <i class="fas fa-video-slash text-2xl text-gray-500"></i>
                </div>
                <div class="absolute bottom-1 right-2 text-white text-xs bg-black/60 px-2 py-0.5 rounded">
                    {{ $userName }}
                </div>
            </div>

            <!-- Audio Only Indicator -->
            <div id="audio-only-indicator" class="hidden absolute top-4 left-4 bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-phone-alt ml-2"></i> اتصال صوتي فقط
            </div>
        </div>

        <!-- Chat Panel -->
        <div class="w-96 bg-gray-900 border-l border-gray-700 flex flex-col chat-panel" id="chat-panel">
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                <h3 class="text-white font-bold flex items-center gap-2">
                    <i class="fas fa-comments text-brand-gold"></i> الدردشة
                </h3>
                <button id="close-chat-mobile" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 chat-messages" id="chat-messages">
                @foreach($messages as $msg)
                <div class="flex {{ $msg->user_id == $currentUserId ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[80%] rounded-xl px-4 py-2 {{ $msg->user_id == $currentUserId ? 'message-mine text-brand-dark' : 'message-other text-white' }}">
                        @if($msg->type === 'file')
                            <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="flex items-center gap-2 hover:opacity-80">
                                <i class="fas fa-file"></i>
                                <span class="text-sm">{{ $msg->file_name }}</span>
                            </a>
                        @else
                            <p class="text-sm">{{ $msg->content }}</p>
                        @endif
                        <span class="text-xs opacity-60 block mt-1">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- File Upload Preview -->
            <div id="file-preview" class="hidden p-3 bg-gray-800 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-white">
                        <i class="fas fa-file text-brand-gold"></i>
                        <span id="file-preview-name" class="text-sm truncate max-w-[200px]"></span>
                    </div>
                    <button id="cancel-file" class="text-red-400 hover:text-red-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-4 border-t border-gray-700">
                <form id="chat-form" class="flex items-center gap-2">
                    <label class="cursor-pointer text-gray-400 hover:text-brand-gold transition p-2">
                        <i class="fas fa-paperclip text-lg"></i>
                        <input type="file" id="file-input" class="hidden" accept="*/*">
                    </label>
                    <input type="text" id="chat-input" placeholder="اكتب رسالة..." 
                           class="flex-1 bg-gray-800 text-white rounded-xl px-4 py-3 border border-gray-600 focus:border-brand-gold focus:outline-none">
                    <button type="submit" class="bg-brand-gold text-brand-dark p-3 rounded-xl hover:bg-brand-goldDeep transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Controls Bar -->
    <div class="bg-black/80 backdrop-blur-lg border-t border-white/10 px-4 py-4 flex-shrink-0">
        <div class="flex items-center justify-center gap-3 md:gap-4">
            <!-- Mic -->
            <button id="toggle-mic" class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center transition-all" title="كتم/تشغيل الصوت">
                <i class="fas fa-microphone text-lg md:text-xl"></i>
            </button>

            <!-- Camera -->
            <button id="toggle-camera" class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center transition-all" title="الكاميرا">
                <i class="fas fa-video text-lg md:text-xl"></i>
            </button>

            <!-- Screen Share -->
            <button id="toggle-screen" class="hidden md:flex w-14 h-14 rounded-full bg-gray-700 hover:bg-gray-600 text-white items-center justify-center transition-all" title="مشاركة الشاشة">
                <i class="fas fa-desktop text-xl"></i>
            </button>

            <!-- Audio Only Mode -->
            <button id="toggle-audio-only" class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center transition-all" title="صوتي فقط">
                <i class="fas fa-phone-alt text-lg md:text-xl"></i>
            </button>

            <!-- End Call -->
            <form action="{{ route('video-call.end', $booking) }}" method="POST" id="end-call-form">
                @csrf
                <button type="submit" class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center transition-all" title="إنهاء المكالمة">
                    <i class="fas fa-phone-slash text-lg md:text-xl"></i>
                </button>
            </form>

            <!-- Chat Toggle (Mobile) -->
            <button id="toggle-chat" class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center transition-all relative" title="الدردشة">
                <i class="fas fa-comments text-lg md:text-xl"></i>
                <span id="chat-badge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">0</span>
            </button>

            <!-- Fullscreen -->
            <button id="toggle-fullscreen" class="hidden md:flex w-14 h-14 rounded-full bg-gray-700 hover:bg-gray-600 text-white items-center justify-center transition-all" title="ملء الشاشة">
                <i class="fas fa-expand text-xl"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // Configuration
    const config = {
        videoCallId: {{ $videoCall->id }},
        currentUserId: {{ $currentUserId }},
        otherUserId: {{ $otherUserId }},
        signalUrl: '{{ route("video-call.signal", $videoCall) }}',
        getSignalsUrl: '{{ route("video-call.get-signals", $videoCall) }}',
        sendMessageUrl: '{{ route("video-call.send-message", $videoCall) }}',
        getMessagesUrl: '{{ route("video-call.get-messages", $videoCall) }}',
        uploadFileUrl: '{{ route("video-call.upload-file", $videoCall) }}',
        csrfToken: '{{ csrf_token() }}',
    };

    const iceServers = [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
    ];

    // State
    let localStream = null;
    let remoteStream = null;
    let peerConnection = null;
    let pollingInterval = null;
    let messagePollingInterval = null;
    let timerInterval = null;
    let callStartTime = null;
    let isMuted = false;
    let isCameraOff = false;
    let isScreenSharing = false;
    let isAudioOnly = false;
    let originalVideoTrack = null;
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};
    let unreadMessages = 0;
    let selectedFile = null;

    // DOM Elements
    const localVideo = document.getElementById('local-video');
    const remoteVideo = document.getElementById('remote-video');
    const localPlaceholder = document.getElementById('local-placeholder');
    const remotePlaceholder = document.getElementById('remote-placeholder');
    const connectionStatus = document.getElementById('connection-status');
    const callTimer = document.getElementById('call-timer');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const chatForm = document.getElementById('chat-form');
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const chatPanel = document.getElementById('chat-panel');
    const chatBadge = document.getElementById('chat-badge');
    const audioOnlyIndicator = document.getElementById('audio-only-indicator');

    // Initialize
    async function init() {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({
                video: { width: 1280, height: 720, facingMode: 'user' },
                audio: { echoCancellation: true, noiseSuppression: true }
            });
            
            localVideo.srcObject = localStream;
            originalVideoTrack = localStream.getVideoTracks()[0];

            createPeerConnection();
            startPolling();
            startMessagePolling();
            createOffer();

        } catch (error) {
            console.error('Error accessing media devices:', error);
            // Try audio only
            try {
                localStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                localVideo.srcObject = localStream;
                enableAudioOnlyMode();
                createPeerConnection();
                startPolling();
                startMessagePolling();
                createOffer();
            } catch (audioError) {
                updateStatus('خطأ في الوصول للميديا', 'error');
                alert('يرجى السماح بالوصول للمايكروفون على الأقل');
            }
        }
    }

    function enableAudioOnlyMode() {
        isAudioOnly = true;
        audioOnlyIndicator.classList.remove('hidden');
        document.getElementById('toggle-camera').classList.add('hidden');
        document.getElementById('toggle-audio-only').classList.add('bg-blue-600');
        document.getElementById('toggle-audio-only').classList.remove('bg-gray-700');
        localPlaceholder.classList.remove('hidden');
    }

    function createPeerConnection() {
        peerConnection = new RTCPeerConnection({ iceServers });

        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        peerConnection.ontrack = (event) => {
            if (!remoteStream) {
                remoteStream = new MediaStream();
                remoteVideo.srcObject = remoteStream;
            }
            remoteStream.addTrack(event.track);
            remotePlaceholder.classList.add('hidden');
            startCallTimer();
            updateStatus('متصل', 'success');
        };

        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                sendSignal('ice_candidate', event.candidate);
            }
        };

        peerConnection.onconnectionstatechange = () => {
            const state = peerConnection.connectionState;
            switch (state) {
                case 'connecting': updateStatus('جاري الاتصال', 'warning'); break;
                case 'connected': updateStatus('متصل', 'success'); break;
                case 'disconnected': updateStatus('انقطع الاتصال', 'error'); break;
                case 'failed': updateStatus('فشل الاتصال', 'error'); break;
            }
        };
    }

    async function createOffer() {
        try {
            const offer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(offer);
            sendSignal('offer', offer);
        } catch (error) {
            console.error('Error creating offer:', error);
        }
    }

    async function handleOffer(offer) {
        try {
            if (peerConnection.signalingState !== 'stable') {
                await peerConnection.setLocalDescription({ type: 'rollback' });
            }
            await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);
            sendSignal('answer', answer);
        } catch (error) {
            console.error('Error handling offer:', error);
        }
    }

    async function handleAnswer(answer) {
        try {
            if (peerConnection.signalingState === 'have-local-offer') {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
            }
        } catch (error) {
            console.error('Error handling answer:', error);
        }
    }

    async function handleIceCandidate(candidate) {
        try {
            if (peerConnection.remoteDescription) {
                await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
            }
        } catch (error) {
            console.error('Error adding ICE candidate:', error);
        }
    }

    function sendSignal(type, data) {
        fetch(config.signalUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                type: type,
                data: JSON.stringify(data),
                to_user_id: config.otherUserId,
            }),
        }).catch(error => console.error('Error sending signal:', error));
    }

    function startPolling() {
        pollingInterval = setInterval(async () => {
            try {
                const response = await fetch(config.getSignalsUrl, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrfToken },
                });
                const data = await response.json();

                for (const signal of data.signals) {
                    const signalData = typeof signal.data === 'string' ? JSON.parse(signal.data) : signal.data;
                    switch (signal.type) {
                        case 'offer': await handleOffer(signalData); break;
                        case 'answer': await handleAnswer(signalData); break;
                        case 'ice_candidate': await handleIceCandidate(signalData); break;
                    }
                }
            } catch (error) {
                console.error('Error polling signals:', error);
            }
        }, 1000);
    }

    function startMessagePolling() {
        messagePollingInterval = setInterval(async () => {
            try {
                const response = await fetch(`${config.getMessagesUrl}?last_id=${lastMessageId}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrfToken },
                });
                const data = await response.json();

                data.messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        addMessageToChat(msg);
                        lastMessageId = msg.id;
                        if (msg.user_id !== config.currentUserId) {
                            unreadMessages++;
                            updateChatBadge();
                        }
                    }
                });
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        }, 2000);
    }

    function addMessageToChat(msg) {
        const isMe = msg.user_id === config.currentUserId;
        const div = document.createElement('div');
        div.className = `flex ${isMe ? 'justify-start' : 'justify-end'}`;
        
        let content = '';
        if (msg.type === 'file') {
            content = `<a href="${msg.file_path}" target="_blank" class="flex items-center gap-2 hover:opacity-80">
                <i class="fas fa-file"></i>
                <span class="text-sm">${msg.file_name}</span>
                <span class="text-xs opacity-60">(${msg.file_size})</span>
            </a>`;
        } else {
            content = `<p class="text-sm">${msg.content}</p>`;
        }

        div.innerHTML = `
            <div class="max-w-[80%] rounded-xl px-4 py-2 ${isMe ? 'message-mine text-brand-dark' : 'message-other text-white'}">
                ${content}
                <span class="text-xs opacity-60 block mt-1">${msg.created_at}</span>
            </div>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function updateChatBadge() {
        if (unreadMessages > 0) {
            chatBadge.classList.remove('hidden');
            chatBadge.textContent = unreadMessages > 9 ? '9+' : unreadMessages;
        } else {
            chatBadge.classList.add('hidden');
        }
    }

    function updateStatus(text, type) {
        connectionStatus.innerHTML = `<i class="fas fa-circle text-xs ml-1 ${type === 'success' ? '' : 'animate-pulse'}"></i> ${text}`;
        connectionStatus.className = 'px-3 py-1 rounded-full text-sm text-white ';
        switch (type) {
            case 'success': connectionStatus.classList.add('bg-green-500'); break;
            case 'warning': connectionStatus.classList.add('bg-yellow-500'); break;
            case 'error': connectionStatus.classList.add('bg-red-500'); break;
            default: connectionStatus.classList.add('bg-gray-500');
        }
    }

    function startCallTimer() {
        if (timerInterval) return;
        callStartTime = Date.now();
        callTimer.classList.remove('hidden');
        timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - callStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
            const seconds = (elapsed % 60).toString().padStart(2, '0');
            callTimer.textContent = `${minutes}:${seconds}`;
        }, 1000);
    }

    // Chat Form
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (selectedFile) {
            await uploadFile();
            return;
        }

        const content = chatInput.value.trim();
        if (!content) return;

        try {
            const response = await fetch(config.sendMessageUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ content }),
            });
            const data = await response.json();
            if (data.success) {
                addMessageToChat(data.message);
                lastMessageId = data.message.id;
                chatInput.value = '';
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });

    // File Input
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 10 * 1024 * 1024) {
                alert('حجم الملف يجب أن يكون أقل من 10 ميجابايت');
                return;
            }
            selectedFile = file;
            document.getElementById('file-preview-name').textContent = file.name;
            filePreview.classList.remove('hidden');
            chatInput.placeholder = 'اضغط إرسال لرفع الملف...';
        }
    });

    document.getElementById('cancel-file').addEventListener('click', () => {
        selectedFile = null;
        fileInput.value = '';
        filePreview.classList.add('hidden');
        chatInput.placeholder = 'اكتب رسالة...';
    });

    async function uploadFile() {
        if (!selectedFile) return;

        const formData = new FormData();
        formData.append('file', selectedFile);

        try {
            const response = await fetch(config.uploadFileUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });
            const data = await response.json();
            if (data.success) {
                addMessageToChat(data.message);
                lastMessageId = data.message.id;
                selectedFile = null;
                fileInput.value = '';
                filePreview.classList.add('hidden');
                chatInput.placeholder = 'اكتب رسالة...';
            }
        } catch (error) {
            console.error('Error uploading file:', error);
        }
    }

    // Controls
    document.getElementById('toggle-mic').addEventListener('click', function() {
        if (localStream) {
            const audioTrack = localStream.getAudioTracks()[0];
            if (audioTrack) {
                isMuted = !isMuted;
                audioTrack.enabled = !isMuted;
                this.innerHTML = isMuted ? '<i class="fas fa-microphone-slash text-lg md:text-xl"></i>' : '<i class="fas fa-microphone text-lg md:text-xl"></i>';
                this.classList.toggle('bg-red-600', isMuted);
                this.classList.toggle('bg-gray-700', !isMuted);
            }
        }
    });

    document.getElementById('toggle-camera').addEventListener('click', function() {
        if (localStream && !isAudioOnly) {
            const videoTrack = localStream.getVideoTracks()[0];
            if (videoTrack) {
                isCameraOff = !isCameraOff;
                videoTrack.enabled = !isCameraOff;
                this.innerHTML = isCameraOff ? '<i class="fas fa-video-slash text-lg md:text-xl"></i>' : '<i class="fas fa-video text-lg md:text-xl"></i>';
                this.classList.toggle('bg-red-600', isCameraOff);
                this.classList.toggle('bg-gray-700', !isCameraOff);
                localPlaceholder.classList.toggle('hidden', !isCameraOff);
            }
        }
    });

    document.getElementById('toggle-audio-only').addEventListener('click', async function() {
        if (isAudioOnly) {
            // Try to enable video
            try {
                const videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
                const videoTrack = videoStream.getVideoTracks()[0];
                localStream.addTrack(videoTrack);
                originalVideoTrack = videoTrack;
                localVideo.srcObject = localStream;
                
                const sender = peerConnection.getSenders().find(s => s.track?.kind === 'video');
                if (sender) {
                    await sender.replaceTrack(videoTrack);
                } else {
                    peerConnection.addTrack(videoTrack, localStream);
                }
                
                isAudioOnly = false;
                audioOnlyIndicator.classList.add('hidden');
                document.getElementById('toggle-camera').classList.remove('hidden');
                this.classList.remove('bg-blue-600');
                this.classList.add('bg-gray-700');
                localPlaceholder.classList.add('hidden');
            } catch (e) {
                alert('لا يمكن تشغيل الكاميرا');
            }
        } else {
            enableAudioOnlyMode();
            if (originalVideoTrack) {
                originalVideoTrack.stop();
            }
        }
    });

    const toggleScreen = document.getElementById('toggle-screen');
    if (toggleScreen) {
        toggleScreen.addEventListener('click', async function() {
            try {
                if (!isScreenSharing) {
                    const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
                    const screenTrack = screenStream.getVideoTracks()[0];
                    
                    const sender = peerConnection.getSenders().find(s => s.track?.kind === 'video');
                    if (sender) {
                        await sender.replaceTrack(screenTrack);
                    }
                    
                    localVideo.srcObject = screenStream;
                    screenTrack.onended = () => stopScreenShare();
                    
                    isScreenSharing = true;
                    this.classList.add('bg-green-600');
                    this.classList.remove('bg-gray-700');
                } else {
                    stopScreenShare();
                }
            } catch (error) {
                console.error('Error sharing screen:', error);
            }
        });
    }

    async function stopScreenShare() {
        if (originalVideoTrack && peerConnection) {
            const sender = peerConnection.getSenders().find(s => s.track?.kind === 'video');
            if (sender) {
                await sender.replaceTrack(originalVideoTrack);
            }
            localVideo.srcObject = localStream;
        }
        isScreenSharing = false;
        const btn = document.getElementById('toggle-screen');
        if (btn) {
            btn.classList.remove('bg-green-600');
            btn.classList.add('bg-gray-700');
        }
    }

    document.getElementById('toggle-chat').addEventListener('click', function() {
        chatPanel.classList.toggle('open');
        unreadMessages = 0;
        updateChatBadge();
    });

    document.getElementById('close-chat-mobile').addEventListener('click', function() {
        chatPanel.classList.remove('open');
    });

    const toggleFullscreen = document.getElementById('toggle-fullscreen');
    if (toggleFullscreen) {
        toggleFullscreen.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                this.innerHTML = '<i class="fas fa-compress text-xl"></i>';
            } else {
                document.exitFullscreen();
                this.innerHTML = '<i class="fas fa-expand text-xl"></i>';
            }
        });
    }

    // Cleanup
    window.addEventListener('beforeunload', () => {
        if (pollingInterval) clearInterval(pollingInterval);
        if (messagePollingInterval) clearInterval(messagePollingInterval);
        if (timerInterval) clearInterval(timerInterval);
        if (localStream) localStream.getTracks().forEach(track => track.stop());
        if (peerConnection) peerConnection.close();
    });

    // Scroll chat to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Initialize
    init();
})();
</script>
@endpush
@endsection
