<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
}
