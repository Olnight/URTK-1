
trait ProductActions {
    public function actionView($id) {
        $pdo = new PDO("mysql:host=localhost;dbname=akhfvmxt_m4;charset=UTF8", 'akhfvmxt', 'Tp83F6');
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Вывести информацию о продукте, например, через var_dump или другой метод
            var_dump($result);
        } else {
            echo "Запись не найдена";
        }
    }

    public function actionUpdate($id) {
        $pdo = new PDO("mysql:host=localhost;dbname=akhfvmxt_m4;charset=UTF8", 'akhfvmxt', 'Tp83F6');
        $stmt = $pdo->prepare("UPDATE products SET title = ?, firstname = ?, mainname = ?, price = ? WHERE id = ?");
        $stmt->execute([$this->title, $this->producerFirstName, $this->producerMainName, $this->price, $id]);
        
        // Можно добавить дополнительную логику при обновлении записи
    }

    public function actionDelete($id) {
        $pdo = new PDO("mysql:host=localhost;dbname=akhfvmxt_m4;charset=UTF8", 'akhfvmxt', 'Tp83F6');
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        // Можно добавить дополнительную логику при удалении записи
    }

    protected function find($id) {
        $pdo = new PDO("mysql:host=localhost;dbname=akhfvmxt_m4;charset=UTF8", 'akhfvmxt', 'Tp83F6');
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}

<?php
$dsn = "mysql:host=localhost;dbname=akhfvmxt_m4;charset=UTF8";
$pdo = new PDO($dsn, 'akhfvmxt', 'Tp83F6');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
class ShopProduct
{
    public function __construct(private string $title, private string $producerFirstName = "", private string $producerMainName = "", private float $price = 0)
    {
    }

    public function getProducerFirstName(): string
    {
        return $this->producerFirstName;
    }

    public function setProducerFirstName(string $producerFirstName): void
    {
        $this->producerFirstName = $producerFirstName;
    }
    public function getProducerMainName(): string
    {
        return $this->producerMainName;
    }
    public function setProducerMainName(string $producerMainName): void
    {
        $this->producerMainName = $producerMainName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->$title = $$title;
    }
    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->$price = $price;
    }

    public function actionCreate(PDO $pdo): void
    {
        $stmt = $pdo->prepare("INSERT INTO products(title, firstname, mainname, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->title, $this->producerFirstName, $this->producerMainName, $this->price]);
    }
}

class CDProduct extends ShopProduct
{
    public function __construct(string $title, string $firstName = "", string $mainName = "", float $price = 0, public float $playLength = 0)
    {
        parent::__construct($title, $firstName, $mainName, $price);
    }
    public function getPlayLength(): float
    {
        return $this->playLength;
    }
    public function setPlayLength(float $playLength): void
    {
        $this->$playLength = $playLength;
    }
    public function actionCreate(PDO $pdo): void
    {
        parent::actionCreate($pdo);
        $stmt = $pdo->query("SELECT id FROM products ORDER BY id DESC");
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("INSERT INTO cd(product_id, playLength) VALUES (?, ?)");
        $stmt->execute([$product['id'], $this->playLength]);
    }

}

class BookProduct extends ShopProduct
{
    public function __construct(string $title, string $firstName = "", string $mainName = "", float $price = 0, public int $numPages = 0)
    {
        parent::__construct($title, $firstName, $mainName, $price);
    }
    public function getNumPages(): int
    {
        return $this->numPages;
    }

    public function setNumPages(int $numPages): void
    {
        $this->$numPages = $numPages;
    }

    public function actionCreate(PDO $pdo): void
    {
        parent::actionCreate($pdo);
        $stmt = $pdo->query("SELECT id FROM products ORDER BY id DESC");
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("INSERT INTO book(product_id, numpages) VALUES (?, ?)");
        $stmt->execute([$product['id'], $this->numPages]);
    }
}

class ShowInfo
{
    public function printCD(CDProduct $ShopProduct):void{
        $str = "{$ShopProduct -> getTitle()}, {$ShopProduct -> getProducerFirstName()}, {$ShopProduct -> getProducerMainName()}, {$ShopProduct -> getPrice()}, {$ShopProduct -> getPlayLength()}"; 
        print $str;
    }
    public function printBook(BookProduct $ShopProduct):void{
        $str = "{$ShopProduct -> getTitle()}, {$ShopProduct -> getProducerFirstName()}, {$ShopProduct -> getProducerMainName()}, {$ShopProduct -> getPrice()}, {$ShopProduct -> getNumPages()}"; 
        print $str;
    }    
    
}

// Пример использования классов
$cd = new CDProduct("Музыкальный альбом", "Майкл", "Джексон", 12.99, 60);
$book = new BookProduct("Книга", "Рой", "Бербери", 9.99, 200);
$cd->actionCreate($pdo);
$book->actionCreate($pdo);

$writer = new ShowInfo();
$writer->printCD($cd);
print '<br>';
$writer->printBook($book);
