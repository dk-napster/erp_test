<?php
// src/Service/FixerApiService.php
namespace App\Service;

use App\Entity\Currencies;
use Doctrine\ORM\EntityManagerInterface;

class FixerApiService
{
    const API_KEY = '7ef40a406d48ba33f975aac500c0d18f';

    private $relatedCurrency = 'CNY';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getActualRate()
    {
        $url = "http://data.fixer.io/api/latest";
        $url .= "?access_key=".self::API_KEY;
        $url .= "&symbols=".$this->relatedCurrency;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        $rawdata = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        return ['result' => json_decode($rawdata, true), 'curl_error' => $err];
    }

    public function saveActualRate()
    {
        $actualRate = $this->getActualRate();
        if (!empty($actualRate['curl_error'])) {
            return false;
        }

        if (!empty($actualRate['result']['success'] && $actualRate['result']['success'] === true)) {
            $currency = new Currencies;
            $currency->setBase($actualRate['result']['base']);
            $currency->setRelated(key($actualRate['result']['rates']));
            $currency->setRate($actualRate['result']['rates'][key($actualRate['result']['rates'])]);
            $date = new \DateTime();
            $date->setTimestamp(strtotime($actualRate['result']['date']));
            $currency->setDateCreated($date);
            $date = new \DateTime();
            $date->setTimestamp((int) $actualRate['result']['timestamp']);
            $currency->setTimestampCreated($date);

            $entityManager = $this->em;
            $entityManager->persist($currency);
            $entityManager->flush();
        }

    }
}