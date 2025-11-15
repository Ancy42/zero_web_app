 <div class="card-body">
     <div class="row">
         <div class="col-lg-8">
             <div class="row">
                 <div class="col-md-6 mb-3">
                     <label class="form-label"><strong>{{ __('Name or Title') }}:</strong></label>
                     <p>{{ $purchase->name }}</p>
                 </div>

                 <div class="col-md-6 mb-3">
                     <label class="form-label"><strong>{{ __('Received Date') }}:</strong></label>
                     <p>{{ \Carbon\Carbon::parse($purchase->receive_date)->format('m/d/Y') }}</p>
                 </div>

                 <div class="col-md-6 mb-3">
                     <label class="form-label"><strong>{{ __('Total Amount') }}:</strong></label>
                     <p>{{ showCurrency($purchase->total_amount) }}</p>
                 </div>

                 <div class="col-md-6 mb-3">
                     <label class="form-label"><strong>{{ __('Supplier') }}:</strong></label>
                     <p>{{ $purchase?->supplier?->name }}</p>
                 </div>
                 <div class="col-md-6 mb-3">
                     <label class="form-label"><strong>{{ __('Invoice No') }}:</strong></label>
                     <p>{{ $purchase?->purchase_code ?? '-' }}</p>
                 </div>

                 <div class="col-md-6 mb-3">
                     <label class="form-label"><strong>{{ __('Notes') }}:</strong></label>
                     <p>{{ $purchase->note }}</p>
                 </div>
             </div>
         </div>

         <div class="col-lg-4">
             <h5 class="mb-3">{{ __('Slip Image') }}</h5>
             <a href="{{ $purchase->thumbnail }}" target="_blank" class="attachmentLink">
                 <img src="{{ $purchase->thumbnail }}" alt="slip" class="img-fluid">
             </a>
         </div>
     </div>
     <form action="{{ route('shop.purchaseReturn.store', $purchase->id) }}" method="POST">
         @csrf
         <div class="table-responsive">
             <table class="table border table-responsive-lg">
                 <thead>
                     <tr class="text-center">
                         <th class="text-center">{{ __('SL') }}</th>
                         <th>{{ __('Item Name') }}</th>
                         <th>{{ __('QTY') }}</th>
                         <th>{{ __('Price') }}</th>
                         <th>{{ __('Return Qty') }}</th>
                         <th>{{ __('Add Qty') }}</th>
                         <th>{{ __('Sub Total') }}</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($purchaseProducts as $purchaseProduct)
                         @php
                             $stockOut = $purchaseProduct->productSkus->where('in_stock', false)->count();
                             $qty = $purchaseProduct->quantity - $stockOut;
                             $price =
                                 $purchaseProduct->quantity > 0
                                     ? $purchaseProduct->price / $purchaseProduct->quantity
                                     : 0;
                         @endphp
                         <tr class="text-center dt-row">
                             <td>{{ $loop->iteration }}</td>
                             <td>{{ $purchaseProduct?->product?->name }}</td>
                             <td>{{ $qty }}</td>
                             <td>{{ $purchaseProduct->price }}</td>
                             <td>{{ $purchaseProduct->return_quantity }}</td>
                             <td><input type="text" name="return_quantity[{{ $purchaseProduct->id }}]"
                                     class="form-control return_quantity w-20 display-inline" data-purchaseid="{{ $purchaseProduct->id }}"
                                     oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                     data-purproqty="{{ $qty }}" data-price="{{ $price }}"
                                    ></td>
                             <td class="subtotal">0.00</td>
                         </tr>
                     @endforeach
                 </tbody>
                 <tfoot>
                     <tr class="text-center">
                         <td colspan="5"></td>
                         <td class="text-right"><strong>{{ __('Grand Total') }}:</strong></td>
                         <td class="grandtotal">0.00</td>
                     </tr>
                 </tfoot>
             </table>
             <table class="table border table-responsive-lg no-row-animation">
                 <thead>
                     <tr class="text-center">
                         <th class="text-center">{{ __('SL') }}</th>
                         <th>{{ __('Item Name') }}</th>
                         <th>{{ __('barcode') }}</th>
                         <th></th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($purchaseProducts as $purchaseProduct)
                         @php
                             $product = $purchaseProduct->product;
                             $barcode = $purchaseProduct->productSkus->pluck('sku')->implode(', ');
                             $price =
                                 $purchaseProduct->quantity > 0
                                     ? $purchaseProduct->price / $purchaseProduct->quantity
                                     : 0;
                         @endphp
                         @foreach ($purchaseProduct->productSkus->where('in_stock', true) as $productSku)
                             <tr class="text-center dt-row">
                                 <td>{{ $loop->iteration }}</td>
                                 <td>{{ $product->name }}</td>
                                 <td>{{ $productSku->sku }}</td>
                                 <td><input type="checkbox" name="return_sku[]" class="return_sku"
                                         value="{{ $productSku->sku }}" data-price="{{ $productSku->price }}"
                                         data-purchaseid="{{ $purchaseProduct->id }}"></td>
                             </tr>
                         @endforeach
                     @endforeach
                 </tbody>
             </table>

             <div class="d-flex justify-content-end mt-3">
                 <button type="submit" class="btn btn-danger">{{ __('Return') }}</button>
             </div>
         </div>
     </form>
 </div>
@push('css')
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush
