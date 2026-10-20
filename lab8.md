Звіт з лабораторної роботи №8

Код обробника файлових завантажень:
$file = $_FILES['avatar'] ?? null;

    if ($file !== null && $file['error'] !== UPLOAD_ERR_NO_FILE) {

      if ($file['error'] === UPLOAD_ERR_OK) {

        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $errors[] = "Файл завеликий! Максимальний розмір — 2 МБ.";
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            $errors[] = "Дозволено завантажувати лише зображення формату JPG або PNG!";
        }

        if (getimagesize($file['tmp_name']) === false) {
                $errors[] = "Завантажений файл не є зображенням!";
        }
      } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = "Файл перевищує дозволений ліміт сервера!";
      } else {
          $errors[] = "Помилка завантаження файлу (код " . $file['error'] . ").";
      }
    }

    if (empty($errors)) {

        if ($file !== null && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newName = uniqid('avatar_', true) . '.' . $ext;
            $destination = $uploadDir . $newName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $uploadedFilePath = $destination;
            } else {
                $errors[] = "Помилка запису на диск (перевірте права на папку uploads).";
            }
        }
    }

Відповіді на контрольні запитання:

1. це спосіб кодування тіла HTTP запиту, без цього атрибута браузер надішле лише назву файлу текстом, а сам файл не передасть 

2. name, type, tmp_name, error, size
tmp_name містить шлях до тимчасової копії файлу, яку PHP створив у системній тимчасовій папці

3. тому що спочатку PHP кладе його в ізольовану тимчасову директорію ОС, а ми перевіряємо розмір, розширення і типа потім якщо перевірки пройдено, переміщуємо файл у проєкт

4. переносить файл із tmp_name у постійну директорію. функція copy() без перевірок дозволить зловмиснику скопіювати будь-який чутливий системний файл сервера у публічний доступ або завантажити
шкідливий PHP-скрипт для повного зламу системи

5. з  авантаження виконуваних скриптів, які зловмисник потім відкриває в браузері й отримує контроль над сервером, також заповнення диска величезними файлами, перезапис існуючих файлів через
однакові імена, розповсюдження вірусів і шкідливого вмісту