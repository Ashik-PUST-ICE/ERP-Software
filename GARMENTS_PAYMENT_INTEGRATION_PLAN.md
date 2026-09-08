# Garments ERP Payment Integration Plan

## Current finding

The application already has a payment system in the Super Admin and subscription
billing areas. A second independent gateway/payment implementation should not be
created for the Garments ERP.

Existing payment flow:

- `App\Models\Transaction` stores user subscription transactions.
- `App\Models\Payment` stores payment records and gateway information.
- `App\Http\Services\Payment\Payment` selects the configured gateway service.
- Gateway services already exist for Stripe, PayPal, Razorpay, SSLCommerz,
  Paystack, Mollie, Flutterwave, Instamojo, Mercado Pago, and bank payments.
- `BillingService` handles subscription cancellation and transaction history.
- Super Admin gateway configuration is available through
  `routes/super_admin.php`.
- Super Admin subscription orders can be reviewed and their payment status can
  be changed through `SubscriptionController`.

Relevant existing files:

- `app/Models/Transaction.php`
- `app/Models/Payment.php`
- `app/Http/Services/Payment/Payment.php`
- `app/Http/Services/Admin/BillingService.php`
- `app/Http/Controllers/AutoPost/SuperAdmin/SubscriptionController.php`
- `routes/super_admin.php`
- `routes/admin.php`

## Garments payment feature already added

The Garments module currently has its own operational invoice collection
records:

- `garment_invoices`
- `garment_invoice_payments`
- Commercial invoice creation and printing
- Payment date, amount, method, and reference
- Outstanding balance validation
- Automatic `partially_paid` and `paid` status updates
- AP/AR summary page

These records are for buyer/order receivables and should remain separate from
subscription billing transactions.

## Recommended integration approach

### Phase 1: Keep payment domains separate

Do not connect Garments buyer invoices directly to `transactions`, because that
table is currently designed around authenticated users, subscriptions, and
gateway payment records.

Continue using:

- `garment_invoices` for export/order invoices
- `garment_invoice_payments` for manual or bank/L/C collection entries
- Existing `Payment` and `Transaction` models for SaaS subscription billing

### Phase 2: Add gateway references to Garments payments

When online buyer payment is required, add nullable fields to
`garment_invoice_payments`:

- `gateway`
- `gateway_payment_id`
- `gateway_transaction_id`
- `gateway_status`
- `gateway_response` (JSON)

The payment record should still belong to a Garments invoice, while the
existing `App\Http\Services\Payment\Payment` class should be reused for
gateway communication.

### Phase 3: Add gateway checkout

Implement a dedicated Garments payment service that:

1. Loads the invoice and calculates the outstanding amount.
2. Validates that the requested payment does not exceed the outstanding amount.
3. Creates a pending `garment_invoice_payments` record.
4. Calls the existing configured gateway adapter.
5. Redirects the payer to the gateway checkout.
6. Confirms the gateway callback/webhook.
7. Marks the payment as successful or failed.
8. Updates invoice `paid_amount` and `status` inside a database transaction.

The existing gateway adapters must not be modified until their callback
contracts are reviewed.

### Phase 4: Reconciliation and audit

Add:

- Payment failure and reversal handling
- Idempotent callback processing
- Gateway response logging without storing secrets
- Payment receipt printing
- Refund/void workflow
- Audit log entries for create, confirm, fail, refund, and reverse events
- Currency consistency validation

## Important safeguards

- Never trust a client-provided paid amount when recalculating invoice status.
- Never process the same gateway callback twice.
- Never allow payment above the invoice outstanding balance.
- Do not store card numbers, CVV, gateway secrets, or access tokens.
- Use database locks/transactions while confirming payments.
- Keep manual collection and online gateway payments distinguishable.
- Use a unique gateway transaction ID to prevent duplicate collection.
- Verify webhook signatures using each gateway's official mechanism.

## Error-fixing order before implementation

1. Verify current Garments invoice/payment Blade pages.
2. Confirm existing Super Admin gateway configuration works in the current
   environment.
3. Confirm the payment callback routes for each selected gateway.
4. Add automated coverage for invoice balance and duplicate callback handling.
5. Implement one gateway first, preferably the gateway already configured for
   the deployment.
6. Add the remaining gateways only after the first integration is stable.

## Definition of done

- Existing Super Admin subscription payment remains unchanged.
- Garments invoice payments can be manual or gateway-based.
- Successful gateway payments update the invoice exactly once.
- Failed, cancelled, refunded, and reversed payments are represented clearly.
- AP/AR totals include only successful Garments invoice collections.
- All payment actions are auditable and do not expose sensitive payment data.
