<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function general()
    {
        $settings = Setting::getGroup('general');
        return view('settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string',
            'company_logo' => 'nullable|image|max:2048',
            'tax_id' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'company_logo' && $request->hasFile('company_logo')) {
                $path = $request->file('company_logo')->store('settings', 'public');
                Setting::set($key, $path, 'general', 'file');
            } elseif ($key !== 'company_logo') {
                Setting::set($key, $value, 'general');
            }
        }

        return back()->with('success', 'General settings updated successfully.');
    }

    public function users()
    {
        $users = User::with('employee')->latest()->paginate(20);
        return view('settings.users', compact('users'));
    }

    public function roles()
    {
        return view('settings.roles');
    }

    public function system()
    {
        $settings = Setting::getGroup('system');
        return view('settings.system', compact('settings'));
    }

    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'enable_pos' => 'boolean',
            'enable_quotations' => 'boolean',
            'enable_purchases' => 'boolean',
            'enable_notifications' => 'boolean',
            'enable_sms' => 'boolean',
            'enable_email' => 'boolean',
            'auto_backup' => 'boolean',
            'backup_frequency' => 'nullable|in:daily,weekly,monthly',
            'theme' => 'nullable|in:light,dark',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ? '1' : '0', 'system', 'boolean');
        }

        return back()->with('success', 'System settings updated successfully.');
    }

    public function financial()
    {
        $settings = Setting::getGroup('financial');
        return view('settings.financial', compact('settings'));
    }

    public function updateFinancial(Request $request)
    {
        $validated = $request->validate([
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
            'default_discount_type' => 'nullable|in:percentage,fixed',
            'enable_payment_cash' => 'boolean',
            'enable_payment_card' => 'boolean',
            'enable_payment_mobile_money' => 'boolean',
            'enable_payment_bank' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, 'financial', is_bool($value) ? 'boolean' : 'text');
        }

        return back()->with('success', 'Financial settings updated successfully.');
    }

    public function inventory()
    {
        $settings = Setting::getGroup('inventory');
        return view('settings.inventory', compact('settings'));
    }

    public function updateInventory(Request $request)
    {
        $validated = $request->validate([
            'default_low_stock_alert' => 'nullable|integer|min:0',
            'default_unit' => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'inventory');
        }

        return back()->with('success', 'Inventory settings updated successfully.');
    }

    public function quotations()
    {
        $settings = Setting::getGroup('quotations');
        return view('settings.quotations', compact('settings'));
    }

    public function updateQuotations(Request $request)
    {
        $validated = $request->validate([
            'default_quotation_expiry_days' => 'nullable|integer|min:1',
            'default_terms_conditions' => 'nullable|string',
            'quotation_number_prefix' => 'nullable|string|max:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'quotations');
        }

        return back()->with('success', 'Quotation settings updated successfully.');
    }

    public function notifications()
    {
        $settings = Setting::getGroup('notifications');
        return view('settings.notifications', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'low_stock_alert' => 'boolean',
            'quotation_reminder' => 'boolean',
            'invoice_overdue_alert' => 'boolean',
            'payment_received_alert' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ? '1' : '0', 'notifications', 'boolean');
        }

        return back()->with('success', 'Notification settings updated successfully.');
    }

    public function backup()
    {
        return view('settings.backup');
    }

    public function createBackup(Request $request)
    {
        try {
            $backupType = $request->input('backup_type', 'database');
            $compression = $request->input('compression', 'gzip');
            
            $filename = 'backup_' . date('Y-m-d_His') . '_' . $backupType;
            
            if ($backupType === 'database' || $backupType === 'full') {
                // Database backup using mysqldump
                $database = config('database.connections.mysql.database');
                $username = config('database.connections.mysql.username');
                $password = config('database.connections.mysql.password');
                $host = config('database.connections.mysql.host');
                
                $backupPath = storage_path('app/backups');
                if (!File::exists($backupPath)) {
                    File::makeDirectory($backupPath, 0755, true);
                }
                
                $sqlFile = $backupPath . '/' . $filename . '.sql';
                $command = sprintf(
                    'mysqldump -h %s -u %s -p%s %s > %s',
                    escapeshellarg($host),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database),
                    escapeshellarg($sqlFile)
                );
                
                exec($command, $output, $returnVar);
                
                if ($returnVar !== 0) {
                    throw new \Exception('Database backup failed');
                }
                
                if ($compression === 'gzip') {
                    $compressedFile = $sqlFile . '.gz';
                    exec("gzip $sqlFile");
                    $filename .= '.sql.gz';
                } else {
                    $filename .= '.sql';
                }
            }
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'filename' => $filename
                ]);
            }
            
            return back()->with('success', 'Backup created successfully: ' . $filename);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup failed: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function updateAutomation(Request $request)
    {
        $validated = $request->validate([
            'auto_backup' => 'boolean',
            'backup_frequency' => 'nullable|in:daily,weekly,monthly',
            'backup_time' => 'nullable|string',
            'retention_days' => 'nullable|integer|min:1|max:365',
            'auto_backup_type' => 'nullable|in:database,full',
            'backup_email_notifications' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, 'backup', is_bool($value) ? 'boolean' : 'text');
        }

        return back()->with('success', 'Automated backup settings updated successfully.');
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            return back()->with('success', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function clearViews()
    {
        try {
            Artisan::call('view:clear');
            return back()->with('success', 'Compiled views cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear views: ' . $e->getMessage());
        }
    }

    public function clearRoutes()
    {
        try {
            Artisan::call('route:clear');
            return back()->with('success', 'Route cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear routes: ' . $e->getMessage());
        }
    }

    public function clearConfig()
    {
        try {
            Artisan::call('config:clear');
            return back()->with('success', 'Configuration cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear config: ' . $e->getMessage());
        }
    }

    public function optimizeDb()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $database = DB::getDatabaseName();
            $tableKey = 'Tables_in_' . $database;
            
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                DB::statement("OPTIMIZE TABLE `{$tableName}`");
            }
            
            return back()->with('success', 'Database optimized successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to optimize database: ' . $e->getMessage());
        }
    }

    public function clearAll()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            return back()->with('success', 'All caches cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear caches: ' . $e->getMessage());
        }
    }

    public function communication()
    {
        $settings = Setting::getGroup('communication');
        return view('settings.communication', compact('settings'));
    }

    public function updateCommunication(Request $request)
    {
        $type = $request->input('type');
        
        if ($type === 'email') {
            $validated = $request->validate([
                'email_enabled' => 'boolean',
                'mail_mailer' => 'nullable|in:smtp,sendmail,mailgun,ses,postmark,resend,log,array',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|integer|min:1|max:65535',
                'mail_encryption' => 'nullable|in:tls,ssl,',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:255',
            ]);

            foreach ($validated as $key => $value) {
                if ($key === 'mail_password' && empty($value)) {
                    continue; // Don't update password if empty
                }
                Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, 'communication', is_bool($value) ? 'boolean' : 'text');
            }

            // Update .env file or config cache
            $this->updateMailConfig($validated);

            return back()->with('success', 'Email settings updated successfully.');
        } elseif ($type === 'sms') {
            $validated = $request->validate([
                'sms_enabled' => 'boolean',
                'sms_provider' => 'nullable|in:twilio,nexmo,aws_sns,messagebird,plivo,custom',
                'sms_api_key' => 'nullable|string|max:255',
                'sms_api_secret' => 'nullable|string|max:255',
                'sms_from' => 'nullable|string|max:50',
                'sms_api_url' => 'nullable|url|max:255',
                'sms_region' => 'nullable|string|max:50',
                'sms_country_code' => 'nullable|string|max:10',
            ]);

            foreach ($validated as $key => $value) {
                if ($key === 'sms_api_secret' && empty($value)) {
                    continue; // Don't update secret if empty
                }
                Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, 'communication', is_bool($value) ? 'boolean' : 'text');
            }

            return back()->with('success', 'SMS settings updated successfully.');
        }

        return back()->with('error', 'Invalid configuration type.');
    }

    protected function updateMailConfig(array $settings)
    {
        // Note: In production, you might want to update .env file directly
        // For now, we'll just store in settings and use them when sending emails
        // You can extend this to update .env or use config caching
    }

    public function testEmail(Request $request)
    {
        try {
            $email = $request->input('email');
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address is required'
                ], 400);
            }

            // Get email settings
            $settings = Setting::getGroup('communication');
            
            // Use settings or fallback to config
            $fromAddress = $settings['mail_from_address'] ?? config('mail.from.address');
            $fromName = $settings['mail_from_name'] ?? config('mail.from.name');

            Mail::raw('This is a test email from ShopSmart. Your email configuration is working correctly!', function ($message) use ($email, $fromAddress, $fromName) {
                $message->to($email)
                        ->subject('ShopSmart - Test Email')
                        ->from($fromAddress, $fromName);
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testSMS(Request $request)
    {
        try {
            $phone = $request->input('phone');
            
            if (!$phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number is required'
                ], 400);
            }

            // Get SMS settings
            $settings = Setting::getGroup('communication');
            
            if (($settings['sms_enabled'] ?? '0') != '1') {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS is not enabled'
                ], 400);
            }

            $provider = $settings['sms_provider'] ?? 'twilio';
            $apiKey = $settings['sms_api_key'] ?? '';
            $apiSecret = $settings['sms_api_secret'] ?? '';
            $from = $settings['sms_from'] ?? '';

            if (empty($apiKey) || empty($apiSecret)) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS API credentials are not configured'
                ], 400);
            }

            // Here you would implement actual SMS sending logic based on provider
            // For now, we'll just log it
            Log::info('Test SMS would be sent', [
                'provider' => $provider,
                'to' => $phone,
                'from' => $from
            ]);

            // TODO: Implement actual SMS sending based on provider
            // Example for Twilio:
            // $client = new \Twilio\Rest\Client($apiKey, $apiSecret);
            // $client->messages->create($phone, ['from' => $from, 'body' => 'Test SMS from ShopSmart']);

            return response()->json([
                'success' => true,
                'message' => 'Test SMS sent successfully (logged)'
            ]);
        } catch (\Exception $e) {
            Log::error('Test SMS failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test SMS: ' . $e->getMessage()
            ], 500);
        }
    }
}
