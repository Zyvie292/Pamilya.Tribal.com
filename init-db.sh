#!/bin/bash

# Load environment variables (if any)
source /etc/environment || true

echo "Starting SQL Server..."
/opt/mssql/bin/sqlservr &

# Wait until SQL Server is ready
echo "Waiting for SQL Server to start..."
for i in {1..30}; do
    /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "$SA_PASSWORD" -Q "SELECT 1" &> /dev/null
    if [ $? -eq 0 ]; then
        echo "✅ SQL Server is ready!"
        break
    fi
    echo "⏳ SQL Server is not ready yet. Retrying in 5 seconds..."
    sleep 5
done

# Check if SQL Server started successfully
/opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "$SA_PASSWORD" -Q "SELECT 1" &> /dev/null
if [ $? -ne 0 ]; then
    echo "❌ ERROR: SQL Server did not start in time."
    exit 1
fi

# Import database schema
echo "📥 Importing database schema..."
/opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "$SA_PASSWORD" -d master -i /var/www/html/database/database_schema.sql

if [ $? -eq 0 ]; then
    echo "✅ Database initialized successfully!"
else
    echo "❌ ERROR: Database initialization failed!"
    exit 1
fi