@extends('layouts.app')

@section('header-title', __('Subscription Plans'))

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="w-100 page-title-heading d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>{{__('Subscription Plans')}}</div>
                <div class="d-flex gap-2 align-items-center gap-md-4">
                    @hasPermission('admin.subscription-plan.create')
                    <a href="{{ route('admin.subscription-plan.create') }}" class="btn py-2 btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        {{__('Create New Plan')}}
                    </a>
                    @endhasPermission
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="subscription-plan-container">
            @forelse ($subscriptionPlans as $key => $subscriptionPlan)
                <div class="subscription-plan {{ $subscriptionPlan->is_popular ? 'popular' : 'position-relative'}}">
                    @if ($subscriptionPlan->is_popular)
                        <div class="popular-plan position-relative">
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
                        <div style="align-self: stretch; padding-bottom: 32px; padding-left: 24px; padding-right: 24px; justify-content: space-between; align-items: center; gap: 10px; display: flex">
                            <a class="btn btn-info btn-sm text-capitalize w-100" href="{{ route('admin.subscription-plan.edit', $subscriptionPlan->id) }}">
                                Edit
                            </a>
                            <a href="{{ route('admin.subscription-plan.destroy', $subscriptionPlan->id) }}"
                                class="btn btn-danger btn-sm text-capitalize confirmDelete w-100" data-bs-toggle="tooltip">
                                Delete
                            </a>
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

        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('css')
    <style>
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
        $(".confirmDelete").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            const deleteForm = $('#deleteForm');
            deleteForm.attr('action', url);

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this plan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
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
