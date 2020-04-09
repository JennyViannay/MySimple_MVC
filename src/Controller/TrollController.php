<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\UserManager;
use App\Model\ItemManager;
use App\Model\BasketManager;

/**
 * Class TrollController
 *
 */
class TrollController extends AbstractController
{

    /**
     * @param null $pseudo
     * @param null $password
     * @return string|void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login($pseudo = null, $password = null)
    {
        $userManager = new UserManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!empty($_POST['pseudo']) && !empty($_POST['password'])){
                $item['pseudo'] = $_POST['pseudo'];
                $item['password'] = $_POST['password'];
                if($userManager->verify($item) > 0){
                    $_SESSION['isConnected'] = true;
                    $_SESSION['user_id'] = $userManager->verify($item);
                    return header('Location: /troll/home');
                } else {
                    return $this->twig->render('Troll/login.html.twig', ['error' => "INCORRECT ID"]);
                }
            } else {
                return $this->twig->render('Troll/login.html.twig', ['error' => "Please enter PSEUDO & PASSWORD !"]);
            }
        }
        return $this->twig->render('Troll/login.html.twig', ['session' => false]);
    }

    public function logout()
    {
        session_destroy();
        return $this->twig->render('Home/index.html.twig');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home()
    {
        if ($_SESSION['isConnected'] === true){
            $basket_id = null;
            $itemManager = new ItemManager();
            $items = $itemManager->selectAll();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->shop($_POST);
            }
            return $this->twig->render('Troll/home.html.twig', [
                'items' => $items,
                'session' => true,
                'userId' => $_SESSION['user_id']
            ]);
        }
        return $this->twig->render('Home/index.html.twig');
    }


    /**
     * @param string $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(string $id): string
    {
        if($_SESSION['isConnected'] === true){
            $userManager = new UserManager();
            $item = $userManager->selectOneById($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $item['pseudo'] = $_POST['pseudo'];
                $item['password'] = $_POST['password'];
                $item['avatar'] = $_POST['avatar'];
                $userManager->update($item);
            }

            return $this->twig->render('Troll/profile.html.twig', ['user' => $item, 'session' => true]);
        }
        return $this->twig->render('Home/index.html.twig');

    }


    /**
     * Display item creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $item = [
                'pseudo' => $_POST['pseudo'],
                'password' => $_POST['password']
            ];
            $id = $userManager->insert($item);
            header('Location:/troll/show/' . $id);
        }

        return $this->twig->render('Troll/add.html.twig');
    }


    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $UserManager = new UserManager();
        $UserManager->delete($id);
        header('Location: /');
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function shop($item)
    {
        if(empty($_SESSION['basket'])){
            $_SESSION['basket'] = [$item];
        }
        if(!empty($_SESSION['basket'])){
            array_push($_SESSION['basket'], $item);
        }
        return $_SESSION['basket'];
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function basket()
    {
        if($_SESSION['isConnected'] === true){

            return $this->twig->render('Troll/basket.html.twig', [
                'basket' => $_SESSION['basket'],
                'session' => true
            ]);
        }
        return $this->twig->render('Home/index.html.twig');
    }

}
