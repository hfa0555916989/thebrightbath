{{-- Cloudflare Turnstile widget (hidden when TURNSTILE_* keys are not set) --}}
@if(\App\Rules\Turnstile::enabled())
    @once
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endonce
    <div class="flex justify-center">
        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-language="ar" data-theme="light"></div>
    </div>
    @error('cf-turnstile-response')
        <p class="text-red-500 text-sm text-center">{{ $message }}</p>
    @enderror
@endif
