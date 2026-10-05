# Migration Plan: Ticket System Database Schema

## Goal
Create 13 CodeIgniter 4 migrations for the ticket sales and gate check-in system, one migration per table in dependency order.

## Current Context / Assumptions
- CodeIgniter 4.7 project at `C:\laragon\www\nextix\web-tix`
- Migrations enabled, timestamp format: `Y-m-d-His_`
- Empty migrations directory: `app/Database/Migrations/`
- Enum types will be VARCHAR columns (manual conversion to ENUM in phpMyAdmin later)
- Foreign key constraints disabled (app-layer validation only)
- DECIMAL precision: (12,2) for money, (5,2) for percentages
- `checkin_history` uses BIGINT primary key; all others use INT
- Full rollback methods required for all migrations

## Architecture / Proposed Approach
Generate migrations using `php spark make:migration <name>` command, then edit each file to add table definitions using CodeIgniter's DBForge. Create tables in dependency order: independent tables first (users, gate_users, ticket_types, checkpoints), then tables with foreign references (customers, transactions, tickets, etc). Each migration has complete `up()` and `down()` methods.

## Step-by-Step Tasks

### Task 1: Create users migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateUsersTable.php`

**Command**:
```bash
php spark make:migration CreateUsersTable
```

**Expected output**:
```
File created: APPPATH/Database/Migrations/2026-10-05-055800_CreateUsersTable.php
```

**Edit the generated file** with this complete code:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'admin',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
```

**Verify**:
```bash
php spark migrate:status
```
Expected: Migration file listed as pending.

---

### Task 2: Create gate_users migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateGateUsersTable.php`

**Command**:
```bash
php spark make:migration CreateGateUsersTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gate_users');
    }

    public function down()
    {
        $this->forge->dropTable('gate_users');
    }
}
```

---

### Task 3: Create customers migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateCustomersTable.php`

**Command**:
```bash
php spark make:migration CreateCustomersTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => true,
            ],
            'origin_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'domestic, international',
            ],
            'origin' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'anonymized_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('phone');
        $this->forge->addKey('name');
        $this->forge->createTable('customers');
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
```

---

### Task 4: Create ticket_types migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateTicketTypesTable.php`

**Command**:
```bash
php spark make:migration CreateTicketTypesTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketTypesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'discount_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ticket_types');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_types');
    }
}
```

---

### Task 5: Create checkpoints migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateCheckpointsTable.php`

**Command**:
```bash
php spark make:migration CreateCheckpointsTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCheckpointsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('checkpoints');
    }

    public function down()
    {
        $this->forge->dropTable('checkpoints');
    }
}
```

---

### Task 6: Create transactions migration (3 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateTransactionsTable.php`

**Command**:
```bash
php spark make:migration CreateTransactionsTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'invoice_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'cash, debit',
            ],
            'payment_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'cash_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'cash_change' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'completed',
                'comment'    => 'completed, cancelled',
            ],
            'voided_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'voided_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'void_reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('customer_id');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['created_by', 'created_at']);
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
```

---

### Task 7: Create tickets migration (3 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateTicketsTable.php`

**Command**:
```bash
php spark make:migration CreateTicketsTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'ticket_code' => [
                'type'       => 'CHAR',
                'constraint' => 23,
                'unique'     => true,
            ],
            'ticket_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'individual, group',
            ],
            'valid_date' => [
                'type' => 'DATE',
            ],
            'total_visitors' => [
                'type'    => 'INT',
                'default' => 1,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
                'comment'    => 'active, used, cancelled',
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'used_by_gate_user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'used_checkpoint_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('valid_date');
        $this->forge->addKey('transaction_id');
        $this->forge->addKey('status');
        $this->forge->createTable('tickets');
    }

    public function down()
    {
        $this->forge->dropTable('tickets');
    }
}
```

---

### Task 8: Create ticket_details migration (3 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateTicketDetailsTable.php`

**Command**:
```bash
php spark make:migration CreateTicketDetailsTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'ticket_type_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'qty' => [
                'type' => 'INT',
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'discount_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00',
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['ticket_id', 'ticket_type_id']);
        $this->forge->createTable('ticket_details');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_details');
    }
}
```

---

### Task 9: Create checkin_history migration (3 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateCheckinHistoryTable.php`

**Command**:
```bash
php spark make:migration CreateCheckinHistoryTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCheckinHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'scanned_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'checkpoint_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'gate_user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'visitor_count' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'result' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'VALID, ALREADY_USED, EXPIRED, NOT_FOUND, CANCELLED',
            ],
            'scanned_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('scanned_at');
        $this->forge->addKey(['gate_user_id', 'scanned_at']);
        $this->forge->addKey(['result', 'scanned_at']);
        $this->forge->createTable('checkin_history');
    }

    public function down()
    {
        $this->forge->dropTable('checkin_history');
    }
}
```

---

### Task 10: Create gate_api_tokens migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateGateApiTokensTable.php`

**Command**:
```bash
php spark make:migration CreateGateApiTokensTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGateApiTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'gate_user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'token_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'unique'     => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gate_api_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('gate_api_tokens');
    }
}
```

---

### Task 11: Create print_log migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreatePrintLogTable.php`

**Command**:
```bash
php spark make:migration CreatePrintLogTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrintLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'printed_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'print_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'initial, reprint',
            ],
            'printed_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('print_log');
    }

    public function down()
    {
        $this->forge->dropTable('print_log');
    }
}
```

---

### Task 12: Create settings migration (2 min)
**File**: `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateSettingsTable.php`

**Command**:
```bash
php spark make:migration CreateSettingsTable
```

**Edit the generated file**:
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'setting_value' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('setting_key', true);
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
```

---

### Task 13: Run and verify all migrations (3 min)

**Check migration status**:
```bash
php spark migrate:status
```
Expected output: 13 migrations listed, all pending.

**Run migrations**:
```bash
php spark migrate
```
Expected output:
```
Running: 2026-10-05-055800_CreateUsersTable
Migrated: 2026-10-05-055800_CreateUsersTable
Running: 2026-10-05-055801_CreateGateUsersTable
Migrated: 2026-10-05-055801_CreateGateUsersTable
...
(13 migrations total)
Done
```

**Verify tables exist**:
```bash
php spark db:table --show
```
Expected: 13 tables listed (users, gate_users, customers, ticket_types, checkpoints, transactions, tickets, ticket_details, checkin_history, gate_api_tokens, print_log, settings, migrations).

**Test rollback on last migration**:
```bash
php spark migrate:rollback
```
Expected: Last table dropped.

**Re-run migration**:
```bash
php spark migrate
```
Expected: Last table recreated.

**Commit**:
```bash
git add app/Database/Migrations/
git commit -m "Add 13 database migrations for ticket system"
```

---

## Tests / Validation

Since migrations are schema definitions without business logic, testing follows this pattern per task:

1. **Generate migration** — verify file created in correct location
2. **Check syntax** — run `php spark migrate:status` to confirm parser reads file
3. **Test up()** — run `php spark migrate` for that batch, verify table exists via `SHOW TABLES` or db:table
4. **Test down()** — run `php spark migrate:rollback`, verify table dropped
5. **Re-run up()** — run `php spark migrate` again, verify table recreated
6. **Commit** — after each successful migration pair

No unit tests required for schema migrations. Validation is structural: does the table exist with correct columns/types/indexes after up(), and does it disappear after down().

Final validation after Task 13: run full migrate → rollback → migrate cycle to confirm all 13 migrations work bidirectionally.

---

## Risks, Tradeoffs, and Open Questions

**Risks**:
- **Enum as VARCHAR**: App-layer validation required. No database-level constraint prevents invalid enum values. Manual phpMyAdmin conversion to ENUM later may require downtime.
- **No FK constraints**: Orphan records possible (ticket without transaction, etc). App must enforce referential integrity. Debugging harder without FK violation errors.
- **Timestamp defaults**: CodeIgniter auto-timestamps require entity model or manual setting in insert/update. Schema has no `DEFAULT CURRENT_TIMESTAMP` on created_at — app must provide value.
- **Composite indexes**: Multi-column indexes (created_by + created_at, gate_user_id + scanned_at) may need prefix length tuning for large datasets.

**Tradeoffs**:
- **One migration per table** (vs one migration for all tables): Easier rollback granularity, clearer git history, but more files to manage.
- **No seeder data**: Migrations create empty tables. Ticket types, checkpoints, default admin user must be seeded separately.
- **BIGINT only for checkin_history**: Assumes high scan volume. If other tables (tickets, transactions) hit INT limit (2B rows), migration required.

**Open Questions**:
- **Database collation**: Schema doesn't specify charset/collation. CI4 default is utf8mb4. Confirm acceptable for international customer names.
- **Index prefix lengths**: VARCHAR indexes on phone (25), name (100) may need prefix length optimization depending on MySQL version and row format.
- **Timestamp precision**: DATETIME has second precision. Millisecond precision (DATETIME(3)) needed for checkin_history race conditions?
- **Updated_at auto-update**: Schema shows `updated_at` but no `ON UPDATE CURRENT_TIMESTAMP`. Rely on CI4 model magic or add trigger?
- **Soft deletes**: No deleted_at columns. If soft delete pattern needed later, requires schema migration.

**Post-migration manual steps**:
1. Convert VARCHAR enum columns to ENUM type in phpMyAdmin (13 columns across 8 tables)
2. Verify index cardinality after seeding data
3. Run EXPLAIN on common queries (transaction by date, ticket lookup, checkin history) to confirm index usage
4. Add triggers for updated_at auto-update if not using CI4 entities
