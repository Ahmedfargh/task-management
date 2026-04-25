<?php

namespace App\Observers;

use App\Models\Tenant;
use App\Mail\WelcomeTenantMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TenantObserver
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        // For a tenant, we usually need an email address.
        // If we don't have one on the tenant model directly, we check the 'data' field.
        $email = $tenant->data['email'] ?? null;

        if ($email) {
            Mail::to($email)->send(new WelcomeTenantMail($tenant));
        } else {
            Log::warning("Tenant created (ID: {$tenant->id}), but no email found for welcome message.");
        }
    }
}
