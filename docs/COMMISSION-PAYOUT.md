# Commission, Wallet & Payout

Flow: paid booking → commission calculation → vendor pending balance → available balance → payout request → processed payout.

Commission rules support global defaults and future scoped overrides using scope_type, scope_id and priority.

Production requirements: immutable financial ledger, idempotency, reconciliation and gateway/bank payout adapters.