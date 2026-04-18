import { test, expect } from '@playwright/test';

test.describe('Checkout and Voucher Flow', () => {
  // Use pre-authenticated session
  test.use({ storageState: 'playwright/.auth/user.json' });

  test.beforeEach(async ({ page }) => {
    // Navigate to home page
    await page.goto('/');
  });

  test('User can apply a voucher and place a COD order', async ({ page }) => {
    // 1. Select the first product from the home page
    const productCard = page.locator('.product-card, .item, a[href*="/product/"]').first();
    await productCard.click();

    // 2. Select variants if present (Size/Color)
    const variantOptions = page.locator('.variant-option');
    const variantCount = await variantOptions.count();
    if (variantCount > 0) {
      // Select first available size
      await variantOptions.first().click();
      // Select first available color (if it's a different group, usually nth(1) or similar)
      const secondVariantGroup = page.locator('.variant-group').nth(1).locator('.variant-option');
      if (await secondVariantGroup.count() > 0) {
          await secondVariantGroup.first().click();
      }
    }

    // 3. Add to cart
    await page.locator('#btn-add-to-cart').click();
    
    // 4. Wait for cart update and navigate to cart
    await page.waitForSelector('.cart-count');
    await page.goto('/cart');
    
    // 5. Click checkout button
    // The selector might be .btn-checkout or similar based on logged in state
    await page.locator('a.btn-checkout, a.btn-login-to-checkout, button.btn-checkout').first().click();
    
    // 6. If redirected to login, login (though auth.setup should handle this if configured correctly)
    if (page.url().includes('/login')) {
        await page.locator('input.auth-input[placeholder*="Email"]').fill('randal.conroy@example.org');
        await page.locator('input.auth-input[placeholder="Mật khẩu"]').fill('password');
        await page.locator('button.auth-btn-login').click();
        await page.waitForURL('**/checkout');
    }

    // 7. Step 1: Shipping Info
    await page.locator('input[name="name"]').fill('E2E Test User');
    await page.locator('input[name="phone"]').fill('0912345678');
    await page.locator('textarea[name="address"]').fill('123 E2E Test Street');
    
    // Select Province/District/Ward (Handle custom dropdowns or selects)
    // Based on exploration, these might be custom. We'll try to click and select.
    // If they are <select>, we use .selectOption()
    const provinceSelect = page.locator('select[name="province_id"], select[name="province"], #province');
    if (await provinceSelect.count() > 0) {
        await provinceSelect.selectOption({ index: 1 });
        await page.waitForTimeout(500); // Wait for dependency fields to load
        await page.locator('select[name="district_id"], #district').selectOption({ index: 1 });
        await page.waitForTimeout(500);
        await page.locator('select[name="ward_id"], #ward').selectOption({ index: 1 });
    }

    // 8. Apply Voucher
    await page.locator('#couponCode').fill('WELCOME50');
    await page.locator('#applyCouponBtn').click();
    
    // Verify voucher feedback
    await expect(page.locator('.text-success, #coupon-feedback')).toBeVisible();

    // 9. Move to Step 2
    await page.locator('#btn-next-step-1').click();
    
    // 10. Step 2: Shipping & Payment
    // Select default shipping
    await page.locator('input#shipping_default, input[name="shipping_method"]').first().check();
    
    // Select COD payment
    await page.locator('input#payment_cod, input[value="COD"]').check();
    
    // 11. Complete Order
    await page.locator('#btn-next-step-2, button[type="submit"]').click();
    
    // 12. Verify Success Page
    await page.waitForURL('**/checkout/success/**');
    await expect(page.locator('h2, .success-title')).toContainText(['thành công', 'Success']);
  });
});
