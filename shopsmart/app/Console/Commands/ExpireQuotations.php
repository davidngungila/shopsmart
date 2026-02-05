<?php

namespace App\Console\Commands;

use App\Models\Quotation;
use Illuminate\Console\Command;

class ExpireQuotations extends Command
{
    protected $signature = 'quotations:expire';
    protected $description = 'Mark expired quotations as expired';

    public function handle()
    {
        $expired = Quotation::where('status', '!=', 'converted')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

        $this->info("Marked {$expired} quotations as expired.");
        return 0;
    }
}
