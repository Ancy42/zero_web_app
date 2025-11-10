@extends('layouts.app')

@section('header-title', __('Subscription Plans'))

@section('content')
    <div class="container-fluid">
        @php
            $current = request()->current_subscription;
            $expired = request()->subscription_expired;
            $sellLimit = $current?->sale_limit;
            $remainingSales = $current?->remaining_sales;
        @endphp
        @if ($current)
            @php
                $daysLeft = 'Unlimited';

                if ($current->ends_at) {
                    $daysLeft = diffInLargestUnit(now(), $current->ends_at);
                }
            @endphp

            <div class="alert alert-info rounded-3 shadow-sm" style="margin-bottom: 48px;">
                <h5 class="mb-2">
                    {{ __('Your Subscription:') }}
                    <strong>{{ $current->plan->name ?? __('(Unknown Plan)') }}</strong>
                </h5>

                @if ($expired)
                    <p class="mb-0 text-danger">
                        {{ __('Your subscription has expired.') }}
                    </p>
                @else
                    <p class="mb-0">
                        {{ __('Time left:') }} <strong>{{ $daysLeft }}</strong>
                        | {{ __('Sales left:') }} <strong>{{ $remainingSales ?? __('Unlimited') }}</strong>
                    </p>
                @endif
            </div>
        @else
            <div style="margin-bottom: 48px;"></div>
        @endif
        <div class="subscription-plan-container">
            @forelse ($subscriptionPlans as $key => $subscriptionPlan)
                @php
                    $isCurrentPlan = $current && $current->plan_id === $subscriptionPlan->id;
                    $buttonLabel = null;

                    if (! $current)
                        $buttonLabel = __('Choose Plan');
                    elseif ($isCurrentPlan)
                        $buttonLabel = __('Renew Plan');
                    else
                        $buttonLabel = __('Change Plan');
                @endphp
                <div class="subscription-plan {{ $subscriptionPlan->is_popular ? 'popular' : 'position-relative'}}">
                    @if ($subscriptionPlan->is_popular)
                        <div class="popular-plan position-relative">
                    @endif
                    @if ($isCurrentPlan)
                        <span class="badge bg-success position-absolute top-0 end-0 m-2 px-3 py-2 rounded-pill">
                            {{ __('Current Plan') }}
                        </span>
                    @endif
                    <div class="w-100">
                        <div style="align-self: stretch; padding-left: 24px; padding-right: 24px; padding-top: 32px; padding-bottom: 12px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 16px; display: flex">
                            <div style="color: white; font-size: 16px; font-family: Inter; font-weight: 600; letter-spacing: 0.32px; word-wrap: break-word">{{ $subscriptionPlan->name }}</div>
                            <div style="align-self: stretch; justify-content: flex-start; align-items: center; gap: 8px; display: inline-flex">
                                <div style="color: white; font-size: 36px; font-family: Inter; font-weight: 800; letter-spacing: 0.72px; word-wrap: break-word">{{ showCurrency($subscriptionPlan->price + 0) }}</div>
                                <div style="flex-direction: column; justify-content: center; align-items: flex-start; gap: 2px; display: inline-flex">
                                    <div style="color: white; font-size: 12px; font-family: Inter; font-weight: 400; word-wrap: break-word">{{ $subscriptionPlan->duration ? daysToLargestUnit($subscriptionPlan->duration) : 'Unlimited' }}</div>
                                </div>
                            </div>
                        </div>
                        @if ($subscriptionPlan->is_popular)
                            <div style="padding-left: 8px; padding-right: 8px; padding-top: 6px; padding-bottom: 6px; right: 24px; top: 24px; position: absolute; background: rgba(255, 255, 255, 0.09); border-radius: 8px; justify-content: center; align-items: center; gap: 4px; display: inline-flex">
                                <div style="text-align: center; color: #FFC107; font-size: 12px; font-family: Inter; font-weight: 500; letter-spacing: 0.24px; word-wrap: break-word">Most Popular</div>
                            </div>
                        @endif
                        <div class="w-100 px-4 d-flex gap-3 justify-content-between text-white">
                            <div class="plan-info-box">
                                <span class="label">Sale limit</span>
                                <span class="value">{{ $subscriptionPlan->sale_limit ?? 'Unlimited' }}</span>
                            </div>
                            <div class="plan-info-box">
                                <span class="label">Duration</span>
                                <span class="value">{{ $subscriptionPlan->duration ? daysToLargestUnit($subscriptionPlan->duration) : 'Unlimited' }}</span>
                            </div>
                        </div>
                        <div class="w-100 p-4 text-white">
                            {!! $subscriptionPlan->description !!}
                        </div>
                    </div>
                    <div class="w-100">
                        <div style="align-self: stretch; padding-bottom: 32px; padding-left: 24px; padding-right: 24px; flex-direction: column; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                            <button type="button"
                                class="btn btn-outline-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#choosePlanModal"
                                data-plan-id="{{ $subscriptionPlan->id }}"
                                data-plan-name="{{ $subscriptionPlan->name }}"
                                data-plan-price="{{ showCurrency($subscriptionPlan->price) }}">
                                {{ $buttonLabel }}
                            </button>
                        </div>
                    </div>
                    @if ($subscriptionPlan->is_popular)
                        </div>
                    @endif
                </div>
            @empty
                <div class="card shadow-sm rounded-12 show-card position-relative overflow-hidden w-100">
                    <div class="card-body shop p-2">
                        <p class="text-center text-muted">{{ __('No subscription plans found') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="my-3">
            {{ $subscriptionPlans->links() }}
        </div>
    </div>

    <!--Choose Plan Modal -->
    <div class="modal fade" id="choosePlanModal" tabindex="-1" aria-labelledby="choosePlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-12">
                <div class="modal-header">
                    <h5 class="modal-title" id="choosePlanModalLabel">Select Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('shop.subscription.purchase') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" id="modalPlanId">

                    <div class="modal-body">
                        <p class="mb-3" id="modalPlanInfo"></p>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                @foreach ($paymentGateways as $gateway)
                                    <option value="{{ $gateway->name }}">{{ $gateway->alias }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Proceed to Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        :root {
            --theme_color: {{ $generaleSetting?->primary_color ?? '#ee456b' }}
        }

        .btn-primary {
            background-color: var(--theme_color) !important;
            border-color: var(--theme_color) !important;
        }

        .btn-outline-primary {
            background-color: #ffffff !important;
            border-color: #ffffff !important;
            color: var(--theme_color) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--theme_color) !important;
            border-color: var(--theme_color) !important;
            color: #fff !important;
        }

        .subscription-plan-container {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            height: 100%;
            justify-content: center;
            row-gap: 72px;
            column-gap: 24px;
            margin: 48px 0;
        }

        .subscription-plan {
            width: 300px;
            min-height: 500px;
            background: #24262D;
            overflow: hidden;
            border-radius: 30px;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            display: inline-flex;
            transform: translateY(24px);
        }

        .subscription-plan.popular {
            padding: 8px;
            border-radius: 32px;
            background: #D7DAE0;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.28);
            transform: translateY(-24px);
        }

        .popular-plan {
            width: 100%;
            height: 100%;
            background: linear-gradient(136deg, #010101 0%, #DD2C5C 100%);
            border-radius: 30px;
            outline: 7px #D7DAE0 solid;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            display: inline-flex
        }

        .plan-info-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 8px 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            min-width: 0;
        }

        .plan-info-box .label {
            font-size: 13px;
            color: #bbbbbb;
            font-weight: 500;
        }

        .plan-info-box .value {
            font-size: 16px;
            font-weight: 600;
            color: white;
            word-break: break-word;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const modal = document.getElementById('choosePlanModal');

        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const planId = button.getAttribute('data-plan-id');
            const planName = button.getAttribute('data-plan-name');
            const planPrice = button.getAttribute('data-plan-price');

            document.getElementById('modalPlanId').value = planId;
            document.getElementById('modalPlanInfo').textContent = `You selected the "${planName}" plan (${planPrice})`;
        });

        fixHeight();

        window.addEventListener('resize', fixHeight);

        function fixHeight() {
            const planElements = document.querySelectorAll('.subscription-plan');

            let maxHeight = 0;

            planElements.forEach(planElement => {
                const planHeight = planElement.getBoundingClientRect().height;
                if (planHeight > maxHeight) {
                    maxHeight = planHeight;
                }
            });

            planElements.forEach(planElement => {
                planElement.style.height = `${maxHeight}px`;
            });
        }
    </script>
@endpush
