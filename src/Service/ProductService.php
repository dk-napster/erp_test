<?php

namespace App\Service;

use App\Service\FixerApiService;
use App\Entity\Currencies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Product;

class ProductService {

    private $fixerApiService;
    private $em;
    private $validator;

    public function __construct(FixerApiService $fixerApiService, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->fixerApiService = $fixerApiService;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function getActualRate()
    {
        $em = $this->em;

        $query = $em->createQuery("SELECT c FROM App\Entity\Currencies c WHERE c.dateCreated BETWEEN '" . date('Y-m-d') . " 00:00:00' 
                    AND '" . date('Y-m-d') . " 23:59:59'");
        return $query->getOneOrNullResult();
    }

    public function processActualRate()
    {
        if (!$this->getActualRate()) {
            $this->fixerApiService->saveActualRate();
        }
    }

    public function add($data)
    {
        $product = new Product();
        $product->setSku($data['sku'] ?? '');
        $product->setName($data['name'] ?? '');
        $product->setPrice($data['price'] ?? 0);
        $product->setCurrency($data['currency'] ?? '');

        $this->em->persist($product);

        $errors = $this->validator->validate($product);

        if (count($errors) == 0) {
            $this->em->persist($product);
            $this->em->flush();
        }

        return $errors;
    }

    public function getData($params = [])
    {
        $q = "SELECT p FROM App\Entity\Product p";

        if (count($params) > 0) {
            $q .= " WHERE ";
        }

        if (!empty($params['from']) && !empty($params['to'])) {
            $q .= "p.dateCreated BETWEEN '".$params['from']."' AND '".$params['to']."'";
        } else if (!empty($params['from']) && empty($params['to'])) {
            $q .= "p.dateCreated >= '".$params['from']."'";
        } else if (empty($params['from']) && !empty($params['to'])) {
            $q .= "p.dateCreated <= '".$params['to']."'";
        }

        if (!empty($params['currency'])) {
            $q .= "p.currency = '".$params['currency']."'";
        }

        $query = $this->em->createQuery($q);
        $res = $query->getResult();
        $data = [];

        $this->processActualRate();

        $actualRate = $this->getActualRate();


        foreach ($res as $key => $item) {
            $data[$key]['sku'] = $item->getSku();
            $data[$key]['name'] = $item->getName();
            $data[$key]['price'][0]['value'] = $item->getPrice();
            $data[$key]['price'][0]['currency'] = $item->getCurrency();
            if ($actualRate) {
                $data[$key]['price'][1]['value'] = $item->getPrice() / $actualRate->getRate();
                $data[$key]['price'][1]['currency'] =  $actualRate->getBase();
            }
        }
        return $data;
    }
}