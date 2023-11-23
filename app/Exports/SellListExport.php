<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class SellListExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        return [
            'Id',
            'Customer Name',
            'Email',
            'Phone',
            'District',
            'Area',
            'Address',
            'Courier Name',
            'Delivery Charge',
            'COD',
            'Status',
            'Note',
            'Price',
            'Discount',
            'Advance',
            'Source',
            'Is Returned',
            'Date',
        ];
    } 
    public function collection()
    {
        return DB::table('orders')
                ->orderBy('id', 'DESC')
                ->where('is_final', 1)
                ->where('source', '!=', 'Wholesale')
                ->leftjoin('districts', 'orders.district_id', 'districts.id')
                ->leftjoin('areas', 'orders.area_id', 'areas.id')
                ->leftjoin('order_statuses', 'orders.order_status_id', 'order_statuses.id')
                ->select('orders.id', 'orders.name', 'orders.email', 'orders.phone', 'districts.name as district_name', 'areas.name as area_name', 'orders.shipping_address', 'orders.courier_name', 'orders.delivery_charge', 'orders.cod', 'order_statuses.title', 'orders.note', 'orders.price', 'orders.discount_amount', 'orders.advance', 'orders.source', 'orders.is_return', 'orders.created_at')
                ->get();
    }
}
