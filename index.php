<?php
require 'config/database.php';
require 'includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_transaction'])) {
    $income = getIncomeForCurrentMonth($pdo, $_SESSION['user_id']);
    $expense = getExpenseForCurrentMonth($pdo, $_SESSION['user_id']);

    echo json_encode(['income' => $income, 'expense' => $expense]);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_transaction'])) {
    $type = $_POST['transaction_type'];
    $amount = $_POST['amount'];
    addTransactionForUser($pdo, $_SESSION['user_id'], $type, $amount);
    echo 'Транзакция добавлена успешно.';
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_stats'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $stats = getIncomeAndExpenseForMonth($pdo, $_SESSION['user_id'], $month, $year);

    echo json_encode($stats);
    exit();
}
if ($_GET['action'] == 'logout') {
  session_start();
  session_destroy();
  header("Location: /");
  exit();
}
$transactions = getAllTransactionsForUser($pdo, $_SESSION['user_id']);

?>

<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-3">FinanceApp <span class="text-muted"> - Система учета финансов</span></h1>

    <a href="index.php?action=logout" class="btn btn-danger w-100 mb-3">Выход</a>
    <div class="card mb-3">
      <div class="card-header">
          <h2 class="card-title">Добавление транзакции</h2>
      </div>
      <div class="card-body">
        <form id="add-transaction-form">
            <div class="form-group">
                <label for="transaction_type">Тип транзакции</label>
                <select id="transaction_type" name="transaction_type" class="form-control" required>
                    <option value="income">Доход</option>
                    <option value="expense">Расход</option>
                </select>
            </div>
            <div class "form-group">
                <label for="amount">Сумма</label>
                <input type="number" id="amount" name="amount" class="form-control" required>
            </div>
            <input type="hidden" name="add_transaction" value="1">
            <button type="submit" class="btn btn-primary w-100 mt-3">Добавить транзакцию</button>
        </form>
      </div>
    </div>
    <h2 class="text-center mb-3"> <span class="btn btn-primary mr-3" id="previousMonth">←</span> Статистика за <span class="text-muted" id="month">Октябрь</span> <span class="btn btn-primary ml-3" id="nextMonth">→</span></h2>
    <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
                <h2 class="card-title">Доходы и расходы за месяц</h2>
            </div>
            <canvas id="doughnutChart"></canvas>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-header">
                <h2 class="card-title">Ваши транзакции</h2>
            </div>
              <table class="table">
                  <thead>
                      <tr>
                          <th>Дата</th>
                          <th>Тип</th>
                          <th>Сумма</th>
                      </tr>
                  </thead>
                  <tbody class="transaction-list">
                      <?php foreach ($transactions as $transaction) : ?>
                          <tr>
                              <td><?= $transaction['date']; ?></td>
                              <td><?= $transaction['type'] === 'income' ? 'Доход' : 'Расход'; ?></td>
                              <td><?= $transaction['amount']; ?></td>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var user_id = <?php echo $_SESSION['user_id']; ?>;
    var currentMonth = new Date().getMonth() + 1;
    var currentYear = new Date().getFullYear();
    var months = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
    updateStatsForMonth(user_id, currentMonth, currentYear);

    $('#add-transaction-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var currentMonth = new Date().getMonth() + 1;
        var currentYear = new Date().getFullYear();
        $.ajax({
            type: 'POST',
            url: '/index.php',
            data: form.serialize(),
            success: function(data) {
                Toastify({
                    text: data,
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                }).showToast();
                if (data === 'Транзакция добавлена успешно.') {
                    updateStatsForMonth(user_id, currentMonth, currentYear);
                    form.trigger('reset');
                }
            }
        });
    });

    $('#previousMonth').click(function() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        updateStatsForMonth(user_id, currentMonth, currentYear);
    });

    $('#nextMonth').click(function() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        updateStatsForMonth(user_id, currentMonth, currentYear);
    });

    function updateStatsForMonth(user_id, month, year) {
        $.ajax({
            type: 'POST',
            url: '/index.php',
            data: {
                get_stats: 1,
                month: month,
                year: year
            },
            success: function(data) {
                var stats = JSON.parse(data);
                var incomeAmount = stats.income;
                var expenseAmount = stats.expense;
                createDoughnutChart('doughnutChart', incomeAmount, expenseAmount);
                $('#month').text(months[month - 1] + ' ' + year);

                refreshTransactionList(stats.transactions);
            }
        });
    }
  
    function refreshTransactionList(transactions) {
        var transactionList = $('.transaction-list');
        transactionList.empty();

        if (transactions.length === 0) {
            transactionList.append('<tr><td colspan="3">В этом месяце не было операций.</td></tr>');
        } else {
            transactions.forEach(function(transaction) {
                var type = transaction.type === 'income' ? 'Доход' : 'Расход';
                var row = '<tr><td>' + transaction.date + '</td><td>' + type + '</td><td>' + transaction.amount + '</td></tr>';
                transactionList.append(row);
            });
        }
    }

    function getIncomeAndExpenseForCurrentMonth(user_id) {
        $.ajax({
            type: 'POST',
            url: 'index.php',
            data: { get_transaction: 1 },
            success: function(data) {
                var result = JSON.parse(data);
                var incomeAmount = result.income;
                var expenseAmount = result.expense;

                createDoughnutChart('doughnutChart', incomeAmount, expenseAmount);
            }
        });
    }

    function createDoughnutChart(canvasId, incomeAmount, expenseAmount) {
        var ctx = document.getElementById(canvasId).getContext('2d');

        if (window.myDoughnutChart) {
            window.myDoughnutChart.destroy();
        }

        window.myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Доход', 'Расход'],
                datasets: [{
                    data: [incomeAmount, expenseAmount],
                    backgroundColor: ['rgba(54, 162, 235, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                }],
            },
            options: {
                cutout: '0%',
            },
        });
    }

});
</script>

<?php include 'templates/footer.php'; ?>
