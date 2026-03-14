# PLAN: IDM-VTON Full Implementation

This plan outlines the technical roadmap to transition the current prototype VTON (Virtual Try-On) into a robust, production-grade feature using the IDM-VTON model.

## 🔴 Socratic Gate (User Review Required)

Before proceeding with the implementation, please answer these critical questions:

1. **Usage Scale:** What is your expected daily volume? (e.g., 10 users/day vs 1,000 users/day)
2. **Budget:** Are you comfortable with a small recurring cost (e.g., $10-50/month) to guarantee 99% success rates, or should we stick to free (but unstable) resources?
3. **Speed Priority:** Is a 45-second wait (free) acceptable, or do you need a <10 second response (paid GPU)?
4. **Data Retention:** Should we store the generated images permanently for user history, or just temporarily?

---

## Proposed Phases

### Phase 1: Analysis & Infrastructure Prep [CURRENT]
- [x] Research IDM-VTON GitHub repository.
- [x] Identify API requirements (Gradio 4+, binary uploads).
- [ ] Select Infrastructure:
    - **Path A:** Replicate / Hugging Face Dedicated (Paid).
    - **Path B:** Custom RunPod/Lambda API wrapper (Semi-paid, High Performance).
    - **Path C:** Enhanced Smart-Mesh (Free, High Effort).

### Phase 2: Backend Architecture Refinement
- **Controller Logic:** Update `VtonController` to support provider-agnostic drivers (HuggingFaceProvider, ReplicateProvider, LocalProvider).
- **Security:** Implement signature verification for webhook callbacks (if using serverless).
- **Storage:** Use S3/Cloudinary for long-term storage of generated try-on history.

### Phase 3: High-Fidelity UI/UX
- **Interactive Masking:** Research adding a simple canvas editor for users to refine the garment mask (optional but improves quality).
- **Live Preview:** Show "Step 1: Analyzing body", "Step 2: Fitting garment", "Step 3: Rendering".
- **Social Sharing:** Add "Share look" buttons for one-click social media posting.

### Phase 4: Implementation & Stress Testing
- Implementation of the chosen infrastructure path.
- Automated tests for payload validation and error recovery.
- Load testing to ensure Laravel queues don't bottleneck if 50 people try on clothes at once.

---

## Verification Plan

### Automated Tests
- `php artisan test --filter VtonApiTest` (Mocking external AI responses).
- E2E testing with Playwright to verify the polling state machine.

### Manual Verification
- Testing with high-resolution "wild" photos (dim lighting, complex backgrounds).
- Testing on mobile devices (iPhone/Android) for responsive editor UI.
