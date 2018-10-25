<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\ProductService;

class ApiController extends AbstractController
{

    public function add(Request $request, ProductService $productService)
    {
        try {
            $data = [
                'sku' => $request->request->get('sku'),
                'name' => $request->request->get('name'),
                'price' => $request->request->get('price')['value'] ?? 0,
                'currency' => $request->request->get('price')['currency'] ?? '',
            ];

            $errors = $productService->add($data);
            if (count($errors) == 0) {
                $response = [
                    'success' => true,
                    'payload' => $request->request->all()
                ];
            } else {
                $preparedErrors = [];
                foreach ($errors as $error) {
                    $preparedErrors[$error->getPropertyPath()][] = $error->getMessage();
                }
                $response = [
                    'success' => false,
                    'errors' => $preparedErrors
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'errors' => $e->getMessage()
            ];
        }
        return $this->json($response);
    }

    public function index(ProductService $productService, Request $request)
    {
        try {
            $errors = [];
            if (array_diff($request->query->all(), ['from', 'to', 'currency'])) {
                $errors[] = 'Unknown parameters';
            }
            if (!empty($request->query->get('from')) && \DateTime::createFromFormat('Y-m-d G:i:s', $request->query->get('from')) === FALSE) {
                $errors[] = 'Parameter "from" is not valid';
            }
            if (!empty($request->query->get('to')) && \DateTime::createFromFormat('Y-m-d G:i:s', $request->query->get('to')) === FALSE) {
                $errors[] = 'Parameter "to" is not valid';
            }
            if (!empty($request->query->get('currency')) && !in_array($request->query->get('currency'), ['EUR', 'CNY'])) {
                $errors[] = 'Parameter "currency" is not valid';
            }

            if (!empty($errors)) {
                $response = [
                    'success' => false,
                    'errors' => $errors
                ];
            } else {
                $res = $productService->getData($request->query->all());
                $response = [
                    'success' => true,
                    'payload' => $res
                ];
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'errors' => $e->getMessage()
            ];
        }
        return $this->json($response);
    }

}