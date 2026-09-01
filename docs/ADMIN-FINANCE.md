# Admin Finance Control

Admin finance APIs cover commission rules and payout processing.

Payout modes:
- manual: authorized admin records completion
- automatic: future provider adapter submits and reconciles transfer

Rejecting a payout returns the reserved amount to the vendor's available balance.

Production hardening requires role policies, immutable ledger entries, approval limits and reconciliation jobs.