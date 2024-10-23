 <script src="https://cdn.tailwindcss.com"></script>

 <div class="text-center">
     <button onclick="printPage()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
         Print
     </button>
 </div>
 <div class="border">
     <div class="text-center border border-black font-bold text-xl">SALES CONTRACT</div>
     <div class="flex items-center">
         <div class="w-1/2 border border-black text-center">
             S.CONTRACT NP: {{ $sale_contract_number }}
         </div>
         <div class="w-1/2 border border-black text-center">
             DATE: {{ date('d-m-Y') }}
         </div>
     </div>
     <div class="text-center border border-black">
         THIS IRREVOCABLE CONTRACT MADE BETWEEN {{ $settings->full_name ?? 'VERO STYLE LTD.' }}
         {{ $settings->address ?? '' }},
         {{ $order->queryModel->brand->buyer->user->username }} {{ $order->queryModel->brand->address }} UNDER THE
         FOLLOWING TERMS AND CONDITIONS.
     </div>
     <div class="">
         <div class="flex items-stretch">
             <div class="w-1/2 border border-black  pl-1">
                 <u>BUYER:</u> <br>
                 {{ $order->queryModel->brand->buyer->user->username }}<br>
                 {{ $order->queryModel->brand->address }}<br>
                 {{ $order->queryModel->brand->phone }}
             </div>
             <div class="w-1/2  border border-black  pl-1">
                 <u class="font-bold">BUYING HOUSE:</u><br>
                 {{ $settings->full_name ?? 'VERO STYLE LTD.' }}<br>
                 {{ $settings->address ?? '' }}<br>
                 {{ $settings->phone ?? '' }}
             </div>
         </div>
     </div>
     <div class="">
         <div class="flex items-stretch">
             <div class="w-1/2 border border-black  pl-1 font-semibold">BANK INFO <br>
                 @foreach ($order->queryModel->brand->banks as $index => $bank)
                     @if ($index == 0)
                         BANK NAME: {{ $bank->name }}<br>
                         ACCOUNT NO: {{ $bank->account_no }}<br>
                         BRANCH: {{ $bank->branch }}<br>
                         SWIFT CODE: {{ $bank->swift_code }}
                     @endif
                 @endforeach
             </div>

             <div class="w-1/2 border border-black pl-1">
                 <u class="font-bold">BUYING HOUSE BANK INFORMATION</u><br>
                 <textarea class="w-full h-32 border border-black p-2">BUYING HOUSE: VERO STYLE LTD<br>BANK: BRAC BANK PLC.<br>BRANCH: MIRPUR-1 BRANCH, DHAKA<br>SWIFT CODE: BRAKBDDH<br>Bank Account: 1538204833322001</textarea>
             </div>
         </div>
     </div>
 </div>
 <table class="table-auto border border-black w-full">
     <thead>
         <tr>
             <th class="border border-black">SL</th>
             <th class="border border-black">ART NO</th>
             <th class="border border-black">ITEM</th>
             <th class="border border-black">FABRICATION</th>
             <th class="border border-black">HS CODE</th>
             <th class="border border-black">PRICE/PC</th>
             <th class="border border-black">QTY</th>
             <th class="border border-black">US $ AMOUNT</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($order->items as $index => $item)
             @php
                 $colors = json_decode($item->colors);
                 $sizes = json_decode($item->sizes);
             @endphp
             <tr>
                 <td class="border border-black text-center">{{ $index + 1 }}</td>
                 <td class="border border-black text-center">{{ $item->style_no }}</td>
                 <td class="border border-black text-center">{{ $item->item }}</td>
                 <td class="border border-black text-center">{{ $item->fabrication }}</td>
                 <td class="border border-black text-center">{{ $item->hs_code }}</td>
                 <td class="border border-black text-center">$ {{ $item->final_cost }}</td>
                 <td class="border border-black text-center">{{ $item->pieces }} PCS</td>
                 <td class="border border-black text-center">$ {{ $item->pieces * $item->final_cost }}</td>
             </tr>
         @endforeach
     </tbody>
     <tfoot>
         <tr class="border border-black">
             <td class="border border-black text-center"></td>
             <td colspan="5" class="text-right font-semibold">TOTAL QUANTITY & FOB VALUE in US $</td>
             <td class="text-center border border-black">{{ $order->total_quantity }} PCS</td>
             <td class="text-center border border-black">$ {{ $order->total_amount }}</td>
         </tr>
         <tr class="border border-black">
             <td colspan="8 " class="h-6">
             </td>
         </tr>

     </tfoot>
 </table>
 <div class="border border-black p-1">
     <textarea class="w-full h-64  p-2">MODE OF PAYMENT: 30% ADVANCE TT AND 70% TT After Shipment Based on Copy Document. <br> TOLERANCE ACCEPTED: Value & quantity more or less 5% <br> PARTIAL SHIPMENT AND TRANSHIPMENT: Allowed <br> SHIPMENT DATE: 30 NOV 2024 & DATE OF EXPIRY: 20 DEC 2024 <br> TERMS OF DELIVERY: FOB Chittagong <br> MODE OF TRANSPORT: By SEA <br>DESTINATION COUNTRY: ITALY <br>INSPECTION CERTIFICATE ISSUING AUTHORITY: VERO STYLE LIMITED, Dhaka, Bangladesh <br>IF THE BUYER DOES NOT TAKE THE DELIVERY OF THE GOODS FOR ANY REASON, VERO STYLE MAY SELL THE GOODS ELSEWHERE. <br>INSURANCE COVERED BY BUYER(TANG SAIXUAN) <br>This sales contract is subject to URC-522 <br> Third party documents acceptable except invoice and draft <br> Bill of Lading made out to the order of Negotiating Bank in Bangladesh and endorsed to the order of Drawee's Bank and/or Drawee if payment received in full. <br> DOCUMENTS REQUIRED: Invoice, Packing List, HBL/HAWB & MBL/MAWB</textarea>
     <div class="flex items-center mt-20">
         <div class="w-1/2 text-center">
             BUYER SIGNATURE
         </div>
         <div class="w-1/2 text-center">
             BUYING HOUSE SIGNATURE
         </div>
     </div>

 </div>
 <script>
     function printPage() {
         //turn the button off and the textareas to normal divs
         document.querySelector('button').style.display = 'none';
         document.querySelectorAll('textarea').forEach(textarea => {
             const div = document.createElement('div');
             div.innerHTML = textarea.value;
             textarea.replaceWith(div);
         });
         window.print();
     }
 </script>
