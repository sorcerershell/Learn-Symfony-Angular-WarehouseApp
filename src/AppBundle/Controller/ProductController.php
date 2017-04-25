<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/23/17
 * Time: 5:26 AM
 */

namespace AppBundle\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Controller\Traits\FractalTrait;

class ProductController extends Controller
{
    use FractalTrait;

    /**
     * Get all products
     * @return array
     *
     * @View()
     * @Get("/api/v1/products/")
     */
    public function getProductsAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();

        $resource = new Collection($products, new \AppBundle\Transformer\ProductTransformer());

        return new JsonResponse(
            $this->transform($resource)->toArray(), 200);
    }

    /**
     * Get all Product Types
     * @return array
     *
     * @View
     * @Get("/api/v1/product-types/")
     */
    public function getProductTypesAction()
    {
        $productTypes = Product::getTypes();

        $types = [];
        foreach($productTypes as $key => $val) {
            $types[] = [
                'key' => $key,
                'value' => $val,
            ];
        }

        return new JsonResponse([
            'data' => $types,
        ], 200);
    }

    /**
     * Get product
     * @return array
     *
     * @View()
     * @Get("/api/v1/product/{productId}")
     */
    public function getProductAction($productId)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($productId);

        $resource = new Item($product, new \AppBundle\Transformer\ProductTransformer());

        if($product) {
            return new JsonResponse(
                $this->transform($resource)->toArray(),
                200);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => "No Product Found"
        ], 404);
    }

    /**
     * New product
     * @return array
     *
     * @View()
     * @Post("/api/v1/products/")
     */
    public function postProductAction(Request $request)
    {
        $product = new Product();

        $data = json_decode($request->getContent());
        $product->setName($data->product->name);
        $product->setType($data->product->type);

        $validator = $this->container->get('validator');

        $errors = $validator->validate($product);

        if(count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'data' => [],
                'errors' => (string)$errors,
            ], 500);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($product);
        $em->flush();



        return new JsonResponse([
            'success' => true,
            'data' => [ 'productId' => $product->getId() ],
        ], 200);
    }

    /**
     * Update existing product
     * @return array
     *
     * @View()
     * @Post("/api/v1/product/{productId}")
     */
    public function postUpdateProductAction(Request $request, $productId)
    {
        $data = json_decode($request->getContent());

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($productId);

        if(!$product) {
            return new JsonResponse([
                'success' => false,
                'errors' => 'No Product Found',
            ], 404);
        }

        $product->setName($data->product->name);
        $product->setType($data->product->type);

        $validator = $this->container->get('validator');

        $errors = $validator->validate($product);

        if(count($errors) > 0) {
            return new JsonResponse([
                'success' => false,
                'errors' => (string)$errors,
            ], 500);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($product);
        $em->flush();



        return new JsonResponse([
            'success' => true,
            'data' => [
                'productId' => $product->getId(),
                'product' => $product,
            ],
        ], 200);
    }



    /**
     * Delete Product
     * @return array
     *
     * @Delete("/api/v1/product/{productId}")
     */
    public function deleteProductAction($productId)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($productId);

        if($product) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($product);
            $em->flush();

            return new JsonResponse([
                'success' => true,
            ], 200);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => 'Product not found'
        ], 404);
    }

    /**
     * Stock In Product
     * @return array
     *
     * @Post("/api/v1/product/{productId}/stock-in")
     */
    public function postStockInProductAction(Request $request, $productId)
    {

        $data = json_decode($request->getContent());

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($productId);

        if(!$product) {
            return new JsonResponse([
                'success' => false,
                'errors' => 'No Product Found',
            ], 404);
        }


    }




}