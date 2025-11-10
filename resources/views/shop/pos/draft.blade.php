@extends('layouts.app')

@section('header-title', __('Draft'))

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    {{ __('Draft') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    {{ __('Draft Items') }}
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-responsive-lg">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Total Products') }}</th>
                            <th>{{ __('Sub Total') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posCarts as $posCart)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $posCart->created_at->format('d M Y, h:i A') }}
                                    <br>
                                    <small>
                                        {{ $posCart->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    {{ $posCart?->user?->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $posCart->products->count() }}
                                    </span>
                                    {{ __('Items') }}
                                </td>
                                <td>
                                    {{ showCurrency($posCart->subtotal) }}
                                </td>
                                <td>
                                    {{ showCurrency($posCart->discount) }}
                                </td>
                                <td>
                                    {{ showCurrency($posCart->total) }}
                                </td>
                                <td>
                                    <a href="{{ route('shop.pos.index', 'name=' . $posCart->name) }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ __('Edit') }}" class="circleIcon btn-outline-info">
                                        <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="view"
                                            loading="lazy" />
                                    </a>

                                    <a href="{{ route('shop.pos.draft.delete', $posCart->id) }}"
                                        class="circleIcon btn-outline-danger deleteConfirm">
                                        <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="view" loading="lazy" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

@endsection
