@extends('layouts.admin')

@section('title', 'Sửa Tài Khoản Ngân Hàng')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 48px;
        display: flex;
        align-items: center;
        border: 1px solid #ced4da;
        border-radius: 6px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding-left: 12px;
        padding-right: 36px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
    }
    .bank-logo {
        height: 24px;
        width: auto;
        max-width: 60px;
        object-fit: contain;
        margin-right: 12px;
        vertical-align: middle;
    }
    .bank-item {
        display: flex;
        align-items: center;
        padding: 4px 0;
    }
    [data-pc-theme="dark"] .select2-container .select2-selection--single {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }
    [data-pc-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #fff;
    }
    [data-pc-theme="dark"] .select2-dropdown {
        background-color: #1a1a1a;
        border-color: rgba(255, 255, 255, 0.1);
    }
    [data-pc-theme="dark"] .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #333;
    }
    [data-pc-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #4680ff;
    }
    [data-pc-theme="dark"] .select2-search input {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
</style>

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
                                    <option value="">-- Chọn Ngân Hàng --</option>
                                    <!-- Top 10 Preferred Banks -->
                                    <option value="970436" data-logo="https://api.vietqr.io/img/VCB.png" data-shortname="Vietcombank" data-name="Ngan hang Ngoai Thuong Viet Nam" {{ old('bank_id', $bank->bank_id) == 'Vietcombank' ? 'selected' : '' }}>Vietcombank</option>
                                    <option value="970418" data-logo="https://api.vietqr.io/img/BIDV.png" data-shortname="BIDV" data-name="Ngan hang Dau tu va Phat trien Viet Nam" {{ old('bank_id', $bank->bank_id) == 'BIDV' ? 'selected' : '' }}>BIDV</option>
                                    <option value="970405" data-logo="https://api.vietqr.io/img/VBA.png" data-shortname="Agribank" data-name="Ngan hang Nong nghiep va Phat trien Nong thon Viet Nam" {{ old('bank_id', $bank->bank_id) == 'Agribank' ? 'selected' : '' }}>Agribank</option>
                                    <option value="970415" data-logo="https://api.vietqr.io/img/CTG.png" data-shortname="VietinBank" data-name="Ngan hang Cong Thuong Viet Nam" {{ old('bank_id', $bank->bank_id) == 'VietinBank' ? 'selected' : '' }}>VietinBank</option>
                                    <option value="970407" data-logo="https://api.vietqr.io/img/TCB.png" data-shortname="Techcombank" data-name="Ngan hang Ky thuong Viet Nam" {{ old('bank_id', $bank->bank_id) == 'Techcombank' ? 'selected' : '' }}>Techcombank</option>
                                    <option value="970422" data-logo="https://api.vietqr.io/img/MB.png" data-shortname="MBBank" data-name="Ngan hang Quan doi" {{ old('bank_id', $bank->bank_id) == 'MB' || old('bank_id', $bank->bank_id) == 'MBBank' ? 'selected' : '' }}>MBBank</option>
                                    <option value="970416" data-logo="https://api.vietqr.io/img/ACB.png" data-shortname="ACB" data-name="Ngan hang A Chau" {{ old('bank_id', $bank->bank_id) == 'ACB' ? 'selected' : '' }}>ACB</option>
                                    <option value="970403" data-logo="https://api.vietqr.io/img/STB.png" data-shortname="Sacombank" data-name="Ngan hang Sai Gon Thương Tín" {{ old('bank_id', $bank->bank_id) == 'Sacombank' ? 'selected' : '' }}>Sacombank</option>
                                    <option value="970432" data-logo="https://api.vietqr.io/img/VPB.png" data-shortname="VPBank" data-name="Ngan hang Viet Nam Thinh Vuong" {{ old('bank_id', $bank->bank_id) == 'VPBank' ? 'selected' : '' }}>VPBank</option>
                                    <option value="970423" data-logo="https://api.vietqr.io/img/TPB.png" data-shortname="TPBank" data-name="Ngan hang Tien Phong" {{ old('bank_id', $bank->bank_id) == 'TPBank' ? 'selected' : '' }}>TPBank</option>
                                    
                                    <!-- Other Banks -->
                                    <option value="970437" data-logo="https://api.vietqr.io/img/HDB.png" data-shortname="HDBank" data-name="Ngan hang Phat trien TP.HCM" {{ old('bank_id', $bank->bank_id) == 'HDBank' ? 'selected' : '' }}>HDBank</option>
                                    <option value="970441" data-logo="https://api.vietqr.io/img/VIB.png" data-shortname="VIB" data-name="Ngan hang Quoc te" {{ old('bank_id', $bank->bank_id) == 'VIB' ? 'selected' : '' }}>VIB</option>
                                    <option value="970443" data-logo="https://api.vietqr.io/img/SHB.png" data-shortname="SHB" data-name="Ngan hang Sai Gon - Ha Noi" {{ old('bank_id', $bank->bank_id) == 'SHB' ? 'selected' : '' }}>SHB</option>
                                    <option value="970426" data-logo="https://api.vietqr.io/img/MSB.png" data-shortname="MSB" data-name="Ngan hang Hang Hai" {{ old('bank_id', $bank->bank_id) == 'MSB' ? 'selected' : '' }}>MSB</option>
                                    <option value="970440" data-logo="https://api.vietqr.io/img/SEAB.png" data-shortname="SeABank" data-name="Ngan hang TMCP Dong Nam A" {{ old('bank_id', $bank->bank_id) == 'SeABank' ? 'selected' : '' }}>SeABank</option>
                                    <option value="970449" data-logo="https://api.vietqr.io/img/LPB.png" data-shortname="LPBank" data-name="Ngan hang TMCP Loc Phat" {{ old('bank_id', $bank->bank_id) == 'LPBank' ? 'selected' : '' }}>LPBank</option>
                                    <option value="970428" data-logo="https://api.vietqr.io/img/NAB.png" data-shortname="NamABank" data-name="Ngan hang Nam A" {{ old('bank_id', $bank->bank_id) == 'NamABank' ? 'selected' : '' }}>NamABank</option>
                                    <option value="970414" data-logo="https://api.vietqr.io/img/OCB.png" data-shortname="OCB" data-name="Ngan hang Phuong Dong" {{ old('bank_id', $bank->bank_id) == 'OCB' ? 'selected' : '' }}>OCB</option>
                                    <option value="970431" data-logo="https://api.vietqr.io/img/EIB.png" data-shortname="Eximbank" data-name="Ngan hang Xuat Nhap Khau" {{ old('bank_id', $bank->bank_id) == 'Eximbank' ? 'selected' : '' }}>Eximbank</option>
                                    <option value="970438" data-logo="https://api.vietqr.io/img/BVB.png" data-shortname="BVBank" data-name="Ngan hang Ban Viet" {{ old('bank_id', $bank->bank_id) == 'BVBank' ? 'selected' : '' }}>BVBank</option>
                                    <option value="970429" data-logo="https://api.vietqr.io/img/SCB.png" data-shortname="SCB" data-name="Ngan hang Sai Gon" {{ old('bank_id', $bank->bank_id) == 'SCB' ? 'selected' : '' }}>SCB</option>
                                    <option value="970427" data-logo="https://api.vietqr.io/img/VAB.png" data-shortname="VietABank" data-name="Ngan hang Viet A" {{ old('bank_id', $bank->bank_id) == 'VietABank' ? 'selected' : '' }}>VietABank</option>
                                    <option value="970430" data-logo="https://api.vietqr.io/img/PGB.png" data-shortname="PGBank" data-name="Ngan hang Xang dau Petrolimex" {{ old('bank_id', $bank->bank_id) == 'PGBank' ? 'selected' : '' }}>PGBank</option>
                                    <option value="970409" data-logo="https://api.vietqr.io/img/BAB.png" data-shortname="BacABank" data-name="Ngan hang Bac A" {{ old('bank_id', $bank->bank_id) == 'BacABank' ? 'selected' : '' }}>BacABank</option>
                                    <option value="970448" data-logo="https://api.vietqr.io/img/OCB.png" data-shortname="PVcomBank" data-name="Ngan hang Dai chung Viet Nam" {{ old('bank_id', $bank->bank_id) == 'PVcomBank' ? 'selected' : '' }}>PVcomBank</option>
                                    <option value="970433" data-logo="https://api.vietqr.io/img/VIETBANK.png" data-shortname="VietBank" data-name="Ngan hang Viet Nam Thuong Tin" {{ old('bank_id', $bank->bank_id) == 'VietBank' ? 'selected' : '' }}>VietBank</option>
                                    <option value="970425" data-logo="https://api.vietqr.io/img/ABB.png" data-shortname="ABBANK" data-name="Ngan hang An Binh" {{ old('bank_id', $bank->bank_id) == 'ABBANK' ? 'selected' : '' }}>ABBANK</option>
                                    <option value="970412" data-logo="https://api.vietqr.io/img/PVB.png" data-shortname="BAOVIET Bank" data-name="Ngan hang Bao Viet" {{ old('bank_id', $bank->bank_id) == 'BAOVIET Bank' ? 'selected' : '' }}>BAOVIET Bank</option>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initBankScript() {
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') {
            setTimeout(initBankScript, 100);
            return;
        }

        let banksData = [];

        function formatBank(bank) {
            if (!bank.id) {
                return bank.text;
            }
            var logoUrl = $(bank.element).data('logo');
            if(logoUrl) {
                var $bank = $('<div class="bank-item"><img src="' + logoUrl + '" class="bank-logo" /> <span>' + bank.text + '</span></div>');
                return $bank;
            }
            return bank.text;
        }

        // Khởi tạo Select2
        $('#bank_select').select2({
            placeholder: '-- Chọn Ngân Hàng --',
            allowClear: true,
            templateResult: formatBank,
            templateSelection: formatBank,
            width: '100%',
            language: {
                noResults: function() {
                    return "Không tìm thấy kết quả";
                }
            }
        });

        // Ngừng fetch danh sách ngân hàng từ VietQR vì đã hardcode để load nhanh
        // Khởi tạo giá trị ban đầu cho Select2 nếu chưa được chọn (trường hợp load trang đầu tiên)
        if (!$('#bank_select').val()) {
             const currentBankId = $('#bank_id').val();
             if (currentBankId) {
                $('#bank_select option').each(function() {
                    if ($(this).data('shortname') == currentBankId) {
                        $(this).prop('selected', true);
                        return false;
                    }
                });
                $('#bank_select').trigger('change.select2');
             }
        }

        // Khi thay đổi ngân hàng, cập nhật hidden inputs
        $('#bank_select').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            if(selectedOption.val() && selectedOption.val() !== "") {
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
    }

    // Khởi chạy script
    initBankScript();
</script>
@endsection
