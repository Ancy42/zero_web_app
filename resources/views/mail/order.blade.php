<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Order Confirmation Email</title>

    <!-- Start Common CSS -->
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
            font-family: Helvetica, arial, sans-serif;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        .main-temp table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            font-family: Helvetica, arial, sans-serif;
        }

        .main-temp table td {
            border-collapse: collapse;
        }
    </style>
    <!-- End Common CSS -->
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="backgroundTable main-temp"
        style="background-color: #d5d5d5;">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellpadding="15" cellspacing="0" border="0"
                        class="devicewidth" style="background-color: #ffffff;">
                        <tbody>
                            <!-- Start header Section -->
                            <tr>
                                <td style="padding-top: 30px;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner"
                                        style="border-bottom: 1px solid #eeeeee; text-align: center;">
                                        <tbody>
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    <a href="{{ url('/') }}">
                                                        <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}"
                                                            alt="{{ config('app.name') }}" style="width: 180px;"
                                                            loading="lazy" />
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                    {{ $generaleSetting?->address }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                    Phone: {{ $generaleSetting?->mobile }} | Email:
                                                    {{ $generaleSetting?->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 25px;">
                                                    <strong>Order Number:</strong>
                                                    #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} | <strong>Order
                                                        Date:</strong>
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- End header Section -->

                            <!-- Start address Section -->
                            <tr>
                                <td style="padding-top: 0;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb;">
                                        <tbody>
                                            <tr>
                                                <td
                                                    style="width: 100%; font-size: 16px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                                    Delivery Address
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="width: 100%; font-size: 14px; line-height: 18px; color: #666666;">
                                                    {{ $order->address?->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="width: 100%; font-size: 14px; line-height: 18px; color: #666666;">
                                                    {{ $order->address?->address_line }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="width: 100%; font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px;">
                                                    @if ($order->address?->area)
                                                        {{ $order->address?->area }}
                                                    @endif
                                                    @if ($order->address?->address_line2)
                                                        , {{ $order->address?->address_line2 }}
                                                    @endif
                                                    @if ($order->address?->post_code)
                                                        , {{ $order->address?->post_code }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- End address Section -->

                            <!-- Start product Section -->
                            @foreach ($order->products as $product)
                                <tr>
                                    <td style="padding-top: 0;">
                                        <table width="560" align="center" cellpadding="0" cellspacing="0"
                                            border="0" class="devicewidthinner"
                                            style="border-bottom: 1px solid #eeeeee;">
                                            <tbody>
                                                <tr>
                                                    <td rowspan="4"
                                                        style="padding-right: 10px; padding-bottom: 10px;">
                                                        <img style="height: 80px;" src="{{ $product->thumbnail }}"
                                                            alt="Product Image" />
                                                    </td>
                                                    <td colspan="2"
                                                        style="font-size: 14px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                                        {{ $product->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 14px; line-height: 18px; color: #757575; width: 440px;">
                                                        Quantity: {{ $product->pivot?->quantity }}
                                                    </td>
                                                    <td style="width: 130px;"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #757575;">
                                                        Color: {{ $product->pivot?->color ?? '--' }}
                                                    </td>
                                                    <td
                                                        style="font-size: 14px; line-height: 18px; color: #757575; text-align: right;">
                                                        {{ showCurrency($product->pivot?->price) }} Per {{ $product->unit?->name ?? 'Unit' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 14px; line-height: 18px; color: #757575; padding-bottom: 10px;">
                                                        Size: {{ $product->pivot?->size ?? '--' }}
                                                    </td>
                                                    <td
                                                        style="font-size: 14px; line-height: 18px; color: #757575; text-align: right; padding-bottom: 10px;">
                                                        <b style="color: #666666;">{{ showCurrency($product->pivot?->price * $product->pivot?->quantity) }}</b> Total
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- End product Section -->

                            <!-- Start calculation Section -->
                            <tr>
                                <td style="padding-top: 0;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner"
                                        style="border-bottom: 1px solid #bbbbbb; margin-top: -5px;">
                                        <tbody>
                                            <tr>
                                                <td rowspan="5" style="width: 55%;"></td>
                                                <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                    Sub-Total:
                                                </td>
                                                <td
                                                    style="font-size: 14px; line-height: 18px; color: #666666; width: 130px; text-align: right;">
                                                    {{ showCurrency($order->total_amount) }}
                                                </td>
                                            </tr>

                                            @if ($order->tax_amount)
                                                <tr>
                                                    <td rowspan="5" style="width: 55%;"></td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                        Vat/Tax:
                                                    </td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666; width: 130px; text-align: right;">
                                                        {{ showCurrency($order->tax_amount) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($order->coupon_discount)
                                                <tr>
                                                    <td rowspan="5" style="width: 55%;"></td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                        Discount:
                                                    </td>
                                                    <td
                                                        style="font-size: 14px; line-height: 18px; color: #666666; width: 130px; text-align: right;">
                                                        {{ showCurrency($order->coupon_discount) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td
                                                    style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px; border-bottom: 1px solid #eeeeee;">
                                                    Shipping Fee:
                                                </td>
                                                <td
                                                    style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px; border-bottom: 1px solid #eeeeee; text-align: right;">
                                                    {{ showCurrency($order->delivery_charge) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-top: 10px;">
                                                    Order Total
                                                </td>
                                                <td
                                                    style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-top: 10px; text-align: right;">
                                                    {{ showCurrency($order->payable_amount) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666;">
                                                    Payment Term:
                                                </td>
                                                <td
                                                    style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; text-align: right;">
                                                    100%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-bottom: 10px;">
                                                    Deposit Amount
                                                </td>
                                                <td
                                                    style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; text-align: right; padding-bottom: 10px;">
                                                    @if ($order->payment_status->value == 'Paid')
                                                        {{ showCurrency($order->payable_amount) }}
                                                    @else
                                                        {{ showCurrency(0) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- End calculation Section -->

                            <!-- Start payment method Section -->
                            <tr>
                                <td style="padding: 0 10px;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner">
                                        <tbody>
                                            <tr>
                                                <td colspan="2"
                                                    style="font-size: 16px; font-weight: bold; color: #666666; padding-bottom: 16px;">
                                                    Payment Method ({{ $order->payment_method?->value }})
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"
                                                    style="width: 100%; text-align: center; font-style: italic; font-size: 13px; font-weight: 600; color: #666666; padding: 15px 0; border-top: 1px solid #eeeeee;">
                                                    <b style="font-size: 14px;">Note:</b> {{ $order->instruction ?? '--' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- End payment method Section -->
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
