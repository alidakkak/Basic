<?php

namespace App\Providers;

use App\Repository\ConversationRepository;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(ConversationRepositoryInterface::class, ConversationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
