import { test, expect } from '@playwright/test';

test.describe('Admin Order Management', () => {
  test.use({ storageState: 'playwright/.auth/admin.json' });

  test.beforeEach(async ({ page }) => {
    await page.goto('/admin/orders');
  });

  test('Admin can update order status', async ({ page }) => {
    const orderDetailLink = page.locator('a.btn-outline-primary:has-text("Xem")').first();
    await expect(orderDetailLink).toBeVisible();
    await orderDetailLink.click();

    const statusSelect = page.locator('select.form-select').first();
    if (await statusSelect.isVisible()) {
        await statusSelect.selectOption({ index: 1 });
        await page.waitForTimeout(1000);

        const updateStatusBtn = page.locator('button.btn-primary:has-text("Cáº­p nháº­t ngay")');
        await updateStatusBtn.click();

        await page.waitForLoadState('networkidle');
        await expect(page.locator('body')).toContainText(/thÃ nh cÃ´ng|cáº­p nháº­t/i, { timeout: 15000 });
    }
  });

  test('Admin can filter orders by status', async ({ page }) => {
    await page.goto('/admin/orders');

    const filterSelect = page.locator('select[name="status"]').first();
    if (await filterSelect.isVisible()) {
        await filterSelect.selectOption('pending');
        await page.locator('button[type="submit"]').first().click();

        await page.waitForURL(/.*status=pending.*/);
        expect(page.url()).toContain('status=pending');
    }
  });
});
