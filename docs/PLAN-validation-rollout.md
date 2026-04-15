# Plan: Rollout, monitoring, and feature flags for validation changes

1. Staged rollout
- Create a feature branch containing FormRequest migrations + frontend validators.
- Open a PR with clear description and changelog; request backend and frontend reviewers.
- Deploy to staging behind a feature flag (e.g., `validation_v2_enabled`).

2. Monitoring
- Add request-level logging for validation failures (structured logs with endpoint, user, payload hash, errors).
- Add an alert rule: if validation failure rate increases > 5% above baseline for 10 minutes, open an incident.
- Capture sample failed payloads to S3 (redact PII) for triage.

3. Feature flags
- Use a toggle at middleware level to enable new stricter validation per-route.
- Start with read-only mode (log-only) for 24-48 hours, then enforce.

4. Database migration & backward-compatibility
- Avoid immediate schema tightening; instead add DB constraints in a follow-up migration after monitoring.
- Coordinate with frontend team to ensure clients are using updated validators.

5. Testing & CI
- Run unit tests (PHPUnit), JS unit tests (Vitest), and Playwright E2E in CI.
- Configure CI to run Playwright separately from Vitest.

6. Rollback
- Feature flag off = immediate rollback path.

7. Communication
- Document changes in `docs/validation/` and notify teams with migration guide.
