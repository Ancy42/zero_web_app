<?php

namespace App\Models\Scopes;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class hasSubscription implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (request()->is('api/*')) {
            $generalSetting = generaleSetting('setting');

            if ($generalSetting?->business_based_on != 'subscription') {
                return;
            }

            $builder->where(function ($builder) {
                $builder->whereHas('shop.user.roles', function ($query) {
                    $query->where('name', 'root'); // allow root user's shop products
                })->orWhereHas('shop.subscriptions', function ($query) {
                    $query->where('status', SubscriptionStatus::ACTIVE)
                        ->where(function ($q) {
                            $q->whereNull('ends_at')
                                ->orWhere('ends_at', '>', now());
                        })
                        ->where(function ($q) {
                            $q->whereNull('remaining_sales')
                                ->orWhere('remaining_sales', '>', 0);
                        });
                });
            });
        }
    }
}
