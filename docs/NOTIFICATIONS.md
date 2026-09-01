# Notifications

Channels: Email and SMS. Delivery runs through queued jobs with retries and persistent logs.

Templates use placeholders such as {{booking_number}} and {{hotel_name}}.

Staff notification rules must be permission-aware. Do not expose guest phone numbers to hotel staff unless their authorized role and operational need explicitly permit it.

Next: add provider adapters, template seeders, webhook delivery status and notification preferences.