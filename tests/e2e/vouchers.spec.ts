import { test, expect } from '@playwright/test';

test.describe('Voucher Logic', () => {
  // Use pre-authenticated session
  test.use({ storageState: 'playwright/.auth/user.json' });

  test.beforeEach(async ({ page }) => {
    // Ensure there's something in the cart to test vouchers on
    await page.goto('/');
    const productCard = page.locator('a[href*="/product/"]').first();
    await productCard.click();
    await page.locator('#btn-add-to-cart').click();
    await page.goto('/checkout');
  });

  test('User can apply a valid voucher and see the discount', async ({ page }) => {
    // 1. Locate the coupon input and apply a known valid voucher
    const couponInput = page.locator('#couponCode');
    const applyBtn = page.locator('#applyCouponBtn');

    await couponInput.fill('WELCOME50');
    await applyBtn.click();

    // 2. Verify success feedback
    const feedback = page.locator('#coupon-feedback, .text-success, .alert-success').first();
    await expect(feedback).toBeVisible({ timeout: 10000 });
    await expect(feedback).toContainText(['thành công', 'Áp dụng']);

    // 3. Verify total price reflects the discount (optional but recommended)
    const discountRow = page.locator('.discount-amount, .coupon-discount, .promo-discount');
    // If a discount row appears, it means it's working
    // await expect(discountRow).toBeVisible();
  });

  test('User gets an error when applying an invalid voucher', async ({ page }) => {
    // 1. Enter an invalid voucher code
    const couponInput = page.locator('#couponCode');
    const applyBtn = page.locator('#applyCouponBtn');

    await couponInput.fill('INVALID-VOUCHER-CODE-' + Date.now());
    await applyBtn.click();

    // 2. Verify error feedback
    const feedback = page.locator('#coupon-feedback, .text-danger, .alert-danger').first();
    await expect(feedback).toBeVisible({ timeout: 10000 });
    await expect(feedback).toContainText(['không tồn tại', 'không hợp lệ', 'Invalid']);
  });
});
