#!/bin/bash

echo "Starting SQL Server..."
/opt/mssql/bin/sqlservr &

# Wait for SQL Server to start
sleep 20

echo "Importing database schema..."
/opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrongPassword123" -d master -i /var/www/html/database/database_schema.sql

echo "Database initialized successfully!"