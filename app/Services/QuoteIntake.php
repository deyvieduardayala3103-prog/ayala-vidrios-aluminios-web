<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuoteIntake
{
    public function create(array $data, array $photos = []): Quote
    {
        $paths = [];
        try {
            return DB::transaction(function () use ($data, $photos, &$paths) {
                $customer = Customer::create(collect($data)->only(['name', 'phone', 'email'])->all());
                $quote = Quote::create(['reference' => (string) Str::uuid(), 'customer_id' => $customer->id, 'specialty_id' => $data['specialty_id'], 'quote_status_id' => QuoteStatus::where('name', 'Pendiente')->firstOrFail()->id, 'description' => $data['description'], 'zone' => $data['zone'] ?? null, 'consented_at' => now()]);
                $quote->items()->create(collect($data)->only(['work_type', 'width', 'height', 'unit', 'quantity'])->all());
                $quote->histories()->create(['quote_status_id' => $quote->quote_status_id, 'note' => 'Solicitud recibida desde el sitio web.']);
                foreach ($photos as $photo) {
                    $path = $photo->store('quote-attachments', 'local');
                    $paths[] = $path;
                    $quote->attachments()->create(['path' => $path, 'original_name' => mb_substr($photo->getClientOriginalName(), 0, 200)]);
                }

                return $quote;
            });
        } catch (\Throwable $e) {
            foreach ($paths as $path) {
                Storage::disk('local')->delete($path);
            }throw $e;
        }
    }
}
