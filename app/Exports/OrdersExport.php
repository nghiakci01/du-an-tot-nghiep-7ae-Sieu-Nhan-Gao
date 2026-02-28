<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Mã Đơn Hàng',
            'Khách Hàng',
            'Email',
            'Số Điện Thoại',
            'Tổng Tiền (VND)',
            'Giảm Giá (VND)',
            'Phí Ship (VND)',
            'Tổng Thanh Toán (VND)',
            'Trạng Thái',
            'PT Thanh Toán',
            'Ngày Đặt',
            'Địa Chỉ Giao Hàng'
        ];
    }

    public function map($order): array
    {
        return [
            '#' . $order->id,
            $order->user ? $order->user->name : $order->name,
            $order->email,
            $order->phone,
            $order->total_price,
            $order->discount_amount,
            $order->shipping_fee,
            $order->final_total,
            $order->status_text,
            $order->payment_method,
            $order->created_at->format('d/m/Y H:i'),
            $order->address . ', ' . $order->province
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F81BD']]],
        ];
    }
}
