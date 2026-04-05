import { test as setup, expect } from '@playwright/test';

const authFile = 'playwright/.auth/user.json';

setup('authenticate as user', async ({ page }) => {
  await page.goto('/login');

  await page.locator('input.auth-input[placeholder*="Email"], input.auth-input[placeholder*="người dùng"]').first().fill('randal.conroy@example.org');
  await page.locator('input.auth-input[placeholder*="khẩu"]').first().fill('password');
  await page.locator('button.auth-btn-login').click();

  // Wait for the account link to be visible instead of waiting for a specific URL
  // If an error modal appears (SweetAlert2), we click 'OK'
  const okBtn = page.locator('.swal2-confirm, button:has-text("OK")');
  if (await okBtn.isVisible({ timeout: 5000 })) {
    await okBtn.click();
  }

  // Verify login success - filter for the visible account link
  await expect(page.locator('a.user-account-link').filter({ visible: true }).first()).toBeVisible({ timeout: 20000 });

  await page.context().storageState({ path: authFile });
});

setup('authenticate as admin', async ({ page }) => {
  const adminAuthFile = 'playwright/.auth/admin.json';
  await page.goto('/login');

  await page.locator('input.auth-input[placeholder*="Email"], input.auth-input[placeholder*="người dùng"]').first().fill('admin@gmail.com');
  await page.locator('input.auth-input[placeholder*="khẩu"]').first().fill('password');
  
  // Use Promise.all to handle navigation after click better
  await page.locator('button.auth-btn-login').click();
  await page.waitForLoadState('networkidle');

  // Verify login success
  if (page.url().includes('/admin')) {
    await expect(page).toHaveURL(/.*\/admin.*/);
  } else {
    await expect(page.locator('a.user-account-link').filter({ visible: true }).first()).toBeVisible({ timeout: 20000 });
  }

  await page.context().storageState({ path: adminAuthFile });
});







