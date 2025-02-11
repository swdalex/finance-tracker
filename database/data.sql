-- Sample data

-- Insert default user
INSERT INTO users (id, email, password) VALUES (1, 'swdalex@gmail.com', '$2y$10$sgCjTwZZK.O6BkXTgxq1nesL1hFVJmzSyz0XQkfeLuDr5Y1RuXqZ6');

-- Insert default categories
INSERT INTO categories (id, user_id, name, type) VALUES
                                                 (1, 1, 'Salary', 'income'),
                                                 (2, 1, 'Groceries', 'expense'),
                                                 (3, 1, 'Entertainment', 'expense');

-- Insert sample transactions
INSERT INTO transactions (user_id, category_id, amount, description, transaction_date) VALUES
                                                                                           (1, 1, 2500.00, 'Salary', '2024-02-01'),
                                                                                           (1, 2, -150.75, 'Lidl shopping', '2024-02-02'),
                                                                                           (1, 3, -20.00, 'Cinema ticket', '2024-02-03');