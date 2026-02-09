@extends('layouts.app')

@section('title', 'Communication Settings')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'email' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Communication Settings</h1>
            <p class="text-gray-600 mt-1">Configure email and SMS communication settings</p>
        </div>
        <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Settings
        </a>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto">
            <button @click="activeTab = 'email'" 
                :class="activeTab === 'email' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Email Configuration
            </button>
            <button @click="activeTab = 'sms'" 
                :class="activeTab === 'sms' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                SMS Configuration
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="mt-6">
        <!-- Email Configuration Tab -->
        <div x-show="activeTab === 'email'" x-transition>
            <form action="{{ route('settings.communication.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="email">
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Email Configuration</h3>
                            <p class="text-sm text-gray-600 mt-1">Configure SMTP settings for sending emails</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_enabled" value="1" {{ ($settings['email_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Enable Email</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mail Driver -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                            <select name="mail_mailer" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                <option value="mailgun" {{ ($settings['mail_mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ ($settings['mail_mailer'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                <option value="postmark" {{ ($settings['mail_mailer'] ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                <option value="resend" {{ ($settings['mail_mailer'] ?? '') == 'resend' ? 'selected' : '' }}>Resend</option>
                                <option value="log" {{ ($settings['mail_mailer'] ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Select the mail service provider</p>
                        </div>

                        <!-- From Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Email Address</label>
                            <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? config('mail.from.address') }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="noreply@example.com">
                            <p class="mt-1 text-xs text-gray-500">Default sender email address</p>
                        </div>

                        <!-- From Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                            <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('mail.from.name') }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="ShopSmart">
                            <p class="mt-1 text-xs text-gray-500">Default sender name</p>
                        </div>

                        <!-- SMTP Host -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? config('mail.mailers.smtp.host') }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="smtp.mailtrap.io">
                            <p class="mt-1 text-xs text-gray-500">SMTP server hostname</p>
                        </div>

                        <!-- SMTP Port -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? config('mail.mailers.smtp.port') }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="2525">
                            <p class="mt-1 text-xs text-gray-500">SMTP server port (usually 587, 465, or 2525)</p>
                        </div>

                        <!-- Encryption -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                            <select name="mail_encryption" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Connection encryption type</p>
                        </div>

                        <!-- SMTP Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                            <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="your-username">
                            <p class="mt-1 text-xs text-gray-500">SMTP authentication username</p>
                        </div>

                        <!-- SMTP Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                            <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="••••••••">
                            <p class="mt-1 text-xs text-gray-500">SMTP authentication password</p>
                        </div>
                    </div>

                    <!-- Test Email Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Test Email Configuration</h4>
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                                <input type="email" id="test_email" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="test@example.com">
                            </div>
                            <button type="button" onclick="testEmail()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Test Email
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Save Email Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- SMS Configuration Tab -->
        <div x-show="activeTab === 'sms'" x-transition>
            <form action="{{ route('settings.communication.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="sms">
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">SMS Configuration</h3>
                            <p class="text-sm text-gray-600 mt-1">Configure SMS gateway settings for sending text messages</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sms_enabled" value="1" {{ ($settings['sms_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Enable SMS</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SMS Provider -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS Provider</label>
                            <select name="sms_provider" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="twilio" {{ ($settings['sms_provider'] ?? 'twilio') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="nexmo" {{ ($settings['sms_provider'] ?? '') == 'nexmo' ? 'selected' : '' }}>Vonage (Nexmo)</option>
                                <option value="aws_sns" {{ ($settings['sms_provider'] ?? '') == 'aws_sns' ? 'selected' : '' }}>AWS SNS</option>
                                <option value="messagebird" {{ ($settings['sms_provider'] ?? '') == 'messagebird' ? 'selected' : '' }}>MessageBird</option>
                                <option value="plivo" {{ ($settings['sms_provider'] ?? '') == 'plivo' ? 'selected' : '' }}>Plivo</option>
                                <option value="custom" {{ ($settings['sms_provider'] ?? '') == 'custom' ? 'selected' : '' }}>Custom API</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Select your SMS service provider</p>
                        </div>

                        <!-- API Key / Account SID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Key / Account SID</label>
                            <input type="text" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                            <p class="mt-1 text-xs text-gray-500">Your SMS provider API key or Account SID</p>
                        </div>

                        <!-- API Secret / Auth Token -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Secret / Auth Token</label>
                            <input type="password" name="sms_api_secret" value="{{ $settings['sms_api_secret'] ?? '' }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="••••••••">
                            <p class="mt-1 text-xs text-gray-500">Your SMS provider API secret or Auth Token</p>
                        </div>

                        <!-- From Number / Sender ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Number / Sender ID</label>
                            <input type="text" name="sms_from" value="{{ $settings['sms_from'] ?? '' }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="+1234567890 or SenderID">
                            <p class="mt-1 text-xs text-gray-500">Phone number or sender ID to send from</p>
                        </div>

                        <!-- API URL (for custom) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">API URL (Custom Provider Only)</label>
                            <input type="url" name="sms_api_url" value="{{ $settings['sms_api_url'] ?? '' }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="https://api.example.com/send">
                            <p class="mt-1 text-xs text-gray-500">API endpoint URL for custom SMS providers</p>
                        </div>
                    </div>

                    <!-- Provider-specific Settings -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Provider Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Region (for some providers) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Region (Optional)</label>
                                <input type="text" name="sms_region" value="{{ $settings['sms_region'] ?? '' }}" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="us-east-1">
                                <p class="mt-1 text-xs text-gray-500">Region for AWS SNS or other regional services</p>
                            </div>

                            <!-- Default Country Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Country Code</label>
                                <input type="text" name="sms_country_code" value="{{ $settings['sms_country_code'] ?? '+1' }}" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="+1">
                                <p class="mt-1 text-xs text-gray-500">Default country code for phone numbers</p>
                            </div>
                        </div>
                    </div>

                    <!-- Test SMS Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Test SMS Configuration</h4>
                        <div class="space-y-4">
                            <div class="flex items-end space-x-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Test Phone Number</label>
                                    <input type="tel" id="test_phone" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                        placeholder="+1234567890">
                                </div>
                                <button type="button" onclick="testSMS()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Send Test SMS
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Save SMS Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function testEmail() {
        const email = document.getElementById('test_email').value;
        if (!email) {
            alert('Please enter a test email address');
            return;
        }

        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';

        fetch('{{ route("settings.communication.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test email sent successfully!');
            } else {
                alert('Error sending test email: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error sending test email: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }

    function testSMS() {
        const phone = document.getElementById('test_phone').value;
        if (!phone) {
            alert('Please enter a test phone number');
            return;
        }

        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';

        fetch('{{ route("settings.communication.test-sms") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test SMS sent successfully!');
            } else {
                alert('Error sending test SMS: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error sending test SMS: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
</script>
@endpush
@endsection

