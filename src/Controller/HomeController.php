<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://brianiswu-cat-facts-v1.p.rapidapi.com/facts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: brianiswu-cat-facts-v1.p.rapidapi.com",
                "x-rapidapi-key: 3038c2322amshbd28049ff1cb4b3p14dc77jsn0dd7720e19e9"
            ),
        ));
        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo $error = "cURL Error #:" . $err;
        }
        $result = $response->all;
        $facts = [];
        foreach ($result as $fact){
            array_push($facts, $fact->text);
        }
        return $this->twig->render('Home/index.html.twig', ['fact' => $facts[array_rand($facts)]]);
    }
}
