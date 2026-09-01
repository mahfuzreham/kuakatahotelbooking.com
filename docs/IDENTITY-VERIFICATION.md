# Identity Verification

Supports NID, passport and other identity documents.

Modes:
- Manual admin review
- External provider adapter (future)

Sensitive document payloads are encrypted at rest and hidden from normal API serialization.

Production requirements:
- explicit user consent and privacy notice
- least-privilege admin access
- retention/deletion policy
- provider-specific legal and security review
- never expose full identity numbers to hotel managers by default