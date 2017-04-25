<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{
    /**
     * Get all customers
     * @return array
     *
     * @View()
     * @Get("/api/v1/customers/")
     */
    public function getCustomersAction()
    {
        $customers = $this->getDoctrine()->getRepository('AppBundle:Customer')->findAll();

        $rows = [];
        foreach($customers as $customer) {
            $rows[] = [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
                'address' => $customer->getAddress(),
            ];
        }

        $response = new JsonResponse(['customers' => $rows], 200);

        return $response;
    }

    /**
     * Get customer
     * @return array
     *
     * @View()
     * @Get("/api/v1/customer/{customerId}")
     */
    public function getCustomerAction($customerId)
    {
        $customer = $this->getDoctrine()->getRepository('AppBundle:Customer')->find($customerId);

        if($customer) {
            return new JsonResponse(['customer' => $customer], 200);
        }

        return new JsonResponse(['error' =>['Customer Not Found']], 404);
    }

    /**
     * Register Customer
     * @return array
     *
     * @View()
     * @Post("/api/v1/customers")
     */
    public function postCustomerAction(Request $request)
    {
        $customer = new Customer();

        $data = json_decode($request->getContent());

        $customer->setName($data->customer->name);
        $customer->setEmail($data->customer->email);
        $customer->setAddress($data->customer->address);

        $validator = $this->container->get('validator');

        $errors = $validator->validate($customer);

        if(count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'data' => [],
                'errors' => (string)$errors,
            ], 500);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($customer);
        $em->flush();



        return new JsonResponse([
            'success' => true,
            'data' => [ 'customerId' => $customer->getId() ],
        ], 200);
    }


    /**
     * Delete Customer
     * @return array
     *
     * @Delete("/api/v1/customer/{customerId}")
     */
    public function deleteCustomerAction($customerId)
    {
        $customer = $this->getDoctrine()->getRepository('AppBundle:Customer')->find($customerId);

        if($customer) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($customer);
            $em->flush();

            return new JsonResponse([
                'success' => true,
            ], 200);
        }

        return new JsonResponse([
            'success' => false,
        ], 404);
    }

}
