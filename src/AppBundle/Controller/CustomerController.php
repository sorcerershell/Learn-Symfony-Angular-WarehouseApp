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
use Warehouse\Customer\RegisterCustomerCommand;

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

        return new JsonResponse(['customers' => $customers], 200);
    }

    /**
     * Register Customer
     * @return array
     *
     * @View()
     * @Post("/api/v1/customer")
     */
    public function postCustomerAction(Request $request)
    {
        $customer = new Customer();

        $form = $this->createForm('AppBundle\Form\CustomerType', $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush($customer);

            return new JsonResponse([
               'success' => true,
                'data' => [
                    'customer_id' => $customer->getId(),
                ]
            ], 200);
        }
        var_dump($form->getErrors());

        return new JsonResponse([
            'success' => false,
            'data' => [],
            'errors' => $form->getErrors(),
        ], 503);
    }

}
