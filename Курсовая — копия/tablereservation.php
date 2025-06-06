<!DOCTYPE html>
<?php
// Подключение к базе данных
$db = new mysqli('localhost', 'root', 'Pivochka277353@', 'booking_system');
if ($db->connect_error) die("Connection failed: " . $db->connect_error);
$db->set_charset("utf8");

// Обработка формы бронирования
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = '<div class="error">Онлайн бронирование временно недоступно. Проект закрыт.</div>';
}

// Получение списка столов
$tables = $db->query("SELECT * FROM tables WHERE is_active = TRUE")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style3.css">
    <title>Творческий центр "Античность"</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <noscript>
        <style>.body_hide{opacity: 1 !important;}</style>
    </noscript>
</head>
<body class="body_hide">
    <header>
        <a href="index.html" style="text-decoration:none;">
            <img src="image/logo.png" alt="Логотип" height="60px" class="logo">
            <h2 class="title">Античность</h2>
        </a>
        <div class="buttons">
            <a href="about.html" class="button">О нас</a>
            <a href="tablereservation.php" class="button">Бронь стола</a>
        </div>
    </header>
    
    <div class="main">
        <h1>Бронирование</h1>
        
        <?php echo $message; ?>
        
        <div class="tabs">
            <div class="tab active" data-tab="table">Столы (300₽/час)</div>
            <div class="tab" data-tab="workshop">Комната (500₽/час)</div>
        </div>
        
        <form method="POST" id="booking-form">
            <input type="hidden" name="type" id="booking-type" value="table">
            
            <div class="tab-content active" id="table-tab">
                <div class="form-group">
                    <label>Выберите стол:</label>
                    <div class="table-list">
                        <?php foreach ($tables as $table): ?>
                            <div class="table-item" data-id="<?= $table['table_id'] ?>">
                                <div>Стол №<?= str_pad($table['table_number'], 2, '0', STR_PAD_LEFT) ?></div>
                                <div class="price">300₽/час</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="table_id" id="selected-table">
                </div>
            </div>
            
            <div class="tab-content" id="workshop-tab">
                <div class="form-group">
                    <label>Комната для мастер-классов</label>
                    <div class="price-display" style="font-size: 24px; margin: 15px 0; color: #957B8D;">
                        Стоимость: 500₽/час
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Ваше имя*</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Телефон*</label>
                <input type="tel" name="phone" required>
            </div>
            
            <div class="form-group">
                <label>Дата*</label>
                <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Время*</label>
                <input type="time" name="time" min="10:00" max="22:00" required>
            </div>
            
            <div class="form-group">
                <label>Продолжительность (часы)*</label>
                <select name="duration" required>
                    <option value="1">1 час</option>
                    <option value="2" selected>2 часа</option>
                    <option value="3">3 часа</option>
                </select>
            </div>
            
            <button type="submit">Забронировать</button>
        </form>
    </div>
    <script>
        // Анимация появления страницы
        setTimeout(function() {
            document.body.classList.add('body_visible');
        }, 300);
    </script>

    <script>
        // Переключение между вкладками
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
                document.getElementById('booking-type').value = tabId;
            });
        });
        
        // Выбор стола
        document.querySelectorAll('.table-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.table-item').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('selected-table').value = this.dataset.id;
            });
        });
        
        // Выбираем первый стол по умолчанию
        if (document.querySelector('.table-item')) {
            document.querySelector('.table-item').click();
        }
    </script>
</body>
</html>