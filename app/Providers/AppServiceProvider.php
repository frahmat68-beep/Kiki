<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\OrderNotification;
use App\Models\User;
use App\Observers\UserObserver;
use App\Services\CartService;
use App\Services\OrderReminderService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        View::composer(['partials.navbar', 'layouts.app', 'layouts.user', 'layouts.app-dashboard', 'welcome'], function ($view) {
            $cartCount = app(CartService::class)->totalItems();
            static $navCategoriesCache;
            $notificationCount = 0;
            $notificationItems = collect();

            if ($navCategoriesCache === null) {
                $navCategoriesCache = collect();

                if (Schema::hasTable('categories')) {
                    $navCategoriesCache = Category::query()
                        ->select(['name', 'slug'])
                        ->orderBy('name')
                        ->limit(8)
                        ->get();
                }
            }

            if (Auth::guard('web')->check() && Schema::hasTable('order_notifications')) {
                $webUserId = (int) Auth::guard('web')->id();
                $reminderSyncKey = 'order_reminder_sync:' . $webUserId . ':' . now()->format('YmdH') . floor(now()->minute / 15);
                if (Cache::add($reminderSyncKey, 1, now()->addMinutes(15))) {
                    app(OrderReminderService::class)->dispatchDueReturnReminders($webUserId);
                }

                $notificationItems = OrderNotification::query()
                    ->with('order')
                    ->where('user_id', $webUserId)
                    ->latest()
                    ->limit(8)
                    ->get()
                    ->map(function (OrderNotification $notification) {
                        $targetUrl = route('booking.history');
                        if ($notification->order) {
                            $targetUrl = route('account.orders.show', $notification->order);
                        }

                        return [
                            'id' => (int) $notification->id,
                            'title' => $notification->title,
                            'body' => $notification->message,
                            'url' => $targetUrl,
                            'mark_read_url' => route('notifications.read', $notification),
                            'time' => $notification->created_at?->diffForHumans(),
                            'is_new' => $notification->read_at === null,
                        ];
                    });

                $notificationCount = (int) OrderNotification::query()
                    ->where('user_id', $webUserId)
                    ->whereNull('read_at')
                    ->count();
            }

            $view->with([
                'cartCount' => $cartCount,
                'notificationCount' => $notificationCount,
                'notificationItems' => $notificationItems,
                'navCategories' => $navCategoriesCache,
            ]);
        });
    }
}
