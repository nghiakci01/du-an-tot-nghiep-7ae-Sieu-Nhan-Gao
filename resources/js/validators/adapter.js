import messages from './messages';

function normalizePath(path) {
  if (!path) return '';
  // convert variants[0].size_id -> variants[].size_id
  return path.replace(/\[\d+\]/g, '[]');
}

function pickMessage(schemaName, path, type) {
  const schemaMsgs = messages[schemaName] || {};
  const norm = normalizePath(path);
  // try exact
  if (schemaMsgs[norm] && schemaMsgs[norm][type]) return schemaMsgs[norm][type];
  // try field-level generic keys (e.g., name.required)
  const field = norm.split('.')[0];
  if (schemaMsgs[field] && schemaMsgs[field][type]) return schemaMsgs[field][type];
  // try fallback by type
  if (schemaMsgs[field] && schemaMsgs[field].invalid) return schemaMsgs[field].invalid;
  return null;
}

export function mapYupError(schemaName, yupError) {
  // yupError: ValidationError from Yup
  const out = {};
  if (!yupError || !yupError.inner || !Array.isArray(yupError.inner)) {
    return out;
  }
  yupError.inner.forEach(err => {
    const path = err.path || '';
    const norm = normalizePath(path);
    // infer type from message or err.type
    // Yup doesn't always expose a stable type; use message heuristics
    let type = 'invalid';
    if (/required/i.test(err.message)) type = 'required';
    else if (/match/i.test(err.message) || /confirm/i.test(err.message)) type = 'match';
    else if (/one of/i.test(err.message) || /must be one of/i.test(err.message)) type = 'oneOf';
    else if (/must be a/i.test(err.message) && /integer/i.test(err.message)) type = 'integer';
    else if (/max/i.test(err.message)) type = 'max';
    else if (/min/i.test(err.message)) type = 'min';

    const custom = pickMessage(schemaName, norm, type) || pickMessage(schemaName, norm.split('.')[0], type);
    out[path] = custom || err.message;
  });
  return out;
}

export default { mapYupError };
