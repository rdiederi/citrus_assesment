# Citruse Technical Assessment

## Overview
This repository contains a scaffolded Laravel + Vue.js + PostgreSQL application for **Citruse**, a fictional citrus shipping business operating across Africa.
The project demonstrates practical database modeling, backend architecture, and frontend integration for managing supplier/distributor orders.

## Features
- Role-based user system: `admin`, `manager`, `sales`
- Partner entity for suppliers and distributors
- Contact types for logistics and sales per partner
- Product catalog with annual price tracking (2023–2025)
- Dual Purchase Order system: POD (from distributor) and POS (to supplier)
- Order statuses with full lifecycle: New → Delivered

## Tech Stack
- Laravel (latest version via Sail)
- Vue.js (with Laravel Breeze & Inertia)
- PostgreSQL
- Docker (via Laravel Sail)
- Axios for API calls

## Getting Started with Laravel Sail
### Prerequisites
- [Docker](https://www.docker.com/) installed and running

### Setup Instructions
```bash
# Clone the repository
git clone <your-repo-url>
cd <project-folder>

# Copy .env and build containers
cp .env.example .env
./vendor/bin/sail up -d

# Run database migrations
./vendor/bin/sail artisan migrate

# Install frontend dependencies
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```

## Future Improvements
- Full user authentication using Breeze or Jetstream
- Input validation and user feedback for forms
- Extend PO linking and approval logic
- Admin dashboard with reporting capabilities
- UI improvements using Tailwind UI
- Export functionality for orders (PDF/CSV)

## AI Tooling Use
- ChatGPT was used to accelerate database modeling, structure design, and documentation generation.
- All outputs were reviewed and refined for clarity and project fit.

