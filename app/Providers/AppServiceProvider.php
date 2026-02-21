<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\OrderNotification;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\UserObserver;
use App\Services\CartService;
use App\Services\OrderReminderService;
use Illuminate\Support\Str;
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
        $this->loadRuntimeTranslationOverrides();

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

    private function loadRuntimeTranslationOverrides(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $overrides = Cache::remember('copy.translation_overrides.lines', 600, function () {
            $explicitOverrides = SiteSetting::query()
                ->where('key', 'like', 'copy.trans.%')
                ->whereNotNull('value')
                ->pluck('value', 'key')
                ->all();

            $lines = [];

            foreach ($explicitOverrides as $settingKey => $value) {
                $translationKey = Str::after((string) $settingKey, 'copy.trans.');
                $translationKey = trim($translationKey);
                $translationValue = trim((string) $value);

                if ($translationKey === '' || $translationValue === '' || ! str_contains($translationKey, '.')) {
                    continue;
                }

                $lines[$translationKey] = $translationValue;
            }

            $rawMap = SiteSetting::query()->where('key', 'copy.translation_overrides')->value('value');
            if (is_string($rawMap) && trim($rawMap) !== '') {
                foreach (preg_split('/\r\n|\r|\n/', $rawMap) as $line) {
                    $rawLine = trim((string) $line);
                    if ($rawLine === '' || str_starts_with($rawLine, '#') || str_starts_with($rawLine, '//')) {
                        continue;
                    }

                    $separator = str_contains($rawLine, '=') ? '=' : (str_contains($rawLine, ':') ? ':' : null);
                    if (! $separator) {
                        continue;
                    }

                    [$rawKey, $rawValue] = array_pad(explode($separator, $rawLine, 2), 2, '');
                    $translationKey = trim((string) $rawKey);
                    $translationValue = trim((string) $rawValue);

                    if ($translationKey === '' || $translationValue === '' || ! str_contains($translationKey, '.')) {
                        continue;
                    }

                    $lines[$translationKey] = $translationValue;
                }
            }

            return $lines;
        });

        if (! is_array($overrides) || $overrides === []) {
            return;
        }

        $locales = array_values(array_unique(array_filter([
            app()->getLocale(),
            (string) config('app.locale'),
            (string) config('app.fallback_locale'),
            'id',
            'en',
        ])));

        foreach ($locales as $locale) {
            app('translator')->addLines($overrides, $locale);
        }
    }
}
