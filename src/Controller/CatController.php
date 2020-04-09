<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\CatManager;

/**
 * Class CatController
 *
 */
class CatController extends AbstractController
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
        $catManager = new CatManager();
        $items = $catManager->selectAll();

        return $this->twig->render('Cat/index.html.twig', ['items' => $items]);
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
        $catManager = new CatManager();
        $item = $catManager->selectOneById($id);

        return $this->twig->render('Cat/show.html.twig', ['item' => $item]);
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
        $catManager = new CatManager();
        $item = $catManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item['name'] = $_POST['name'];
            $item['img'] = $_POST['img'];
            $catManager->update($item);
        }

        return $this->twig->render('Cat/edit.html.twig', ['item' => $item]);
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
            $catManager = new CatManager();
            $item = [
                'name' => $_POST['name'],
                'img' => $_POST['img']
            ];
            $id = $catManager->insert($item);
            header('Location:/cat/show/' . $id);
        }

        return $this->twig->render('Cat/add.html.twig');
    }


    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $catManager = new CatManager();
        $catManager->delete($id);
        header('Location:/cat/index');
    }
}
