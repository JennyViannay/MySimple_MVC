<?php


namespace App\Controller;

use App\Model\UserManager;

/**
 * Class ItemController
 *
 */
class UserController extends AbstractController
{


    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll();

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
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
        $user = $userManager->selectOneById($id);

        return $this->twig->render('User/show.html.twig', ['user' => $user]);
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
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user['pseudo'] = $_POST['pseudo'];
            $user['password'] = $_POST['password'];
            $user['avatar'] = $_POST['avatar'];
            $userManager->update($user);
        }

        return $this->twig->render('User/edit.html.twig', ['user' => $user]);
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
            $user = [
                'pseudo' => $_POST['pseudo'],
                'password' => $_POST['password'],
                'avatar' => $_POST['avatar']
            ];
            $id = $userManager->insert($user);
            header('Location:/user/show/' . $id);
        }

        return $this->twig->render('User/add.html.twig');
    }


    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $userManager = new UserManager();
        $userManager->delete($id);
        header('Location:/user/index');
    }
}