{{-- resources/views/emails/return_status.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật trạng thái yêu cầu trả hàng</title>
</head>
<body>
    <h1>Cập nhật trạng thái yêu cầu trả hàng</h1>

    <p>Xin chào {{ $returnRequest->user->name }},</p>

    <p>Yêu cầu trả hàng của bạn cho đơn hàng #{{ $returnRequest->order_id }} đã được cập nhật:</p>

    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0;">
        <strong>Trạng thái mới:</strong> {{ $returnRequest->getStatusTextAttribute() }}
    </div>

    @if($returnRequest->admin_note)
        <p><strong>Ghi chú từ admin:</strong> {{ $returnRequest->admin_note }}</p>
    @endif

    @if($status === 'refunded' && $returnRequest->refund_amount > 0)
        <p><strong>Số tiền hoàn:</strong> {{ number_format($returnRequest->refund_amount, 0, ',', '.') }} đ</p>
        <p>Số tiền đã được chuyển vào ví của bạn.</p>
    @endif

    @if($returnRequest->tracking_code)
        <p><strong>Mã vận chuyển:</strong> {{ $returnRequest->tracking_code }}</p>
    @endif

    <p>Nếu bạn có câu hỏi, vui lòng liên hệ với chúng tôi.</p>

    <p>Trân trọng,<br>
    Đội ngũ hỗ trợ</p>
</body>
</html>
