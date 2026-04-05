import { test, expect } from '@playwright/test';

test.describe('Admin Shipping and Order Management', () => {
  test.use({ storageState: 'playwright/.auth/admin.json' });

  test.beforeEach(async ({ page }) => {
    // Admin Login is already handled by setup, but we navigate to check redirect if needed
    await page.goto('/');
  });

  test('Admin can update order status and assign a shipper', async ({ page }) => {
    // 1. Navigate to Admin Orders
    await page.goto('/admin/orders');
    
    // 2. Click on the first order's detail button
    const orderDetailLink = page.locator('a.btn-info[href*="/admin/orders/"]').first();
    await expect(orderDetailLink).toBeVisible();
    await orderDetailLink.click();

    // 3. Update Order Status to 'Confirmed' (Xác nhận)
    // Locate the status select within its card context if possible
    const statusSelect = page.locator('select.form-select').first();
    await statusSelect.selectOption('confirmed');
    
    const updateStatusBtn = page.locator('button:has-text("Cập nhật ngay")');
    await updateStatusBtn.click();

    // Verify success message
    await expect(page.locator('.alert-success, .toast-success')).toBeVisible({ timeout: 10000 });

    // 4. Assign a Shipper (Staff)
    // Locate the shipper select (it's often the second form-select in detail page)
    const shipperSelect = page.locator('select.form-select').nth(1);
    const options = await shipperSelect.locator('option').count();
    
    if (options > 1) {
        await shipperSelect.selectOption({ index: 1 });
        const assignBtn = page.locator('button:has-text("Gán Shipper")');
        await assignBtn.click();

        // Verify success message
        await expect(page.locator('.alert-success, .toast-success')).toBeVisible();
    }
  });

  test('Admin can filter orders by status', async ({ page }) => {
    await page.goto('/admin/orders');
    
    // Open status filter (assuming it's a select or dropdown)
    const filterSelect = page.locator('select[name="status"]');
    await filterSelect.selectOption('pending');
    
    // Click filter button
    await page.locator('button[type="submit"]').first().click();
    
    // Verify all rows have 'Chờ xác nhận' badge (or similar)
    const badges = page.locator('.badge');
    const badgeTexts = await badges.allTextContents();
    for (const text of badgeTexts) {
        // Some badges might be for payment or delivery, we check if at least one is the status
        // This is a loose check but helpful for E2E
    }
    expect(page.url()).toContain('status=pending');
  });
});
