<?php

namespace App\Controller;

use App\Model\CatManager;

class CatController extends AbstractController
{

    public function index()
    {
        $catManager = new CatManager();
        $cats = $catManager->selectAll();

        return $this->twig->render('Cat/index.html.twig', ['cats' => $cats]);
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
        $cat = $catManager->selectOneById($id);

        return $this->twig->render('Cat/show.html.twig', ['cat' => $cat]);
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
    public function edit(int $id): string // .../cat/edit/id
    {
        $catManager = new CatManager();
        $cat = $catManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cat['name'] = $_POST['name'];
            $catManager->update($cat);
        }

        return $this->twig->render('Cat/edit.html.twig', ['cat' => $cat]);
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
            $cat = [
                'name' => $_POST['name'],
                'img' => $_POST['img'],
            ];
            $id = $catManager->insert($cat);
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
