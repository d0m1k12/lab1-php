Звіт з лабораторної роботи №10

Код класів та пояснення методів:
Task

<?php
declare(strict_types=1);

class Task
{
    public function __construct(
        public readonly int    $id,
        private string         $title,
        private bool           $isCompleted = false,
        private ?string        $deadline    = null,
    ) {
        if (empty(trim($title))) {
            throw new InvalidArgumentException('Назва завдання не може бути порожньою.');
        }
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function isCompleted(): bool { return $this->isCompleted; }
    public function getDeadline(): ?string { return $this->deadline; }

    public function isOverdue(): bool
    {
        if ($this->isCompleted || $this->deadline === null) {
            return false;
        }
        return strtotime($this->deadline) < time();
    }

    public function complete(): void { $this->isCompleted = true; }

    public function getStatusLabel(): string
    {
        if ($this->isCompleted) { return 'Виконано'; }
        if ($this->isOverdue()) { return 'Прострочено'; }
        return 'В процесі';
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'isCompleted' => $this->isCompleted,
            'deadline'    => $this->deadline,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id:          $data['id'],
            title:       $data['title'],
            isCompleted: $data['isCompleted'] ?? false,
            deadline:    $data['deadline']    ?? null,
        );
    }
}

Пояснення методів:

__construct: ініціалізує властивості об'єкта під час його створення та валідує назву, забороняючи створювати завдання з порожнім заголовком

getId, getTitle, isCompleted, getDeadline: публічні геттери, які дозволяють безпечно прочитати значення приватних властивостей ззовні, не даючи можливості їх змінити

isOverdue: перевіряє, чи минув час дедлайну порівняно з поточним часом сервера

complete: змінює стан завдання, встановлюючи приватній властивості значення true

getStatusLabel: повертає зрозумілий текстовий статус завдання ("Виконано", "Прострочено", "В процесі")

toArray: серіалізує об'єкт, перетворюючи його властивості у звичайний асоціативний масив для подальшого збереження у json

fromArray: статичний фабричний метод, який створює новий екземпляр класу Task із сирих даних, прочитаних з масиву json


TaskRepository

<?php
declare(strict_types=1);
require_once __DIR__ . '/Task.php';

class TaskRepository
{
    private array $tasks = [];
    private string $storageFile;

    public function __construct(string $storageFile)
    {
        $this->storageFile = $storageFile;
        $this->load();
    }

    public function findAll(): array { return $this->tasks; }

    public function findById(int $id): ?Task
    {
        foreach ($this->tasks as $task) {
            if ($task->getId() === $id) return $task;
        }
        return null;
    }

    public function add(string $title, ?string $deadline = null): Task
    {
        $newId  = $this->generateNextId();
        $task   = new Task($newId, $title, false, $deadline);
        $this->tasks[] = $task;
        $this->save();
        return $task;
    }

    public function complete(int $id): bool
    {
        $task = $this->findById($id);
        if ($task === null) return false;
        $task->complete();
        $this->save();
        return true;
    }

    public function delete(int $id): bool
    {
        foreach ($this->tasks as $key => $task) {
            if ($task->getId() === $id) {
                unset($this->tasks[$key]);
                $this->tasks = array_values($this->tasks); 
                $this->save();
                return true;
            }
        }
        return false;
    }

    public function countCompleted(): int
    {
        return count(array_filter($this->tasks, fn(Task $t) => $t->isCompleted()));
    }

    public function countOverdue(): int
    {
        return count(array_filter($this->tasks, fn(Task $t) => $t->isOverdue()));
    }

    private function generateNextId(): int
    {
        if (empty($this->tasks)) return 1;
        $maxId = max(array_map(fn(Task $t) => $t->getId(), $this->tasks));
        return $maxId + 1;
    }

    private function load(): void
    {
        if (!file_exists($this->storageFile)) {
            $this->tasks = [];
            return;
        }
        $json = file_get_contents($this->storageFile);
        $data = json_decode($json, true) ?? [];
        $this->tasks = array_map(fn(array $item) => Task::fromArray($item), $data);
    }

    private function save(): void
    {
        $data = array_map(fn(Task $t) => $t->toArray(), $this->tasks);
        file_put_contents($this->storageFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

Пояснення методів:

__construct: ініціалізує репозиторій, зберігає шлях до json-файлу бази даних та одразу викликає метод load() для завантаження наявних завдань в пам'ять

findAll: повертає масив усіх існуючих об'єктів завдань, що наразі зберігаються в репозиторії

findById: шукає та повертає конкретний об'єкт Task за його унікальним ідентифікатором, або null, якщо такого завдання немає

add: створює новий екземпляр Task із згенерованим id, додає його до колекції та викликає метод збереження змін у файл

complete: знаходить завдання за id, викликає його внутрішній метод complete() та фіксує зміну статусу у файлі

delete: шукає завдання за id, видаляє його з масиву за ключем, перенумеровує індекси масиву та зберігає оновлену колекцію на диск

countCompleted, countOverdue: допоміжні методи, які за допомогою функції array_filter підраховують кількість виконаних та прострочених завдань для блоку статистики

generateNextId: автоматично обчислює наступний унікальний ID для нового завдання, знаходячи максимальний наявний id і додаючи до нього одиницю

load: приватний метод, що зчитує json-файл, декодує його та перетворює сирі масиви даних на повноцінні об'єкти Task через метод fromArray()

save: приватний метод, що конвертує всі об'єкти Task назад у прості масиви через toArray(), кодує їх у json і перезаписує файл бази даних


https://github.com/d0m1k12/lab1-php


Відповіді на контрольні запитання:

1. це шаблон, який об'єднує дані і логіку роботи з цими даними, масив — це "сирі" дані без жодних правил чи обмежень, завдання зручніше представити об'єктом, оскільки об'єкт гарантує цілісність даних і сам обробляє свій стан

2. private робить властивість прихованою, до неї не можна звернутися або змінити її значення напряму з-поза меж самого класу, властивість $title зроблено private, щоб зовнішній код не перезаписав назву завдання порожнім рядком або числом оминаючи перевірки

3. одночасне оголошення властивості $id та присвоєння їй значення з аргументу, ключове слово readonly означає що значення цій властивості можна присвоїти лише один раз, і після цього його неможливо змінити жодним чином, ідентифікатор завдання ніколи не повинен змінюватися, а $isCompleted змінюється з false на true

4. приймає звичайний асоціативний масив і конструює з нього повноцінний об'єкт класу Task, він оголошений як static, тому що виступає у ролі фабрики для створення об'єктів, статичний метод належить самому класу, а не його екземпляру, тому ми можемо викликати його до того, як об'єкт існує, якби це був звичайний метод, нам довелося б спочатку створити порожній об'єкт, щоб мати змогу викликати метод

5. 
1) викликається метод $repo->add('Нове завдання')
2) генерується новий унікальний ідентифікатор за допомогою приватного методу generateNextId()
3) створюється новий екземпляр new Task(...) із переданою назвою та згенерованим ID
4) новий об'єкт додається в кінець внутрішнього масиву репозиторію $this->tasks
5) викликається приватний метод $this->save()
6) у методі save() кожен об'єкт Task з масиву за допомогою функції array_map перетворюється на звичайний асоціативний масив
7) новий масив масивів перетворюється на рядок формату JSON за допомогою вбудованої функції json_encode()
8) функція file_put_contents() відкриває файл tasks.json і повністю перезаписує його цим новим JSON-рядком, де вже є нове завдання