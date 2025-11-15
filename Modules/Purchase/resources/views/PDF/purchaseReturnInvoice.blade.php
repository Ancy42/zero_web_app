<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }}</title>

    <style>
        body {
            box-sizing: border-box;
            font-family: "Roboto", sans-serif;
            font-size: 12px;
            color: #000;
        }
        p, h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .float-left { float: left !important; }
        .main-header {
            width: 100%;
            border-collapse: collapse;
        }
        .main-header .logoSection {
            border-right: 1.5px solid #ddd;
            text-align: center;
            width: 30%;
            padding: 12px;
            border-bottom: 1px solid #DFDFDF;
        }
        .main-header .companyInfo {
            border-right: 1.5px solid #DFDFDF;
            width: 40%;
            padding: 12px;
            border-bottom: 1px solid #DFDFDF;
        }
        .main-header .invoiceInfo {
            text-align: right;
            width: 30%;
            padding: 12px;
            border-bottom: 1px solid #DFDFDF;
        }
        .site-name {
            font-weight: bold;
            font-size: 24px;
            text-transform: uppercase;
        }
        .office-address {
            font-size: 11px;
            padding-top: 12px;
            padding-bottom: 12px;
        }
        .fz-12 { font-size: 12px; }
        .pt-1 { padding-top: 3px; }
        .pt-3 { padding-top: 12px; }
        .sales-invoice {
            text-align: center;
            padding: 6px;
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid #DFDFDF;
            color: #3785B1;
        }
        .customer_title { font-size: 14px; }
        .customer_name {
            font-size: 15px;
            font-weight: bold;
            margin-top: 8px;
        }
        .customer_address { margin-top: 8px; }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .product-table thead tr th {
            border-top: 1px solid #f73d3d;
            border-bottom: 1px solid #000;
            border-left: 1px solid #DFDFDF;
            border-right: 1px solid #DFDFDF;
            padding: 6px;
        }
        .product-table tbody tr td {
            border: 1px solid #DFDFDF;
            padding: 6px;
        }
        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }
        .inner-table tr td {
            border: none;
            padding: 6px;
        }
        .border-top { border-top: 1px solid #DFDFDF; }
        .qrCode {
            width: 140px;
            height: 140px;
        }
        .signature {
            text-align: center;
            width: 100%;
            padding-top: 10px;
            border-top: 1px solid #000 !important;
        }
        .receipt-footer {
            text-align: center;
            border-top: 1px solid #DFDFDF !important;
            padding-top: 8px;
        }
        .terms-list {
            margin-top: 2px;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <table class="main-header">
        <tr>
            <td class="logoSection">
                <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}" alt="{{ __('Logo') }}" width="120">
            </td>
            <td class="companyInfo">
                <h2 class="site-name">{{ __($generaleSetting?->name ?? config('app.name')) }}</h2>
                <div class="office-address">{{ __($generaleSetting?->address ?? '') }}</div>
                <div class="fz-12 pt-1">
                    <strong>{{ __('Mobile') }}:</strong> {{ $generaleSetting?->mobile }}
                </div>
                <div class="fz-12 pt-1">
                    <strong>{{ __('Email') }}:</strong> {{ $generaleSetting?->email }}
                </div>
                <div class="fz-12 pt-1">
                    <strong>{{ __('Web') }}:</strong> {{ config('app.url') }}
                </div>
            </td>
            <td class="invoiceInfo">
                <div><strong>{{ __('Invoice N°') }}:</strong> RET-{{ $purchaseReturn?->return_purchase_code }}</div>
                <div><strong>{{ __('Date') }}:</strong> {{ $purchaseReturn->created_at->format('d M Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="sales-invoice">{{ __('Purchase Return Invoice') }}</div>

    @php $user = $purchaseReturn->purchase->supplier; @endphp

    <div style="width: 100%; padding-top: 12px;">
        <div class="float-left" style="width: 50%;">
            <div class="customer_title"><strong>{{ __('Supplier Info:') }}</strong></div>
            <p class="customer_name">{{ $user?->name }}</p>
            <div class="pt-1"><strong>{{ __('Contact') }}:</strong> {{ $user?->phone }}</div>
            <div class="pt-1"><strong>{{ __('Client ID') }}:</strong> CID{{ str_pad($user?->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div>
            <div class="customer_title"><strong>{{ __('Address') }}</strong></div>
            <div class="pt-1 customer_address">{{ $user?->address ?? '--' }}</div>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th class="text-left">{{ __('N°') }}</th>
                <th class="text-left">{{ __('Description (Code)') }}</th>
                <th class="text-center">{{ __('Price') }}</th>
                <th class="text-center">{{ __('Qty') }}</th>
                <th class="text-center">{{ __('Discount') }}</th>
                <th class="text-center">{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseReturnProducts as $returnProduct)
                @php
                    $product = $returnProduct->product;
                    $barcode = $returnProduct->returnProductSkus->pluck('sku')->implode(', ');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <table class="inner-table">
                            <tr>
                                <td>
                                    <span style="text-transform: capitalize">
                                        {{ $product->name }}
                                        @if ($barcode)
                                            <span style="font-weight: bold">#SKU: ({{ $barcode }})</span>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-center">
                        @if ($returnProduct->quantity > 0)
                            {{ number_format($returnProduct->price / $returnProduct->quantity, 2) }}
                        @else
                            <span class="text-danger">{{ __('N/A') }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $returnProduct->quantity }} {{ __('Piece') }}</td>
                    <td class="text-center">{{ __('--') }}</td>
                    <td class="text-right">{{ $returnProduct->price }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><strong>{{ __('Total') }}</strong></td>
                <td colspan="2" class="text-right"><strong>{{ $purchaseReturn->total_amount }}</strong></td>
            </tr>
        </tbody>
    </table>

    @php
        use Illuminate\Support\Number;
        $spellNumber = Number::spell(round($purchaseReturn->total_amount));
    @endphp

    <div style="margin-top: 8px">
        <strong>{{ __('Amount in words:') }}</strong>
        <span style="text-transform: uppercase">{{ $spellNumber }}</span> {{ __('Taka Only') }}
    </div>

    <p class="pt-1"><strong>{{ __('Terms & Conditions') }}</strong></p>
    <ul class="terms-list">
        <li>{{ __('All prices are exclusive of VAT and applicable taxes.') }}</li>
        <li>{{ __('Goods once sold are not returnable or refundable.') }}</li>
        <li>{{ __('Warranty applies as per manufacturer policy only.') }}</li>
        <li>{{ __('Warranty is void if the product is physically damaged or burned.') }}</li>
    </ul>

    <table style="width: 100%; border-collapse: collapse; margin-top: 8px;">
        <tr>
            <td style="width: 35%; padding: 8px;">
                <div class="signature">{{ __('Customer Signature') }}</div>
            </td>
            <td style="width: 35%; padding: 8px;">
                <div class="signature">{{ __('Authorized Signature') }}</div>
            </td>
        </tr>
    </table>

    <div class="receipt-footer">
        <span>{{ __($generaleSetting?->name ?? config('app.name')) }} © {{ date('Y') }}</span>
    </div>
</body>
</html>
