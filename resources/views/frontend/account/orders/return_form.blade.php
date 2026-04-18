@extends('layouts.public')

@section('title', 'Yêu cầu hoàn trả - Đơn #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.return-form-wrapper {
  background: #f5f5f7;
  padding: 40px 0 60px;
  min-height: 80vh;
}
.detail-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.07);
  overflow: hidden;
  margin-bottom: 20px;
}
.detail-header {
  padding: 18px 24px;
  border-bottom: 1px solid #f0f0f0;
}
.detail-header h5 { margin: 0; font-weight: 700; font-size: 1rem; }
.detail-body { padding: 22px 24px; }

/* Bank Selector Styling */
.select2-container .select2-selection--single {
    height: 48px;
    display: flex;
    align-items: center;
    border: 1px solid #dee2e6;
    border-radius: 10px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 48px;
    padding-left: 12px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 46px;
}
.bank-logo {
    height: 24px;
    width: auto;
    margin-right: 12px;
    vertical-align: middle;
}
.bank-item {
    display: flex;
    align-items: center;
    padding: 4px 0;
}

/* Image Upload UI */
.image-upload-wrap {
  position: relative;
  border: 2px dashed #ddd;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px;
  cursor: pointer;
  background: #fafafa;
  transition: all 0.2s;
}
.image-upload-wrap:hover { background: #f0f0f0; border-color: #bbb; }
.image-upload-wrap i { font-size: 2rem; color: #888; margin-bottom: 10px; }
.image-upload-wrap input[type=file] {
  position: absolute; width: 100%; height: 100%;
  opacity: 0; cursor: pointer; top: 0; left: 0;
}

#preview-container {
  display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;
}
.img-preview {
  width: 80px; height: 80px;
  border-radius: 8px; object-fit: cover;
  border: 1px solid #ddd;
}

/* Video Upload UI */
.video-upload-wrap {
  position: relative;
  border: 2px dashed #ddd;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px;
  cursor: pointer;
  background: #fafafa;
  transition: all 0.2s;
}
.video-upload-wrap:hover { background: #f0f0f0; border-color: #bbb; }
.video-upload-wrap i { font-size: 2rem; color: #888; margin-bottom: 10px; }
.video-upload-wrap input[type=file] {
  position: absolute; width: 100%; height: 100%;
  opacity: 0; cursor: pointer; top: 0; left: 0;
}
#video-preview-container {
  margin-top: 15px;
}
  #video-preview-container video {
  max-width: 100%;
  max-height: 240px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

/* Method Card Styling */
.method-card:hover {
    border-color: #1a1a2e !important;
    background-color: #f8f9fa;
}
.method-card.active {
    border-color: #1a1a2e !important;
    border-width: 2px !important;
    background-color: #f0f7ff;
}
.method-card.active h6 {
    color: #1a1a2e;
}
.avtar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}
.bg-light-primary { background: #e0e7ff; color: #4338ca; }
.bg-light-success { background: #dcfce7; color: #15803d; }
.bg-light-info { background: #e0f2fe; color: #0369a1; }
</style>
@endpush

@section('content')
<div class="breadcrumbs_area other_bread">
  <div class="container">
    <div class="breadcrumb_content">
      <ul>
        <li><a href="{{ route('welcome') }}">Trang chủ</a></li>
        <li>/</li>
        <li><a href="{{ route('account.index') }}?tab=orders">Tài khoản</a></li>
        <li>/</li>
        <li><a href="{{ route('account.orders.show', $order->id) }}">Đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</a></li>
        <li>/</li>
        <li>Yêu cầu hoàn trả</li>
      </ul>
    </div>
  </div>
</div>

<div class="return-form-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        @if(session('error'))
          <div class="alert alert-danger rounded-3 mb-3 shadow-sm border-0">{{ session('error') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger rounded-3 mb-3 shadow-sm border-0">
            <ul class="mb-0 px-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="detail-card">
          <div class="detail-header">
            <h5><i class="bi bi-arrow-return-left me-2"></i>Tạo Yêu cầu Hoàn trả</h5>
          </div>
          <div class="detail-body">
            
            <div class="alert alert-info rounded-3 mb-4 border-0 shadow-sm" style="font-size:0.9rem;">
              <strong>Lưu ý:</strong> Hiện tại cửa hàng chỉ hỗ trợ <strong>Hoàn trả toàn bộ đơn hàng</strong>. Chúng tôi không hỗ trợ trả lẻ từng sản phẩm. Nếu bạn muốn đổi size/màu, vui lòng hoàn trả toàn bộ đơn hàng này và đặt một đơn hàng mới.
            </div>

            <form action="{{ route('account.orders.return_submit', $order->id) }}" method="POST" enctype="multipart/form-data">
              @csrf

              {{-- Item Selection --}}
              <div class="mb-5">
                <label class="form-label fw-bold mb-3">Chọn sản phẩm muốn hoàn trả <span class="text-danger">*</span></label>
                <div class="table-responsive">
                  <table class="table table-borderless align-middle">
                    <thead class="table-light">
                      <tr>
                        <th width="80" class="text-center">STT</th>
                        <th>Sản phẩm</th>
                        <th width="120" class="text-center">Số lượng</th>
                        <th width="150" class="text-end">Đơn giá</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $totalOrderAmount = 0; @endphp
                      @foreach($order->items as $index => $item)
                      @php $totalOrderAmount += $item->price * $item->quantity; @endphp
                      <tr class="item-row border-bottom">
                        <td class="text-center text-muted">
                          {{ $index + 1 }}
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            @if($item->product && $item->product->image)
                              <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                            @elseif($item->product && $item->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                            @endif
                            <div>
                              <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                              @if($item->variant_name)
                                <small class="text-muted">Phân loại: {{ $item->variant_name }}</small>
                              @endif
                            </div>
                          </div>
                        </td>
                        <td class="text-center fw-bold">
                          x{{ $item->quantity }}
                        </td>
                        <td class="text-end fw-bold">
                          {{ number_format($item->price, 0, ',', '.') }}₫
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded-3">
                  <span class="fw-bold">Tổng số tiền hoàn lại (Toàn bộ đơn):</span>
                  <span class="text-danger fw-bold fs-5">{{ number_format($totalOrderAmount, 0, ',', '.') }}₫</span>
                </div>
              </div>

              {{-- Return Type --}}
              <input type="hidden" name="type" value="refund">

              {{-- Reason Type --}}
              <div class="mb-4">
                <label class="form-label fw-bold">Lý do cụ thể <span class="text-danger">*</span></label>
                <select name="reason_type" id="reason_type" class="form-select form-select-lg" required style="border-radius:10px; font-size:15px;">
                  <option value="" disabled {{ !old('reason_type') ? 'selected' : '' }}>-- Chọn lý do --</option>
                  <option value="defective" {{ old('reason_type') == 'defective' ? 'selected' : '' }}>Hàng lỗi / Hư hỏng do vận chuyển hoặc NSX</option>
                  <option value="disliked" {{ old('reason_type') == 'disliked' ? 'selected' : '' }}>Sản phẩm không giống mô tả / Không ưng ý</option>
                  <option value="other" {{ old('reason_type') == 'other' ? 'selected' : '' }}>Lý do khác</option>
                </select>
                <input type="hidden" name="reason" id="reason_text" value="{{ old('reason') }}">
              </div>

              {{-- Note --}}
              <div class="mb-4">
                <label class="form-label fw-bold">Chi tiết lỗi / Yêu cầu thêm <span class="text-danger">*</span></label>
                <textarea name="note" class="form-control" rows="4" placeholder="Vui lòng mô tả chi tiết tình trạng sản phẩm..." style="border-radius:10px;" required>{{ old('note') }}</textarea>
              </div>

              {{-- Return Method --}}
              <div class="mb-5">
                  <label class="form-label fw-bold mb-3">Phương thức gửi hàng hoàn trả <span class="text-danger">*</span></label>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="method-card p-3 border rounded-3 h-100 position-relative" data-value="at_home" style="cursor:pointer; transition: all 0.2s;">
                        <div class="d-flex align-items-center">
                          <div class="avtar bg-light-primary me-3">
                            <i class="bi bi-house-door fs-4"></i>
                          </div>
                          <div>
                            <h6 class="mb-0 fw-bold">Shipper đến lấy hàng</h6>
                            <small class="text-muted" style="font-size: 0.8rem;">Nhân viên sẽ đến tận nhà lấy hàng</small>
                          </div>
                        </div>
                        <input type="radio" name="return_method" value="at_home" class="d-none" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="method-card p-3 border rounded-3 h-100 position-relative" data-value="at_post_office" style="cursor:pointer; transition: all 0.2s;">
                        <div class="d-flex align-items-center">
                          <div class="avtar bg-light-success me-3">
                            <i class="bi bi-building fs-4"></i>
                          </div>
                          <div>
                            <h6 class="mb-0 fw-bold">Tự mang đến bưu cục</h6>
                            <small class="text-muted" style="font-size: 0.8rem;">Gửi hàng tại bưu cục gần nhất</small>
                          </div>
                        </div>
                        <input type="radio" name="return_method" value="at_post_office" class="d-none">
                      </div>
                    </div>
                  </div>
                  
                  <div id="ghn-map-link" class="mt-3 p-3 rounded-3 border border-warning d-none" style="background-color: #fffaf0;">
                    <div class="d-flex align-items-center mb-2">
                        <div class="d-flex align-items-center gap-1 me-2" style="color: #f26522; font-weight: 800; font-size: 1.1rem; line-height: 1;">
                            <i class="bi bi-truck"></i> GHN
                        </div>
                        <span class="small fw-bold" style="color: #f26522;">Bạn có thể gửi hàng hoàn trả tại bất kỳ bưu cục Giao Hàng Nhanh (GHN) nào.</span>
                    </div>
                    <a href="https://www.google.com/maps/search/Giao+hàng+nhanh/" target="_blank" class="btn btn-warning btn-sm w-100 rounded-pill py-2" style="background-color: #f26522; border-color: #f26522; color: white;">
                      <i class="bi bi-geo-alt-fill me-1"></i>🌏 Tìm bưu cục GHN gần nhất trên Google Maps
                    </a>
                  </div>
              </div>

              {{-- Images --}}
              <div class="mb-4">
                <label class="form-label fw-bold">Ảnh minh chứng <span class="text-danger">*</span> <span class="text-muted fw-normal">(Tối đa 4 ảnh)</span></label>
                <div class="image-upload-wrap">
                  <div class="text-center">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-0 text-muted">Nhấn vào đây để tải ảnh lên (Bắt buộc)</p>
                  </div>
                  <input type="file" name="images[]" id="return-images" multiple accept="image/*" required>
                </div>
                <div id="preview-container"></div>
              </div>

              {{-- Videos --}}
              <div class="mb-5">
                <label class="form-label fw-bold"><i class="bi bi-camera-reels me-1"></i>Video minh chứng <span class="text-danger">*</span> <span class="text-muted fw-normal">(Tối đa 1 video, 50MB)</span></label>
                <div class="video-upload-wrap">
                  <div class="text-center">
                    <i class="bi bi-film"></i>
                    <p class="mb-0 text-muted">Nhấn vào đây để tải video lên (Bắt buộc - MP4, MOV, AVI, WebM)</p>
                  </div>
                  <input type="file" name="videos[]" id="return-videos" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm" required>
                </div>
                <div id="video-preview-container"></div>
              </div>

              {{-- Refund Bank Info --}}
              <div class="mb-5 p-4 border rounded-3 bg-light shadow-sm">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                  <i class="bi bi-bank me-2 text-primary"></i> Thông tin nhận tiền hoàn (Bắt buộc)
                </h6>
                <div class="row g-3">

                  <div class="col-md-6">
                    <label class="form-label small fw-bold">Ngân hàng <span class="text-danger">*</span></label>
                    <select name="bank_bin" id="bank_bin" class="form-select select2-bank" required>
                      <option value="">-- Chọn Ngân Hàng --</option>
                      <!-- Top Preferred Banks -->
                      <option value="970436" data-logo="https://api.vietqr.io/img/VCB.png" data-name="Vietcombank" {{ old('bank_bin') == '970436' ? 'selected' : '' }}>Vietcombank</option>
                      <option value="970418" data-logo="https://api.vietqr.io/img/BIDV.png" data-name="BIDV" {{ old('bank_bin') == '970418' ? 'selected' : '' }}>BIDV</option>
                      <option value="970405" data-logo="https://api.vietqr.io/img/VBA.png" data-name="Agribank" {{ old('bank_bin') == '970405' ? 'selected' : '' }}>Agribank</option>
                      <option value="970415" data-logo="https://api.vietqr.io/img/CTG.png" data-name="VietinBank" {{ old('bank_bin') == '970415' ? 'selected' : '' }}>VietinBank</option>
                      <option value="970407" data-logo="https://api.vietqr.io/img/TCB.png" data-name="Techcombank" {{ old('bank_bin') == '970407' ? 'selected' : '' }}>Techcombank</option>
                      <option value="970422" data-logo="https://api.vietqr.io/img/MB.png" data-name="MBBank" {{ old('bank_bin') == '970422' ? 'selected' : '' }}>MBBank</option>
                      <option value="970416" data-logo="https://api.vietqr.io/img/ACB.png" data-name="ACB" {{ old('bank_bin') == '970416' ? 'selected' : '' }}>ACB</option>
                      <option value="970403" data-logo="https://api.vietqr.io/img/STB.png" data-name="Sacombank" {{ old('bank_bin') == '970403' ? 'selected' : '' }}>Sacombank</option>
                      <option value="970432" data-logo="https://api.vietqr.io/img/VPB.png" data-name="VPBank" {{ old('bank_bin') == '970432' ? 'selected' : '' }}>VPBank</option>
                      <option value="970423" data-logo="https://api.vietqr.io/img/TPB.png" data-name="TPBank" {{ old('bank_bin') == '970423' ? 'selected' : '' }}>TPBank</option>
                      <!-- Other Banks -->
                      <option value="970437" data-logo="https://api.vietqr.io/img/HDB.png" data-name="HDBank" {{ old('bank_bin') == '970437' ? 'selected' : '' }}>HDBBank</option>
                      <option value="970441" data-logo="https://api.vietqr.io/img/VIB.png" data-name="VIB" {{ old('bank_bin') == '970441' ? 'selected' : '' }}>VIB</option>
                      <option value="970443" data-logo="https://api.vietqr.io/img/SHB.png" data-name="SHB" {{ old('bank_bin') == '970443' ? 'selected' : '' }}>SHB</option>
                      <option value="970426" data-logo="https://api.vietqr.io/img/MSB.png" data-name="MSB" {{ old('bank_bin') == '970426' ? 'selected' : '' }}>MSB</option>
                      <option value="970440" data-logo="https://api.vietqr.io/img/SEAB.png" data-name="SeABank" {{ old('bank_bin') == '970440' ? 'selected' : '' }}>SeABank</option>
                      <option value="970449" data-logo="https://api.vietqr.io/img/LPB.png" data-name="LPBank" {{ old('bank_bin') == '970449' ? 'selected' : '' }}>LPBank</option>
                      <option value="970428" data-logo="https://api.vietqr.io/img/NAB.png" data-name="NamABank" {{ old('bank_bin') == '970428' ? 'selected' : '' }}>NamABank</option>
                      <option value="970414" data-logo="https://api.vietqr.io/img/OCB.png" data-name="OCB" {{ old('bank_bin') == '970414' ? 'selected' : '' }}>OCB</option>
                      <option value="970431" data-logo="https://api.vietqr.io/img/EIB.png" data-name="Eximbank" {{ old('bank_bin') == '970431' ? 'selected' : '' }}>Eximbank</option>
                      <option value="970438" data-logo="https://api.vietqr.io/img/BVB.png" data-name="BVBank" {{ old('bank_bin') == '970438' ? 'selected' : '' }}>BVBank</option>
                    </select>
                    <input type="hidden" name="bank_name" id="bank_name" value="{{ old('bank_name') }}">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label small fw-bold">Số tài khoản <span class="text-danger">*</span></label>
                    <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Nhập số tài khoản" required style="border-radius:10px; height:45px;" value="{{ old('account_number') }}">
                  </div>

                  <div class="col-md-12">
                    <label class="form-label small fw-bold">Tên chủ tài khoản <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <input type="text" name="account_name" id="account_name" class="form-control text-uppercase" placeholder="Hệ thống sẽ tra cứu tự động..." required style="border-radius:10px; height:45px; background-color: #f8f9fa;" value="{{ old('account_name') }}">
                      <div id="lookup-spinner" class="spinner-border spinner-border-sm text-primary position-absolute" role="status" style="right: 15px; top: 15px; display: none;">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                    </div>
                    <small id="lookup-msg" class="text-muted"></small>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-light rounded-pill px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-warning rounded-pill px-4" style="background:#1a1a2e; color:white; border:none;">
                  <i class="bi bi-send me-1"></i> Gửi yêu cầu
                </button>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Initialize Select2 for Banks
    function formatBank(bank) {
        if (!bank.id) return bank.text;
        var logoUrl = $(bank.element).data('logo');
        if (logoUrl) {
            return $('<div class="bank-item"><img src="' + logoUrl + '" class="bank-logo" /> <span>' + bank.text + '</span></div>');
        }
        return bank.text;
    }

    $('.select2-bank').select2({
        placeholder: '-- Chọn Ngân Hàng --',
        allowClear: true,
        templateResult: formatBank,
        templateSelection: formatBank,
        width: '100%'
    });

    // 2. Pre-fill from Saved Accounts
    $('#saved-bank-select').on('change', function() {
        const option = $(this).find('option:selected');
        if (option.val()) {
            const bin = option.data('bin');
            const number = option.data('number');
            const accName = option.data('accname');
            const bankName = option.data('name');

            $('#bank_bin').val(bin).trigger('change');
            $('#account_number').val(number);
            $('#account_name').val(accName);
            $('#bank_name').val(bankName);
        }
    });

    // 3. Update hidden bank_name when select changes
    $('#bank_bin').on('change', function() {
        const selected = $(this).find('option:selected');
        $('#bank_name').val(selected.data('name') || '');
        checkAccountName();
    });

    // 3.1. Update hidden reason field when reason_type changes
    function syncReason() {
        const selectedOption = $('#reason_type').find('option:selected');
        if (selectedOption.val()) {
            $('#reason_text').val(selectedOption.text());
        } else {
            $('#reason_text').val('');
        }
    }
    $('#reason_type').on('change', syncReason);
    syncReason(); // Initial sync on load

    // 4. Real-time VietQR Lookup
    let lookupTimer;
    $('#account_number').on('input', function() {
        clearTimeout(lookupTimer);
        lookupTimer = setTimeout(checkAccountName, 800);
    });

    function checkAccountName() {
        const bin = $('#bank_bin').val();
        const accountNo = $('#account_number').val().trim();
        const $accNameInput = $('#account_name');
        const $spinner = $('#lookup-spinner');
        const $msg = $('#lookup-msg');

        if (bin && accountNo && accountNo.length >= 6) {
            $spinner.show();
            $msg.text('Đang xác thực tài khoản...');
            $accNameInput.css('background-color', '#fff'); // Reset background

            $.ajax({
                url: 'https://api.vietqr.io/v2/lookup',
                method: 'POST',
                headers: {
                    'x-client-id': 'b85a3c26-f831-4a5f-abaa-ae57d25e40e2', // From admin script
                    'x-api-key': 'd102dc85-2eec-4752-9654-20a221f7e34a',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ bin: bin, accountNumber: accountNo }),
                success: function(res) {
                    $spinner.hide();
                    if (res.code == '00') {
                        $accNameInput.val(res.data.accountName);
                        $msg.html('<span class="text-success"><i class="bi bi-check-circle-fill"></i> Tài khoản hợp lệ</span>');
                        $accNameInput.prop('readonly', true);
                    } else {
                        $msg.html('<span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Không tìm thấy tài khoản. Vui lòng kiểm tra lại.</span>');
                        $accNameInput.prop('readonly', false).val('');
                    }
                },
                error: function() {
                    $spinner.hide();
                    $msg.text('Không thể tra cứu tự động. Vui lòng nhập tay.');
                    $accNameInput.prop('readonly', false);
                }
            });
        }
    }


    // 6. Image preview
    $('#return-images').on('change', function(e) {
        const container = $('#preview-container');
        container.empty();
        const files = Array.from(e.target.files).slice(0, 4);
        files.forEach(file => {
            if(file.type.startsWith('image/')) {
                const img = $('<img>').addClass('img-preview').attr('src', URL.createObjectURL(file));
                container.append(img);
            }
        });
    });

    // 7. Video preview
    $('#return-videos').on('change', function(e) {
        const container = $('#video-preview-container');
        container.empty();
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 50 * 1024 * 1024) {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Video không được vượt quá 50MB' });
            $(this).val('');
            return;
        }

        if (file.type.startsWith('video/')) {
            const video = $('<video controls>').attr('src', URL.createObjectURL(file));
            container.append(video);
            const info = $('<p>').addClass('text-muted small mt-1').text(file.name + ' (' + (file.size / (1024*1024)).toFixed(1) + ' MB)');
            container.append(info);
        }
    });

    // 8. Return Method Selection
    $('.method-card').on('click', function() {
        const value = $(this).data('value');
        
        // Update selection UI
        $('.method-card').removeClass('active');
        $(this).addClass('active');
        
        // Update radio input
        $('.method-card input[type="radio"]').prop('checked', false);
        $(this).find('input[type="radio"]').prop('checked', true);
        
        // Show/Hide GHN link
        if (value === 'at_post_office') {
            $('#ghn-map-link').removeClass('d-none').hide().fadeIn(300);
        } else {
            $('#ghn-map-link').fadeOut(200, function() {
                $(this).addClass('d-none');
            });
        }
    });

    // Handle initial state for Return Method if has old value
    const oldMethod = "{{ old('return_method') }}";
    if (oldMethod) {
        $(`.method-card[data-value="${oldMethod}"]`).trigger('click');
    }

    // Trigger initial calculation
    // calculateTotalRefund(); // No longer needed for 'Return All' policy
});
</script>
@endpush
