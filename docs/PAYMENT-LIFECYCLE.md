# Payment & Booking Lifecycle

pending_payment → confirmed → checked_in → completed

Alternative terminal states:
- cancelled
- expired
- refunded

## Important production requirement
A payment confirmation endpoint must never trust arbitrary browser requests. Real gateways must verify webhook signatures, amounts, currency, replay protection and idempotency.

## Next
Implement booking-item creation atomically, payment expiry release, cancellation policy and refund workflow.
