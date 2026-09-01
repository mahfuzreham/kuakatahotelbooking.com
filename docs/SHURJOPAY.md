# shurjoPay Integration

This project uses the official REST flow:
1. Authenticate with `/get_token`
2. Initiate checkout with `/secret-pay`
3. Redirect the customer to the gateway checkout URL
4. On callback, verify server-to-server with `/verification`
5. Only mark the booking paid after successful verification and amount matching

## Environment
```
SHURJOPAY_ENABLED=true
SHURJOPAY_SANDBOX=true
SHURJOPAY_USERNAME=your_sandbox_or_live_username
SHURJOPAY_PASSWORD=your_sandbox_or_live_password
SHURJOPAY_PREFIX=KHB
SHURJOPAY_CURRENCY=BDT
# Sandbox: https://sandbox.shurjopayment.com/api
# Live: https://engine.shurjopayment.com/api
SHURJOPAY_BASE_URL=https://sandbox.shurjopayment.com/api
```

Do not commit live credentials. Switch sandbox=false and use merchant-issued live credentials only after onboarding.

Reference: official shurjoPay REST documentation and integration guide.