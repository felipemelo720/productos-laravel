<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public string $action,
        public ?string $userName = null,
        public ?int $wcProductId = null,
    ) {}

    public function envelope(): Envelope
    {
        $verb = $this->action === 'updated' ? 'actualizado' : 'creado';

        return new Envelope(
            subject: "[Clandent] Producto {$verb} en WooCommerce: {$this->product->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-sent',
            with: [
                'product'     => $this->product,
                'action'      => $this->action,
                'userName'    => $this->userName,
                'wcProductId' => $this->wcProductId,
                'wcStoreUrl'  => config('services.woocommerce.store_url'),
            ],
        );
    }
}
