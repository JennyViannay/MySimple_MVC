<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class UserManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $item
     * @return int
     */
    public function insert(array $item): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`pseudo`, `password`) VALUES (:pseudo, :password)");
        $statement->bindValue('pseudo', $item['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue('password', $item['password'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param array $item
     * @return int
     */
    public function verify(array $item): int
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE pseudo =:pseudo");
        $statement->bindValue('pseudo', $item['pseudo'], \PDO::PARAM_STR);
        if ($statement->execute()) {
            $result = $statement->fetchObject();
            if ($result === false){
                return 0;
            }
            if(isset($result) && $_POST['password'] === $result->password){
                return $result->id;
            } else {
                return 0;
            }
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $item
     * @return bool
     */
    public function update(array $item):bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `pseudo` = :pseudo, `password` = :password, `avatar` = :avatar WHERE id=:id");
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('pseudo', $item['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue('password', $item['password'], \PDO::PARAM_STR);
        $statement->bindValue('avatar', $item['avatar'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
