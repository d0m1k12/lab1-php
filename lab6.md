блок коду з формою (<form>) з усіма атрибутами name, method та action:

<form action="create.php" method="POST">
    
    <div class="form-group">
      <label for="title">Назва завдання:</label>
      <input type="text" id="title" name="title" value="<?= $title ?>" placeholder="Що потрібно зробити?">
    </div>

    <div class="form-group">
      <label for="description">Опис завдання:</label>
      <textarea id="description" name="description" rows="4" placeholder="Деталі..."><?= $description ?></textarea>
    </div>

    <div class="form-group">
      <label for="priority">Пріоритет:</label>
      <select id="priority" name="priority">
        <option value="Low" <?= ($priority === 'Low') ? 'selected' : '' ?>>Low (Низький)</option>
        <option value="Medium" <?= ($priority === 'Medium') ? 'selected' : '' ?>>Medium (Середній)</option>
        <option value="High" <?= ($priority === 'High') ? 'selected' : '' ?>>High (Високий)</option>
      </select>
    </div>

    <button type="submit">Зберегти</button>
  </form>

1. GET передає дані відкрито в адресному рядку браузера, а POST ховає їх у тілі запиту HTTP
GET має обмеження на об'єм даних, POST дозволяє передавати великі масиви даних
GET використовується переважно для запиту даних, а POST — для відправки нових даних на сервер
2. GET підходить для пошукових запитів, фільтрів, сортування на сайтах. Це зручно, бо таким посиланням можна поділитися. Паролі не можна передавати через GET, оскільки вони будуть видимі в адресному рядку браузера
3. вказує шлях, на який браузер повинен відправити зібрані з форми дані після натискання кнопки
4. PHP бере назву для ключа у масиві $_POST з HTML-атрибута name всередині тегів <input>, <textarea> чи <select>
5. виводить повну інформацію про змінну, що є безцінним для пошуку помилок, тег <pre> зберігає пробіли та перенесення рядків, роблячи вивід читабельним списком
у продуктовому середовищі цей код не використовують, оскільки він видає службову інформацію і має неестетичний вигляд для клієнта