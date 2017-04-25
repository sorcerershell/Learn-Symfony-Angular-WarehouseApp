'use strict';

angular.module('myApp.customers', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/customers', {
    	templateUrl: 'customers/customers.html',
    	controller: 'CustomersCtrl',
	  	controllerAs: 'customer',
    });
}])
.service('customersAPIService', ['$http', function(http){
	var baseAPI = "http://localhost:8000/api/v1";

    return {
        getCustomers: function() {
            return http({
                method: 'GET',
                url: baseAPI + '/customers'
            });
        },
        saveCustomer: function(customer) {
            return http({
                method: 'POST',
                url: baseAPI + '/customers',
                data: customer
            });
        },
        getCustomer: function(customerId) {
            return http({
                method: 'GET',
                url: baseAPI + '/customers/' + customerId
            });
        },
    }

}])

.controller('CustomersCtrl', ['$scope', 'customersAPIService', function(scope, api) {
	var customer = this;


    // get initial data
	api.getCustomers().then(function(response){
		customer.customersList = response.data.customers;
	});

    customer.refreshList = function() {
        // get initial data
        api.getCustomers().then(function(response){
            customer.customersList = response.data.customers;
        });
    }

    customer.edit = function(customerId) {
        api.getCustomer(customerId).then(function(response) {
            customer.name = response.data.customer.name;
            customer.email = response.data.customer.email;
            customer.address = response.data.customer.email;
            customer.id = response.data.customer.id;
        });
    }

    customer.newCustomer = function() {
        customer.showForm = true;
        customer.formTitle = "New Customer";
    }

    customer.hideForm = function() {
        customer.showForm = false;
        customer.formTitle = "";
    }

    customer.saveCustomer = function() {
        var data = {
            customer: {
                name: customer.name,
                email: customer.email,
                address: customer.address
            }
        };
        api.saveCustomer(data).then(function(response){
            if(response.data.success) {
                customer.hideForm();
                customer.refreshList();
            }
        });
    }

    customer.onDelete = function() {

    }


}]);