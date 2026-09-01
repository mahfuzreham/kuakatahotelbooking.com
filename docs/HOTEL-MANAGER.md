# Hotel Manager & Staff Operations

## Dashboard capabilities
- property-scoped booking list
- limited guest operational information
- room inventory and room status
- check-in and check-out workflow

## Access levels
Roles should be enforced with policies/middleware:
- Hotel Manager: full property operations
- Reception: bookings and check-in/out
- Housekeeping: room status only

Guest contact details should not be returned to staff APIs by default. Phone sharing requires an explicit authorized operational policy.