<?php

namespace App\Providers;

use App\Repository\ConversationRepository;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryInterface;
use App\Repository\StoriesRepository;
use App\Repository\StoriesRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, function() {
            return new MessageRepository();
        });
        $this->app->bind(ConversationRepositoryInterface::class, function() {
            return new ConversationRepository();
        });
        $this->app->bind(StoriesRepositoryInterface::class, function() {
            return new StoriesRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
