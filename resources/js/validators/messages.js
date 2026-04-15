const messages = {
  StoreUser: {
    name: {
      required: 'Vui lòng nhập tên.',
      max: 'Tên không được vượt quá 255 ký tự.'
    },
    email: {
      required: 'Vui lòng nhập email.',
      invalid: 'Email không hợp lệ.',
      max: 'Email không được vượt quá 255 ký tự.'
    },
    password: {
      required: 'Vui lòng nhập mật khẩu.',
      min: 'Mật khẩu phải có ít nhất 8 ký tự.'
    },
    password_confirmation: {
      match: 'Xác nhận mật khẩu không khớp.'
    },
    phone: {
      invalid: 'Số điện thoại không hợp lệ.',
      max: 'Số điện thoại không được vượt quá 20 ký tự.'
    },
    address: {
      invalid: 'Địa chỉ không hợp lệ.'
    },
    role: {
      required: 'Vui lòng chọn vai trò.',
      oneOf: 'Vai trò không hợp lệ.'
    }
  },

  StoreProduct: {
    name: { required: 'Vui lòng nhập tên sản phẩm.', max: 'Tên sản phẩm không được vượt quá 255 ký tự.' },
    category_id: { required: 'Vui lòng chọn danh mục.', integer: 'Danh mục không hợp lệ.' },
    price: { invalid: 'Giá không hợp lệ.', min: 'Giá phải lớn hơn hoặc bằng 0.' },
    sale_price: { invalid: 'Giá khuyến mãi không hợp lệ.', min: 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.' },
    short_description: { max: 'Mô tả ngắn không được vượt quá 500 ký tự.' },
    description: { max: 'Mô tả không được vượt quá 5000 ký tự.' },
    image: { invalid: 'Ảnh không hợp lệ.' },
    gallery_images: { maxItems: 'Tối đa 6 ảnh trong thư viện.' },
    is_active: { invalid: 'Trường trạng thái không hợp lệ.' },
    variants: { required: 'Vui lòng thêm ít nhất 1 biến thể.', minItems: 'Vui lòng thêm ít nhất 1 biến thể.' },
    'variants[].size_id': { required: 'Vui lòng chọn kích thước.', integer: 'Kích thước không hợp lệ.' },
    'variants[].color_id': { required: 'Vui lòng chọn màu.', integer: 'Màu không hợp lệ.' },
    'variants[].stock_quantity': { required: 'Vui lòng nhập số lượng kho.', integer: 'Số lượng phải là số nguyên.', min: 'Số lượng không được âm.' },
    'variants[].sku': { max: 'SKU không được vượt quá 100 ký tự.' }
  },

  StoreCategory: {
    name: { required: 'Vui lòng nhập tên danh mục.', max: 'Tên danh mục không được vượt quá 50 ký tự.' },
    parent_id: { integer: 'Danh mục cha không hợp lệ.' }
  },

  UpdateCategory: {
    name: { required: 'Vui lòng nhập tên danh mục.', max: 'Tên danh mục không được vượt quá 50 ký tự.' },
    parent_id: { integer: 'Danh mục cha không hợp lệ.' }
  },

  StoreCoupon: {
    code: { required: 'Vui lòng nhập mã khuyến mãi.', max: 'Mã không được vượt quá 50 ký tự.' },
    type: { required: 'Vui lòng chọn kiểu giảm giá.', oneOf: 'Kiểu giảm giá không hợp lệ.' },
    value: { required: 'Vui lòng nhập giá trị giảm giá.', min: 'Giá trị phải lớn hơn hoặc bằng 0.' },
    min_order_amount: { min: 'Số tiền tối thiểu phải lớn hơn hoặc bằng 0.' },
    max_discount_amount: { min: 'Số tiền tối đa phải lớn hơn hoặc bằng 0.' },
    usage_limit: { integer: 'Số lần sử dụng không hợp lệ.', min: 'Số lần sử dụng phải >= 1.' },
    start_date: { invalid: 'Ngày bắt đầu không hợp lệ.' },
    end_date: { invalid: 'Ngày kết thúc không hợp lệ.' },
    is_active: { invalid: 'Trường trạng thái không hợp lệ.' },
    description: { max: 'Mô tả không được vượt quá 500 ký tự.' },
    user_id: { integer: 'Người dùng không hợp lệ.' }
  },

  UpdateCoupon: {
    code: { required: 'Vui lòng nhập mã khuyến mãi.', max: 'Mã không được vượt quá 50 ký tự.' },
    type: { required: 'Vui lòng chọn kiểu giảm giá.', oneOf: 'Kiểu giảm giá không hợp lệ.' },
    value: { required: 'Vui lòng nhập giá trị giảm giá.', min: 'Giá trị phải lớn hơn hoặc bằng 0.' },
    min_order_amount: { min: 'Số tiền tối thiểu phải lớn hơn hoặc bằng 0.' },
    max_discount_amount: { min: 'Số tiền tối đa phải lớn hơn hoặc bằng 0.' },
    usage_limit: { integer: 'Số lần sử dụng không hợp lệ.', min: 'Số lần sử dụng phải >= 1.' },
    start_date: { invalid: 'Ngày bắt đầu không hợp lệ.' },
    end_date: { invalid: 'Ngày kết thúc không hợp lệ.' },
    is_active: { invalid: 'Trường trạng thái không hợp lệ.' },
    description: { max: 'Mô tả không được vượt quá 500 ký tự.' },
    user_id: { integer: 'Người dùng không hợp lệ.' }
  },

  UpdateProduct: {
    name: { required: 'Vui lòng nhập tên sản phẩm.', max: 'Tên sản phẩm không được vượt quá 255 ký tự.' },
    category_id: { required: 'Vui lòng chọn danh mục.', integer: 'Danh mục không hợp lệ.' },
    price: { invalid: 'Giá không hợp lệ.', min: 'Giá phải lớn hơn hoặc bằng 0.' },
    sale_price: { invalid: 'Giá khuyến mãi không hợp lệ.', min: 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.' },
    short_description: { max: 'Mô tả ngắn không được vượt quá 500 ký tự.' },
    description: { max: 'Mô tả không được vượt quá 5000 ký tự.' },
    image: { invalid: 'Ảnh không hợp lệ.' },
    gallery_images: { maxItems: 'Tối đa 6 ảnh trong thư viện.' },
    is_active: { invalid: 'Trường trạng thái không hợp lệ.' },
    variants: { minItems: 'Vui lòng thêm ít nhất 1 biến thể.' }
  },

  UpdateUser: {
    name: { required: 'Vui lòng nhập tên.', max: 'Tên không được vượt quá 255 ký tự.' },
    email: { required: 'Vui lòng nhập email.', invalid: 'Email không hợp lệ.', max: 'Email không được vượt quá 255 ký tự.' },
    password: { min: 'Mật khẩu phải có ít nhất 8 ký tự.' },
    phone: { invalid: 'Số điện thoại không hợp lệ.', max: 'Số điện thoại không được vượt quá 20 ký tự.' },
    address: { invalid: 'Địa chỉ không hợp lệ.' },
    role: { required: 'Vui lòng chọn vai trò.', oneOf: 'Vai trò không hợp lệ.' }
  }
};

export default messages;
