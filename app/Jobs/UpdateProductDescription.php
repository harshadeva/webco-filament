<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class UpdateProductDescription implements ShouldQueue
{
    use Queueable;

    protected Product $product;
    protected User $user;

    public function __construct(Product $product,User $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    public function handle(): void
    {
        $this->product->update(['description' => $this->product->description . ' [Updated by Job]' . ' at ' . now()]);
        Notification::make()
            ->title('Product Description Updated')
            ->body('The description for product ' . $this->product->name . ' has been updated successfully.')
            ->icon('heroicon-o-check-circle')
            ->sendToDatabase($this->user);
    }
}
