'use strict';

angular.module('myApp.products', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/products', {
            templateUrl: 'products/products.html',
            controller: 'ProductsCtrl',
            controllerAs: 'products',
        });
    }])
    .service('productsAPIService', ['$http', function(http){
        var baseAPI = "http://localhost:8000/api/v1";

        return {
            getProducts: function() {
                return http({
                    method: 'GET',
                    url: baseAPI + '/products/'
                });
            },

            saveProduct: function(data) {
                var url = baseAPI + '/products/';
                if(data.product.id) {
                    var url = baseAPI + '/product/' + data.product.id;
                }

                console.log(data);
                return http({
                    method: 'POST',
                    url: url,
                    data: data
                });
            },
            getProduct: function(productId) {
                return http({
                    method: 'GET',
                    url: baseAPI + '/product/' + productId
                });
            },
            getProductTypes: function() {
                return http({
                    method: 'GET',
                    url: baseAPI + '/product-types/'
                });
            }

        }

    }])

    .controller('ProductsCtrl', ['$scope', 'productsAPIService', function(scope, api) {
        var ctrl = this;

        ctrl.showListing = true;
        ctrl.showForm = false;
        ctrl.stockInForm = false;
        ctrl.stockOutForm = false;


        // get initial data
        api.getProducts().then(function(response){
            ctrl.productsList = response.data.data;
        });

        api.getProductTypes().then(function(response){
            ctrl.productTypes = response.data.data;
        });

        ctrl.refreshList = function() {
            api.getProducts().then(function(response){
                ctrl.productsList = response.data.data;
            });
        }

        ctrl.editProduct = function(productId) {
            api.getProduct(productId).then(function(response) {
                ctrl.product = {
                    id: response.data.data.id,
                    name: response.data.data.name,
                    type: response.data.data.type
                }


            });
            ctrl.editForm();
        }

        ctrl.resetForm = function() {
            ctrl.product.name = "";
            ctrl.product.type = "";
        }

        ctrl.newForm = function() {
            ctrl.showForm = true;
            ctrl.showListing = false;
            ctrl.formTitle = "New Product";
        }

        ctrl.editForm = function() {
            ctrl.showForm = true;
            ctrl.showListing = false;
            ctrl.formTitle = "Edit Product";
        }

        ctrl.hideForm = function() {
            ctrl.showForm = false;
            ctrl.showListing = true;
            ctrl.formTitle = "";
        }

        ctrl.showStockInForm = function() {
            ctrl.showListing = false;
            ctrl.stockInForm = true;
            ctrl.stockOutForm = false;

        }

        ctrl.hideStockInForm = function() {
            ctrl.stockInForm = false;
            ctrl.stockOutForm = false;
            ctrl.showListing = true;

        }


        ctrl.showStockOutForm = function() {
            ctrl.stockInForm = false;
            ctrl.stockOutForm = true;
            ctrl.showListing = false;
        }

        ctrl.hideStockOutForm = function() {
            ctrl.stockInForm = false;
            ctrl.stockOutForm = false;
            ctrl.showListing = true;
        }



        ctrl.saveProduct = function() {

            ctrl.productsList = _.concat(ctrl.productsList, {
                name: ctrl.product.name,
                price: ctrl.product.price,
                type: ctrl.product.type,
                in_stock: 0
            });

            ctrl.hideForm();

            var data = {
                product: {
                    id: ctrl.product.id,
                    name: ctrl.product.name,
                    type: ctrl.product.type
                }
            };
            api.saveProduct(data).then(function(response){
                if(response.data.success) {
                    ctrl.hideForm();
                    ctrl.refreshList();
                    ctrl.resetForm();
                }
            });
        }

        ctrl.onDelete = function() {

        }


    }]);