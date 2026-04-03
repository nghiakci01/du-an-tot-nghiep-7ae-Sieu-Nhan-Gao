# Plan: Remove Store Pickup

## 1. Goal
Remove the "Nhận tại cửa hàng" (Pick up in store) delivery option from the checkout process, mandating that all customers provide a delivery address for shipping.

## 2. Analyzed Files
- `resources/views/frontend/checkout/index.blade.php`: Frontend UI where the radio button and JavaScript logic for toggling delivery methods exist.
- `app/Http/Controllers/Frontend/CheckoutController.php`: Backend controller that validates and processes the checkout request, checking if `delivery_type` is 'store'.

## 3. Implementation Steps

### Phase 1: Frontend Cleanup (`resources/views/frontend/checkout/index.blade.php`)
- **[DELETE]** Delivery method toggle box (`.delivery-method-box`).
- **[DELETE]** The radio button inputs for `delivery_type` (`delivery_home` and `delivery_store`).
- **[MODIFY]** The address fields block (`#delivery_home_content`) should always be visible (i.e. remove `display: none` toggle behavior if any).
- **[MODIFY]** JavaScript validation `syncDeliveryModeUI()` and `requiresAddress()` can be removed or simplified to always return `true`.

### Phase 2: Backend Cleanup (`app/Http/Controllers/Frontend/CheckoutController.php`)
- **[MODIFY]** Remove validation rules related to `delivery_type` (lines 223).
- **[MODIFY]** Change `province` and `address` validation from `required_if:delivery_type,home` to strictly `required` (lines 224, 227).
- **[MODIFY]** Remove ternary checks like `$deliveryType === 'store' ? ... : ...` when setting `$shippingAddress` and creating the `Order` model (lines 307-314, 320-321).
- **[MODIFY]** The `Order::create` method will now directly use `$request->province` and `$request->address`.

## 4. Verification Plan
- **Manual Verification**: Go to the checkout page and ensure the option is gone, and the address fields are mandatory. Try to submit the form without filling the address to verify backend validation enforces it.
- **Admin Verification**: Verify that the admin dashboard still displays old store-pickup orders correctly (since they were saved as raw strings in DB).

## 5. Agent Assignments
- `frontend-specialist`: Blade UI & JavaScript cleanup.
- `backend-specialist`: Controller validation & business logic cleanup.
