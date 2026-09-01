# Property & Inventory

Vendor ownership is checked server-side for every write operation.

## Availability
Each room type has a daily availability record containing:
- available inventory
- price override
- closed/open state
- minimum stay

Bulk updates are limited to 366 days per request.

## Next hardening
Booking creation must use database transactions and row-level locking to prevent overselling.
