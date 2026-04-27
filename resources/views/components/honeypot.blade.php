{{-- Honeypot Field - Invisible to users, bots will fill it --}}
<div style="position: absolute; left: -9999px; top: -9999px;">
    <label for="website_url">Website URL</label>
    <input type="text" name="website_url" id="website_url" value="" autocomplete="off" tabindex="-1">
</div>
<div style="position: absolute; left: -9999px; top: -9999px;">
    <label for="company_name">Company</label>
    <input type="text" name="company_name" id="company_name" value="" autocomplete="off" tabindex="-1">
</div>

{{-- Timestamp to prevent rapid submissions --}}
<input type="hidden" name="_hp_timestamp" value="{{ time() }}">



