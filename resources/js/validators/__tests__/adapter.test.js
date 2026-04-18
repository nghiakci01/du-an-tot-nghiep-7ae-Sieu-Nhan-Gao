import { describe, it, expect } from 'vitest';
import { mapYupError } from '../adapter.js';

describe('adapter.mapYupError', () => {
  it('maps required email error to Vietnamese message for StoreUser', () => {
    const err = { inner: [{ path: 'email', message: 'email is a required field' }] };
    const out = mapYupError('StoreUser', err);
    expect(out).toEqual({ email: 'Vui lòng nhập email.' });
  });

  it('maps array path to variants[].size_id message for StoreProduct', () => {
    const err = { inner: [{ path: 'variants[0].size_id', message: 'size_id is a required field' }] };
    const out = mapYupError('StoreProduct', err);
    expect(out).toEqual({ 'variants[0].size_id': 'Vui lòng chọn kích thước.' });
  });
});
