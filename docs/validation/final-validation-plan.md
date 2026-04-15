# Final Validation Plan & Artifacts

Summary:
- Backend: moved inline `$request->validate()` into `FormRequest` classes under `app/Http/Requests/Generated/`.
- Frontend: generated Yup validators in `resources/js/validators/` and messages in `resources/js/validators/messages.js`.
- Adapter: `resources/js/validators/adapter.js` maps Yup errors to localized messages.
- OpenAPI fragments: `docs/openapi/validation-components.json` contains components/schemas for the main input shapes.
- Tests: JS unit tests for adapter in `resources/js/validators/__tests__/adapter.test.js`; PHPUnit skeleton tests under `tests/Unit/Validation`.

Files created/updated:
- app/Http/Requests/Generated/* (many FormRequest files)
- resources/js/validators/* (Yup schemas, messages.js, adapter.js, index.js)
- docs/openapi/validation-components.json
- vitest.config.js (limit test discovery)
- tests/Unit/Validation/ReviewRequestTest.php
- docs/PLAN-validation-rollout.md
- docs/validation/validation-plan.md (earlier)

Next steps:
- Run full PHPUnit suite and fix any failures introduced by signature changes.
- Run Playwright E2E in CI (`npx playwright test`). Ensure CI separates Playwright from Vitest.
- Create PR and run staged rollout behind feature flag.
