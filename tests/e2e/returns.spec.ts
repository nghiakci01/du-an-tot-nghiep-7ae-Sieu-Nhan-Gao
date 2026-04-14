import { test, expect } from '@playwright/test';

test.describe('Return Flow', () => {
  // Reuse authenticated state
  test.use({ storageState: 'playwright/.auth/user.json' });

  test('User can submit a return request for a completed order', async ({ page }) => {
    // 1. Go to My Account
    await page.goto('/my-account');
    
    // 2. Locate the orders table and find a 'Hoàn thành' (Completed) order
    // Looking for a link that leads to order details
    const orderDetailsLink = page.locator('a[href*="/my-account/orders/"]').first();
    await orderDetailsLink.click();

    // 3. Check for the "Yêu cầu trả hàng" (Request Return) button
    // It only appears if the order is completed and no return exists yet
    const returnBtn = page.locator('a:has-text("Yêu cầu trả hàng"), a[href*="/return"]').first();
    
    // If the button is not visible, it might be because the order seeded doesn't meet criteria
    // but our seeding script should have ensured it.
    await expect(returnBtn).toBeVisible({ timeout: 10000 });
    await returnBtn.click();

    // 4. Fill the return request form
    // Select reason
    const reasonSelect = page.locator('select[name="reason"], #reason');
    if (await reasonSelect.count() > 0) {
        await reasonSelect.selectOption({ index: 1 });
    } else {
        // Handle custom dropdown if necessary
        await page.locator('.select2-selection, .custom-select').first().click();
        await page.locator('.select2-results__option, .dropdown-item').first().click();
    }

    await page.locator('textarea[name="note"]').fill('E2E Test: Hàng bị lỗi kỹ thuật.');

    // Select the first item in the order to return
    const itemCheckbox = page.locator('input[type="checkbox"][name*="items"]').first();
    await itemCheckbox.check();

    // Fill mandatory bank information
    await page.locator('input[name="bank_name"]').fill('Vietcombank');
    await page.locator('input[name="bank_bin"]').fill('970436');
    await page.locator('input[name="account_number"]').fill('1234567890');
    await page.locator('input[name="account_name"]').fill('E2E TEST USER');

    // 5. Submit the form
    await page.locator('button[type="submit"], #btn-submit-return').click();

    // 6. Verify successful submission and redirect
    await expect(page.locator('.alert-success, .toast-success')).toBeVisible();
    await expect(page.locator('.alert-success, .toast-success')).toContainText(['thành công', 'đã được gửi']);
  });
});
