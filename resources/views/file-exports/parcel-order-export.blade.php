<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate($data['status']) }} {{ translate('messages.Parcel_Order_List') }}</h1></div>
    <div class="col-lg-12">
    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('order_status' )}} : {{ translate($data['status']) }}
                    @if ($data['search'])
                    <br>
                    {{ translate('search_bar_content' )}} : {{ $data['search'] }}
                    @endif
                    @if ($data['zones'])
                    <br>
                    {{ translate('zones' )}} : {{ $data['zones'] }}
                    @endif

                    @if ($data['type'])
                    <br>
                    {{ translate('order_type' )}} : {{ translate($data['type']) }}
                    @endif
                    @if ($data['from'])
                    <br>
                    {{ translate('from' )}} : {{ $data['from']?Carbon\Carbon::parse($data['from'])->format('d M Y'):'' }}
                    @endif
                    @if ($data['to'])
                    <br>
                    {{ translate('to' )}} : {{ $data['to']?Carbon\Carbon::parse($data['to'])->format('d M Y'):'' }}
                    @endif

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.order_id') }}</th>
                <th>{{ translate('messages.Date') }}</th>
                <th>{{ translate('messages.parcel_category') }}</th>
                <th>{{ translate('messages.customer_name') }}</th>
                <th>{{ translate('messages.coupon_discount') }}</th>
                <th>{{ translate('messages.discounted_amount') }}</th>
                <th>{{ translate('messages.tax') }}</th>
                <th>{{ translate('messages.total_amount') }}</th>
                <th>{{ translate('messages.payment_status') }}</th>
                <th>{{ translate('messages.Payment_By') }}</th>
                <th>{{ translate('messages.Payment_method') }}</th>
                <th>{{ translate('messages.order_status') }}</th>
                <th>{{ translate('messages.order_type') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['orders'] as $key => $order)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $order->id }}</td>

                <td>{{ \App\CentralLogics\Helpers::time_date_format($order->created_at) }}</td>
                <td>
                    <div>{{Str::limit($order->parcel_category?$order->parcel_category->name:translate('messages.not_found'),20,'...')}}</div>
            </td>
                <td>
                    @if ($order->customer)
                        {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                    @else
                        {{ translate('not_found') }}
                    @endif
                </td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount'] + $order['store_discount_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['total_tax_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']) }}</td>
                <td>{{ translate($order->payment_status) }}</td>
                <td>{{ translate($order->charge_payer) }}</td>
                <td>{{ translate($order->payment_method) }}</td>
                <td>{{ translate($order->order_status) }}</td>
                <td>{{ translate($order->order_type) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
