(function() {

	'use strict';

	/*******************************************************************************************
	 * CartController controls the cart view
	 *******************************************************************************************/

	var CartController = function($scope, $location, WebService, LogService, CartService) {

		$scope.cart_error = 'Ingen feil';

		WebService.connect('ws://linode2.biblionaut.net:8080');

		function found_book(item) {
			console.log("-[cart]---------------------------------------");
			console.log("Found book: " + item.id);
			CartService.add(item);
		}

		function onWebSocketMessageReceived(e, data) {

			/* Example response: {
				"msg": "new-tag",
				"item": {
					"uid": "75FE9C16500104E0",
					"library": "1030310",
					"nparts": 1,
					"usage_type": "for-circulation",
					"id": "044955NA0",
					"is_blank": false,
					"crc_ok": true,
					"partno": 1,
					"country": "NO",
					"crc": "1971",
					"error": ""
				}, "rcpt": "frontend", "uid": "75FE9C16500104E0"}
			 */

			if (data.msg == 'new-tag') {
				//found_tag(data.item);
				if (data.item.usage_type == 'for-circulation') {

					$scope.$apply(found_book(data.item));

				}
			}

		}

		function onCartChanged(e) {

			$scope.books = CartService.contents;

			console.log('> on Cart changed, cart contains ' + $scope.books.length + ' items');

			// Propagate error
			$scope.cart_error = CartService.last_error;

			if ($scope.books.length > 0) {
				$('#borrowing-cart .item:last').slideDown('fast', function() {
					var $t = $('#borrowing-cart');
					$t.animate({"scrollTop": $('#borrowing-cart')[0].scrollHeight}, "slow");
				});
			}
		}

		$scope.continue = function() {
			$location.path('/checkout');
		}

		$scope.cancel = function() {
			CartService.clear();
			$location.path('/');
		}
		// controller receiving broadcast event from WebService (bubbles up to $scope from $rootScope)

		var messageListener = $scope.$on("messageReceived", onWebSocketMessageReceived),
			cartChangedListener = $scope.$on("cartChanged", onCartChanged);

		// In case there is already something in the cart
		onCartChanged();

		console.log("CartController initialized");

	};

	/*******************************************************************************************
	 * CartService keeps books until checkout is complete
	 *******************************************************************************************/

	var CartService = function($rootScope, LogService, HttpService) {

		var that = this;

		// Keeps the contents of the cart
		this.contents = [];

		this.last_error = '';

		// Clears the cart
		this.clear = function() {
			this.contents = [];
		};

		// Check if an item is present in cart
		this.has = function(id) {
			for (var i = this.contents.length - 1; i >= 0; i--) {
				if (this.contents[i].cardData.id == id) return true;
			};
			return false;
		};

		// Add an item using RFID card data
		this.add = function(cardData) {
			if (this.has(cardData.id)) {
				console.log('Already in cart: ' + cardData.id);
				return;
			}
			console.log('Added to cart : ' + cardData.id);

			HttpService.neverEverGiveUp({ method: 'GET', url: '/documents/show/' + cardData.id })
				.then(function(d) {
					console.log('-------');
					console.log('Got some metadata back');
					console.log(d);
					console.log('-------');
					if (d.error) {

						LogService.log('Boka ble ikke funnet i BibCraft-systemet: ' + d.error, 'error');
						that.last_error = 'Boka ble ikke funnet i BibCraft-systemet';
						$rootScope.$broadcast('cartChanged');

					} else if (d.loans.length !== 0) {

						LogService.log('Boka er allerede utlånt!', 'error');
						that.last_error = 'Boka er allerede utlånt';
						$rootScope.$broadcast('cartChanged');

						// TODO: Gå til ny skjerm og vis utlånsfrist :)

					} else {

						that.contents.push({
							cardData: cardData,
							catalogueData: d
						});

						// Broadcast an event
						console.log(':: Broadcast : Cart changed')
						that.last_error = '';
						$rootScope.$broadcast('cartChanged');

					}

				});

		};

		console.log('Created CartService');

	};

	CartController.$inject = ['$scope', '$location', 'WebService', 'LogService', 'CartService'];
	CartService.$inject = ['$rootScope', 'LogService', 'HttpService'];

	angular.module('bibcraft.selfservice.cart', ['bibcraft.selfservice.services', 'bibcraft.selfservice.user'])
	  .controller('CartController', CartController)
	  .service('CartService', CartService);

}());
