@extends('layouts.admin')

@section('title', 'Sửa Tài Khoản Ngân Hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa Tài Khoản</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bank-settings.update', $bank->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bank_select" class="form-label">Chọn Ngân Hàng <span class="text-danger">*</span></label>
                                <select id="bank_select" class="form-select select2" required>
                                    <option value="">-- Đang tải danh sách ngân hàng --</option>
                                </select>
                                <input type="hidden" name="bank_name" id="bank_name" value="{{ old('bank_name', $bank->bank_name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_id" class="form-label">Mã NH (VietQR Shortcode) <span class="text-danger">*</span></label>
                                <input type="text" name="bank_id" id="bank_id" class="form-control" value="{{ old('bank_id', $bank->bank_id) }}" readonly required>
                                <small class="text-muted">Mã này được tự động điền khi chọn ngân hàng.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label">Số Tài Khoản <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number', $bank->account_number) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_name" class="form-label">Tên Chủ Tài Khoản <span class="text-danger">*</span></label>
                                <input type="text" name="account_name" id="account_name" class="form-control" value="{{ old('account_name', $bank->account_name) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ $bank->is_default ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">Đặt làm Mặc định (Sẽ ưu tiên load ở thanh toán)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $bank->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Hoạt động (Trạng thái hiển thị)</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.bank-settings.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let banksData = [];

        // Fetch danh sách ngân hàng từ VietQR
        $.ajax({
            url: 'https://api.vietqr.io/v2/banks',
            method: 'GET',
            success: function(response) {
                if (response.code == '00') {
                    banksData = response.data;
                    let options = '<option value="">-- Chọn Ngân Hàng --</option>';
                    
                    // Lấy giá trị old (nếu có)
                    const oldBankName = $('#bank_name').val();
                    const oldBankId = $('#bank_id').val();
                    let selectedBin = '';

                    banksData.forEach(function(bank) {
                        let isSelected = (oldBankName == bank.name || oldBankId == bank.shortName) ? 'selected' : '';
                        if (isSelected) selectedBin = bank.bin;
                        options += `<option value="${bank.bin}" data-shortname="${bank.shortName}" data-name="${bank.name}" ${isSelected}>${bank.shortName} - ${bank.name}</option>`;
                    });
                    
                    $('#bank_select').html(options);

                    // Khởi tạo Select2 nếu có
                    if ($.fn.select2) {
                        $('#bank_select').select2({
                            theme: 'bootstrap-5'
                        });
                    }
                }
            },
            error: function() {
                $('#bank_select').html('<option value="">Lỗi tải danh sách ngân hàng</option>');
            }
        });

        // Khi thay đổi ngân hàng, cập nhật hidden inputs
        $('#bank_select').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            if(selectedOption.val() !== "") {
                $('#bank_name').val(selectedOption.data('name'));
                $('#bank_id').val(selectedOption.data('shortname'));
                checkAccountName(); // Kiểm tra lại tên nếu đã nhập stk
            } else {
                $('#bank_name').val('');
                $('#bank_id').val('');
            }
        });

        // Khi nhập Account Number -> GỌi API lookup
        $('#account_number').on('blur', function() {
            checkAccountName();
        });

        // Add timeout buffer for typing
        let typingTimer;
        $('#account_number').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(checkAccountName, 1000);
        });

        function checkAccountName() {
            const bin = $('#bank_select').val();
            const accountNo = $('#account_number').val().trim();
            
            if (bin && accountNo && accountNo.length >= 5) {
                // Hiển thị trạng thái đang tải
                let oldVal = $('#account_name').val();
                $('#account_name').val('Đang tra cứu...');
                
                $.ajax({
                    url: 'https://api.vietqr.io/v2/lookup',
                    method: 'POST',
                    headers: {
                        'x-client-id': 'b85a3c26-f831-4a5f-abaa-ae57d25e40e2',
                        'x-api-key': 'd102dc85-2eec-4752-9654-20a221f7e34a',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        bin: bin,
                        accountNumber: accountNo
                    }),
                    success: function(res) {
                        if (res.code == '00') {
                            $('#account_name').val(res.data.accountName);
                        } else {
                            $('#account_name').val('');
                        }
                    },
                    error: function() {
                        if ($('#account_name').val() === 'Đang tra cứu...') {
                           $('#account_name').val('');
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
