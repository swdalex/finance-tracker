-- Initial DB schema

-- Drop existing tables if needed
DROP TABLE IF EXISTS transactions, categories, settings, users CASCADE;

-- Users table
CREATE TABLE users (
                       id SERIAL PRIMARY KEY,
                       email TEXT UNIQUE NOT NULL,
                       password TEXT NOT NULL,
                       created_at TIMESTAMP DEFAULT NOW()
);

-- Categories table
CREATE TABLE categories (
                            id SERIAL PRIMARY KEY,
                            user_id INT REFERENCES users(id) ON DELETE CASCADE,
                            name TEXT NOT NULL,
                            type TEXT CHECK (type IN ('income', 'expense')) NOT NULL
);

-- Transactions table
CREATE TABLE transactions (
                              id SERIAL PRIMARY KEY,
                              user_id INT REFERENCES users(id) ON DELETE CASCADE,
                              category_id INT REFERENCES categories(id) ON DELETE SET NULL,
                              amount DECIMAL(10,2) NOT NULL,
                              description TEXT,
                              transaction_date DATE NOT NULL,
                              created_at TIMESTAMP DEFAULT NOW()
);

-- Settings table
CREATE TABLE settings (
                          user_id INT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
                          currency TEXT DEFAULT 'EUR',
                          monthly_budget DECIMAL(10,2) DEFAULT NULL
);
