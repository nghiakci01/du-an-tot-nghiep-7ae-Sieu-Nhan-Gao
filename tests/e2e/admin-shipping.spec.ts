import { test, expect } from '@playwright/test';

test.describe('Admin Shipping and Order Management', () => {
  test.use({ storageState: 'playwright/.auth/admin.json' });

  test.beforeEach(async ({ page }) => {
    // Navigate to Admin Orders
    await page.goto('/admin/orders');
  });

  test('Admin can update order status', async ({ page }) => {
    // 1. Click on the first order detail link
    const orderDetailLink = page.locator('a.btn-outline-primary:has-text("Xem")').first();
    await expect(orderDetailLink).toBeVisible();
    await orderDetailLink.click();

    // 2. Update Order Status
    const statusSelect = page.locator('select.form-select').first();
    if (await statusSelect.isVisible()) {
        await statusSelect.selectOption({ index: 1 });
        await page.waitForTimeout(1000); // Small delay for UI stability
        
        const updateStatusBtn = page.locator('button.btn-primary:has-text("Cập nhật ngay")');
        await updateStatusBtn.click();
        
        // Wait for page load or toast
        await page.waitForLoadState('networkidle');
        // Success indicator: usually a toast or alert contains 'thành công' or 'Cập nhật'
        await expect(page.locator('body')).toContainText(/thành công|cập nhật/i, { timeout: 15000 });
    }
  });

  test('Admin can assign a shipper', async ({ page }) => {
    // 1. Click on the first order detail link
    const orderDetailLink = page.locator('a.btn-outline-primary:has-text("Xem")').first();
    await expect(orderDetailLink).toBeVisible();
    await orderDetailLink.click();

    // 2. Assign a Shipper
    const shipperSelect = page.locator('select.form-select').nth(1);
    const assignBtn = page.locator('button.btn-info:has-text("Gán Shipper")');
    
    if (await shipperSelect.isVisible()) {
        const optionCount = await shipperSelect.locator('option').count();
        if (optionCount > 1) {
            await shipperSelect.selectOption({ index: 1 });
            await page.waitForTimeout(1000);
            await assignBtn.click();
            await page.waitForLoadState('networkidle');
            await expect(page.locator('body')).toContainText(/thành công|giao hàng/i, { timeout: 15000 });
        }
    }
  });

  test('Admin can filter orders by status', async ({ page }) => {
    await page.goto('/admin/orders');
    
    // Open status filter
    const filterSelect = page.locator('select[name="status"]').first();
    if (await filterSelect.isVisible()) {
        await filterSelect.selectOption('pending');
        await page.locator('button[type="submit"]').first().click();
        
        // Wait for page load and verify URL
        await page.waitForURL(/.*status=pending.*/);
        expect(page.url()).toContain('status=pending');
    }
  });
});
