<?php
function isUserExists($pdo, $username) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerUser($pdo, $username, $password) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt->execute([$username, $password])) {
        $user_id = $pdo->lastInsertId();

        $_SESSION['user_id'] = $user_id;
        return true;
    }

    return false;
}

function loginUser($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }

    return false;
}

function addTransactionForUser($pdo, $user_id, $type, $amount) {
    $stmt = $pdo->prepare("INSERT INTO " . ($type === 'income' ? 'incomes' : 'expenses') . " (user_id, amount, date) VALUES (?, ?, NOW())");
    return $stmt->execute([$user_id, $amount]);
}

function getTransactionsForUser($pdo, $user_id, $type) {
    $stmt = $pdo->prepare("SELECT * FROM " . ($type === 'income' ? 'incomes' : 'expenses') . " WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllTransactionsForUser($pdo, $user_id) {
    $stmt = $pdo->prepare("
        (SELECT id, amount, date, description, 'income' as type FROM incomes WHERE user_id = ?)
        UNION ALL
        (SELECT id, amount, date, description, 'expense' as type FROM expenses WHERE user_id = ?)
        ORDER BY date DESC
    ");

    $stmt->execute([$user_id, $user_id]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $transactions;
}

function getIncomeForCurrentMonth($pdo, $user_id) {
    $currentMonth = date('Y-m');
    $stmt = $pdo->prepare("SELECT SUM(amount) as total_income FROM incomes WHERE user_id = ? AND date LIKE ?");
    $stmt->execute([$user_id, $currentMonth . '%']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_income'] ?: 0;
}

function getExpenseForCurrentMonth($pdo, $user_id) {
    $currentMonth = date('Y-m');
    $stmt = $pdo->prepare("SELECT SUM(amount) as total_expense FROM expenses WHERE user_id = ? AND date LIKE ?");
    $stmt->execute([$user_id, $currentMonth . '%']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_expense'] ?: 0;
}

function getIncomeAndExpenseForMonth($pdo, $user_id, $month, $year) {
    $start_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
    $end_date = $year . '-' . str_pad($month + 1, 2, '0', STR_PAD_LEFT) . '-01';

    $stmt = $pdo->prepare("SELECT SUM(amount) as total_income FROM incomes WHERE user_id = ? AND date >= ? AND date < ?");
    $stmt->execute([$user_id, $start_date, $end_date]);
    $income = $stmt->fetch(PDO::FETCH_ASSOC)['total_income'] ?: 0;

    $stmt = $pdo->prepare("SELECT SUM(amount) as total_expense FROM expenses WHERE user_id = ? AND date >= ? AND date < ?");
    $stmt->execute([$user_id, $start_date, $end_date]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC)['total_expense'] ?: 0;

    $stmt = $pdo->prepare("SELECT * FROM incomes WHERE user_id = ? AND date >= ? AND date < ?");
    $stmt->execute([$user_id, $start_date, $end_date]);
    $income_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id = ? AND date >= ? AND date < ?");
    $stmt->execute([$user_id, $start_date, $end_date]);
    $expense_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $transactions = array_merge($income_transactions, $expense_transactions);

    function compareByDate($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    }

    usort($transactions, 'compareByDate');

    return [
        'income' => $income,
        'expense' => $expense,
        'transactions' => $transactions
    ];
}
