<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class ShutterStockApiController extends AbstractController
{

  const API_SEARCH_ENDPOINT = "https://api.shutterstock.com/v2/images/search?";
  const LICENCE_ENDPOINT = "https://api.shutterstock.com/v2/images/licenses?subscription_id=";
  const CLIENT_ID = 'CLIENT ID HERE';
  const CLIENT_SECRET = 'CLIENT SECRET HERE';

  

    /**
     * 
     * @Route("/shutterstocksearch/", name="search_user", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    { 

        if($request->getMethod() === 'POST')
        {
          $requestTypeSearch = $request->request->get('usersearch');
          
        } else {
          $requestTypeSearch = $request->get('usersearch');
        }

        
        $queryFields = [
          "query" => $requestTypeSearch,
          "sort" => "popular",
          "license" => "commercial"
        ];

        $options = [
          CURLOPT_URL => self::API_SEARCH_ENDPOINT  . http_build_query($queryFields),
          CURLOPT_USERAGENT => "php/curl",
          CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
          CURLOPT_RETURNTRANSFER => 1
        ];
      
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_USERPWD, self::CLIENT_ID.":".self::CLIENT_SECRET);
        curl_setopt_array($handle, $options);
        $response = curl_exec($handle);
        curl_close($handle);
        


        $decodedResponse = json_decode($response);
        
        $getImages = $this->getImages($decodedResponse);
        $this->getLogs($getImages, $queryFields);
  
        return $this->render('search.html.twig', [
          'getImages' => $getImages,
          'userImageSearch' => $queryFields['query'],
      ]);
    }


    /*
    * Getlog files to see if shutterstock API has successful calls or fails
    * @example http://localhost:3000/shutterstocksearch/?usersearch=laptops
    */
    public function getLogs($getImages, $queryFields)
    {
        //Documentation - https://zetcode.com/php/monolog/
        $logger = new Logger('main');

        if(!empty($getImages)) {
          $logger->pushHandler(new StreamHandler('path/to/shutterstock-api-requests.log', Logger::DEBUG));
          $logger->info('Successful search: ' . self::API_SEARCH_ENDPOINT  . http_build_query($queryFields));
        } else {
          $logger->pushHandler(new StreamHandler('path/to/shutterstock-api-failed-responses.log', Logger::NOTICE));
          $logger->notice('No data found: ' . self::API_SEARCH_ENDPOINT  . http_build_query($queryFields));
        }
    
    }

    /*
    * @description: Loop through image types 
    */
    public function getImages($data)
    {
      $dataImages = [];

      foreach($data->data as $item)
      {
        foreach($item->assets as $key => $asset_item)
        {
          $dataImages[$item->id][$key][] = array(
            "height" => $asset_item->height,
            "width" => $asset_item->width,
            "url" => $asset_item->url,
        );
        }
      }
      return $dataImages;
    }

    /**
     * 
     * @Route("/shutterstocksearch/?user={query}", name="submit_query", methods={"GET"})
     */
    public function submitQueryAction(Request $request, $user)
    {
        $data = $request->request->all();

        return $this->render('search.html.twig', [
          'getImages' => $getImages,
          'userImageSearch' => $queryFields['query'],
      ]);
    } 
}