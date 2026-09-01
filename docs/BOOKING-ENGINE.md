# Booking Engine Foundation

## Flow
Search availability → select room → submit guest details → transactional booking.

## Oversell protection
Booking creation locks the room and all daily availability rows inside a database transaction before inventory is decremented.

## Current status
Bookings are created as `pending_payment`.

## Next
Add booking expiry/hold release, payment confirmation, cancellation inventory restoration, booking guests, and notification jobs.
