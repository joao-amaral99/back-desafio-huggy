<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Mail\WelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public Contact $contact;

    /**
     * Create a new job instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            if (empty($this->contact->email)) {
                Log::warning('Contato sem email', [
                    'contact_id' => $this->contact->id
                ]);
                return;
            }

            Mail::to($this->contact->email)->send(new WelcomeEmail($this->contact));

        } catch (\Throwable $e) {
            Log::error('Erro ao enviar email', [
                'contact_id' => $this->contact->id,
                'error' => $e->getMessage(),
                'exception' => $e
            ]);

            throw $e;
        }
    }
}
