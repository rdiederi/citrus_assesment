# PostgreSQL Database Design for Citruse

## Table: users

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| name | VARCHAR |
| email | VARCHAR UNIQUE |
| password | VARCHAR |
| role | VARCHAR CHECK (role IN ('admin', 'manager', 'sales')) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |


## Table: partners

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| type | VARCHAR CHECK (type IN ('supplier', 'distributor')) |
| business_name | VARCHAR |
| address | TEXT |
| country | VARCHAR |
| vat_number | VARCHAR |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |


## Table: partner_contacts

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| partner_id | UUID REFERENCES partners(id) |
| type | VARCHAR CHECK (type IN ('sales', 'logistics')) |
| name | VARCHAR |
| email | VARCHAR |
| phone | VARCHAR |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |


## Table: products

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| name | VARCHAR |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |


## Table: product_prices

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| product_id | UUID REFERENCES products(id) |
| year | INTEGER |
| price | NUMERIC(10,2) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |


## Table: purchase_orders

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| po_number | VARCHAR |
| type | VARCHAR CHECK (type IN ('POD', 'POS')) |
| status | VARCHAR CHECK (status IN ('New', 'Accepted', 'In Delivery', 'Delivered', 'Rejected', 'Cancelled')) |
| reference_id | UUID |
| created_by | UUID REFERENCES users(id) |
| linked_po_id | UUID REFERENCES purchase_orders(id) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |


## Table: order_line_items

| Field | Type |
|-------|------|
| id | UUID PRIMARY KEY |
| purchase_order_id | UUID REFERENCES purchase_orders(id) |
| product_id | UUID REFERENCES products(id) |
| quantity | INTEGER |
| delivery_date | DATE |
| total | NUMERIC(10,2) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

