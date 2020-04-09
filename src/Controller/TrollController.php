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

/**
 * Class TrollController
 *
 */
class TrollController extends AbstractController
{

    public function login($pseudo = null, $password = null)
    {
        $userManager = new UserManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!empty($_POST['pseudo']) && !empty($_POST['password'])){
                $item['pseudo'] = $_POST['pseudo'];
                $item['password'] = $_POST['password'];
                if($userManager->verify($item) === true){
                    return header('Location: /troll/home');
                } else {
                    return $this->twig->render('Troll/login.html.twig', ['error' => "INCORRECT ID"]);
                }
            } else {
                return $this->twig->render('Troll/login.html.twig', ['error' => "Please enter PSEUDO & PASSWORD !"]);
            }
        }
        return $this->twig->render('Troll/login.html.twig');
    }

    public function home()
    {
        $itemManager = new ItemManager();
        $items = $itemManager->selectAll();
        return $this->twig->render('Troll/home.html.twig', ['items' => $items]);
    }


    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $userManager = new UserManager();
        $item = $userManager->selectOneById($id);

        return $this->twig->render('Troll/show.html.twig', ['item' => $item]);
    }


    /**
     * Display item edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $userManager = new UserManager();
        $item = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item['pseudo'] = $_POST['pseudo'];
            $item['password'] = $_POST['password'];
            $userManager->update($item);
        }

        return $this->twig->render('Troll/edit.html.twig', ['item' => $item]);
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
}
