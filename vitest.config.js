import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    include: ['resources/js/**/*.test.{js,ts}', 'resources/js/**/__tests__/**/*.{js,ts}']
  }
});
