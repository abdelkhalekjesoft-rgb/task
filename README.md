## Foodics Pay Coding Challenge – Laravel Implementation

### Overview
This project is a Laravel-based solution for the **Foodics Pay Coding Challenge**. It demonstrates:
- Webhook ingestion from multiple bank formats
- Idempotent transaction storage
- High‑performance processing (1,000+ transactions per webhook)
- XML transfer request generation according to business rules
- Clean architecture using Controllers, Services, Jobs, and Tests

---

## Architecture

```
app/
 ├── Http/Controllers
 │   ├── WebhookController.php
 ├── Services
 │   ├── Webhooks
 │   │   ├── BankParser.php
 │   │   ├── PaymentService.php
 │   │   └── Parsers
 │   │       ├── FoodicsBankParser.php
 │   │       └── AcmeBankParser.php
 └── Models
     └── Transaction.php
     └── WebHook.php

```

---

## API Endpoints

### 1. Bank Webhook (Receive Transactions)
```
POST /api/webhooks/{bank}
Content-Type: text/plain
```

**Description**
- Accepts raw text payload
- Each line represents a transaction
- Supports multiple bank formats
- Returns `200 OK` immediately
- Processing is asynchronous

**Example Payload**
```
2025-06-15#100.00##REF123##note/test
REF999,2025-06-15,50.00
```
---

## Database Design

### transactions table

| Column     | Type           | Description |
|-----------|---------------|-------------|
| id        | bigint        | Primary key |
| reference | string (unique)| Idempotency key |
| amount    | decimal(10,2) | Transaction amount |
| date      | datetime      | Transaction date |
| notes      | json (nullable)| Extra metadata |
| created_at| timestamp     | Laravel timestamp |
| updated_at| timestamp     | Laravel timestamp |

---

## Idempotency

- Each transaction has a unique `reference`
- Duplicate webhooks do not create duplicate records
- Implemented using `firstOrCreate`

---

## Performance Considerations

- Webhook processing uses line‑by‑line parsing
- Designed to handle large payloads (1,000+ transactions)
- Performance test included to validate acceptable execution time

---

## Testing

### Run tests
```bash
php artisan test
```

### Test Coverage
- Feature tests for API endpoints
- Unit tests for webhook ingestion service
- Performance test for large webhook payloads

---

## Key Design Decisions

- **Thin Controllers**: No business logic
- **Service Layer**: Parsing & XML generation
- **Jobs**: Async webhook processing
- **PSR‑4 Compliance**: Clean autoloading
- **Laravel Best Practices**

---

## Interview Notes

This project demonstrates:
- System design thinking
- Performance awareness
- Clean separation of concerns
- Real‑world webhook handling

---

## Author

Developed as part of the Foodics Pay Coding Challenge using Laravel.

