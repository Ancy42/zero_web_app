<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Payment Receipt</title>

    <style>
        body {
            font-family: "freeSerif", "kalpurush", serif;
            margin: 0;
            padding: 0;
        }

        p,
        h2,
        h1,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
        }

        .float-left {
            float: left !important;
        }

        .float-right {
            float: right !important;
        }

        .header {
            color: #5E6470;
            padding-top: 12px;
        }

        .header .details {
            text-align: right;
        }

        .header .details p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }

        .header .row {
            width: 50%;
        }

        .header .row {
            width: 50%;
        }

        .header .logo {
            width: 140px;
            object-fit: contain;
        }

        .header img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .pt-2 {
            padding: 5px;
        }

        .pt-1-5 {
            padding-top: 2px;
        }

        .pt-1 {
            padding-top: 4px;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text {
            color: #1e293b;
        }

        .text-black {
            color: #000;
        }

        .receipt-container {
            background-color: #ffffffe4;
            border-radius: 10px;
            padding: 20px;
        }

        .receipt-title {
            background-color: #ffea00;
            padding: 6px;
            border-radius: 5px;
            text-align: right;
            margin-bottom: 12px;
        }

        .receipt-title h1 {
            background-color: #ffffff;
            display: inline-block;
            padding: 8px;
            margin: 0;
            border-radius: 5px;
            font-size: 20px;
            color: #333;
        }

        .section {
            margin-bottom: 12px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
        }

        .section h2 {
            font-size: 16px;
            color: #333;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 9px;
            margin-bottom: 12px;
        }

        .order-summary .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .order-summary .items-table th,
        .order-summary .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .order-summary .items-table th {
            background-color: #e2e8f0;
            font-weight: bold;
            color: #333;
        }

        .order-summary .items-table tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .payment-table {
            border-collapse: collapse;
            width: 100%;
        }

        .payment-table th,
        .payment-table td {
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
        }

        .receipt-footer p {
            font-size: 14px;
            color: #666;
        }

        .order-details {
            border-collapse: collapse;
            width: 100%;
        }

        .order-details td {
            padding: 4px !important;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="header">
            <div class="row float-left">
                <div class="clearfix">
                    <div class="logo">
                        <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}" alt="logo" />
                    </div>
                    <p class="pt-2">{{ config('app.url') }}</p>
                </div>
            </div>

            <div class="pt-4 float-right text-right">
                <p class="fz-14">{{ __('Business Address') }}</p>
                <p class="fz-14 pt-1-5 text-black">{{ __($generaleSetting?->address) }}</p>
                <p class="pt-1-5 text-black">{{ $generaleSetting?->mobile }}</p>
            </div>
        </div>

        <div class="receipt-title" style="margin-top: 6px">
            <h1>{{ __('Payment Receipt') }}</h1>
        </div>

        @php
            $payment = $order->payments()?->latest()->first();
            $transactionId = $payment?->payment_token ?? str_pad($order->id, 6, '0', STR_PAD_LEFT);
            $user = $order->customer?->user;
        @endphp

        <div class="section">
            <h2 class="text">Payment Details</h2>
            <table class="payment-table">
                <tbody>
                    <tr>
                        <td style="width: 50%">
                            <table>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td class="text">{{ $order->payment_method->value }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction ID:</strong></td>
                                    <td class="text">{{ $transactionId }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td class="text">{{ $order->payment_status->value }}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td class="text">
                                        {{ $order->payment_status->value == 'Paid' ? showCurrency($order->payable_amount) : showCurrency(0) }}
                                    </td>
                                </tr>
                                @if ($order->payment_status->value != 'Paid')
                                    <tr>
                                        <td><strong>Due Amount:</strong></td>
                                        <td class="text">
                                            {{ showCurrency($order->payable_amount) }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Customer Details</h2>
            <table class="payment-table">
                <tr>
                    <td>
                        <strong>Name:</strong>
                        <span class="text">{{ $user?->name }}</span>
                    </td>
                    <td>
                        <strong>Email:</strong>
                        <span class="text">{{ $user?->email ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>Phone:</strong>
                        <span class="text">{{ $user?->phone ?? '-' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="order-summary section">
            <h2>Order Summary</h2>
            <table class="order-details">
                <tr>
                    <td>
                        <strong style="color: #6b7280">Order ID:</strong>
                        #{{ $order->prefix . $order->order_code }}
                    </td>
                    <td>
                        <strong style="color: #6b7280">Order Date:</strong>
                        {{ $order->created_at->format('F d, Y') }}
                    </td>
                    <td>
                        <strong style="color: #6b7280">Status:</strong>
                        {{ $order->payment_status->value }}
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->products ?? [] as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ showCurrency($product->pivot->price) }}</td>
                            <td>
                                {{ showCurrency($product->pivot->quantity * $product->pivot->price) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Subtotal</strong></td>
                        <td>{{ showCurrency($order->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Shipping</strong></td>
                        <td>{{ showCurrency($order->delivery_charge) }}</td>
                    </tr>
                    @if ($order->coupon_discount > 0)
                        <tr>
                            <td colspan="3"><strong>Discount</strong></td>
                            <td>{{ showCurrency($order->coupon_discount) }}</td>
                        </tr>
                    @endif
                    @foreach ($order->vatTaxes ?? [] as $vatTax)
                        <tr>
                            <td colspan="3">
                                <strong>{{ $vatTax->name . '(' . $vatTax->percentage . '%)' }}</strong>
                            </td>
                            <td>{{ showCurrency($vatTax->amount) }}</td>
                        </tr>
                    @endforeach
                    @if ($order->tax_amount > 0 && count($order->vatTaxes ?? []) <= 0)
                        <tr>
                            <td colspan="3"><strong>Total Tax Amount</strong></td>
                            <td>{{ showCurrency($order->tax_amount) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3"><strong>Grand Total</strong></td>
                        <td>{{ showCurrency($order->payable_amount) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="receipt-footer">
            <p>
                If you have any questions, contact us at
                <a href="mailto:{{ $generaleSetting?->email }}">{{ $generaleSetting?->email }}</a>
            </p>
        </div>

    </div>
</body>

</html>
