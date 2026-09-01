# shurjoPay Sandbox Checklist

Before testing:
- Set sandbox credentials in .env
- Set SHURJOPAY_ENABLED=true
- Confirm the public callback URL is reachable over HTTPS
- Run php artisan config:clear

Test flow:
1. Create a booking in pending-payment status.
2. Open the booking payment page.
3. Select shurjoPay and enter customer details.
4. Confirm the gateway returns a checkout URL.
5. Complete the sandbox transaction using merchant-provided test instructions.
6. Confirm callback performs server-side verification.
7. Confirm only a verified successful payment changes the booking to confirmed.
8. Open the invoice and verify amount, dates and payment status.

Never mark a production payment successful from browser parameters alone.