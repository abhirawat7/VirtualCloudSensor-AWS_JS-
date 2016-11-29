var adminapp = angular.module('adminapp', ['ngRoute']);

adminapp.config(function($routeProvider){
  $routeProvider

  .when('/',{
    templateUrl : 'admin/dashboard_admin.html',
    controller : 'DashboardCtrl'
  })

  .when('/billing',{
    templateUrl : 'admin/billing_admin.html',
    controller : 'BillingCtrl'
  })
  
  .when('/viewsensor',{
    templateUrl : 'admin/viewsensor_user.html',
    controller : 'ViewSensorCtrl'
  })
  
  .when('/viewadminsensor',{
    templateUrl : 'admin/viewsensor_admin.html',
    controller : 'ViewAdminSensorCtrl'
  })
  
  .when('/createsensor',{
    templateUrl : 'admin/createsensor_admin.html',
    controller : 'CreateSensorCtrl'
  })
  
  .when('/map',{
    templateUrl : 'admin/map_admin.html',
    controller : 'MapCtrl'
  })
  
  .when('/profile',{
    templateUrl : 'admin/profile_admin.html',
    controller : 'ProfileCtrl'
  })
  
  .otherwise({redirectTo: '/'});
});

adminapp.factory('Scopes', function ($rootScope) {
    var mem = {};
 
    return {
        store: function (key, value) {
            mem[key] = value;
        },
        get: function (key) {
            return mem[key];
        }
    };
});

//dashboard
adminapp.controller('DashboardCtrl', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from DashboardCtrl");
	
	//session checking
	$http.get('/sessioncheck').success(function(response) {
    console.log("I got the data I requested");
    console.log(response);
	
    if(response == 'not exist'){
		$rootScope.login_user="";
	}
	else{
		$rootScope.login_user=response;
		$http.get('/getSensorCountPerUser/'+$rootScope.login_user).success(function(response1) {
		    console.log("I got the data I requested in getsensorCount");
		    console.log(response1);
			$scope.sensor_total = response1;
			
			
		  });
	}

  });

}]);

//billing
adminapp.controller('BillingCtrl', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from BillingCtrl");
	
	$http.get('/sensorlist_admin').success(function(response) {
		console.log("I got the data I requested");
		console.log(response);
		
		var total=0;
			
			for(var i=0; i<response.length; i++)
			{
				var hours=0;
				var minutes=0;
				
				//convert minutes into hours and minutes format
				hours = parseInt(Number(response[i].duration)/60);
				minutes = parseInt(Number(response[i].duration)%60);
				var duration =hours.toString()+":"+minutes.toString();
				
				//display duration in HH:mm format
				response[i].duration=duration;
				
				//calculate total bill (all sensors)
				total=total + parseFloat(response[i].bill);
			}
		
		$scope.sensor_total = total;
		$scope.sensorlist = response;
		
		
		
		});
		
}]);


//view sensor
adminapp.controller('ViewSensorCtrl', ['$scope', '$http', function($scope, $http) {
    console.log("Hello from ViewSensorCtrl");
	
	var refresh = function() {
		$http.get('/sensorlist_admin').success(function(response) {
		console.log("I got the data I requested");
		
		$scope.sensorlist = response;
		
		});
	}
	refresh();

}]);

//maps
adminapp.controller('MapCtrl', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) { 
	
	console.log("Hello from MapController");


	
	
	$http.get('/physicalsensorlist_admin/'+$rootScope.login_user).success(function(response) {
		console.log("I got the data I requested"+$rootScope.login_user+"check");
		
		var locations=[[response[0].name.toString(),response[0].latitude,response[0].longitude]];
		for(i=1;i<response.length;i++)
		{
			locations.push([response[i].name.toString(),response[i].latitude,response[i].longitude]);

console.log(locations);	
			
		}
		
		
		
	
	
	
/*
	  var locations = [
	      ['san jose',37.307604, -121.568276],
	      ['Coogee Beach', 37.307603, -121.368276, 5],
	      ['Cronulla Beach', 37.307602, -121.268276, 3],
	      ['Manly Beach', 37.307601, -121.168271, 2],
	      ['Maroubra Beach', 37.307600, -121.868276, 1]
	    ];
	*/
	
	
			 var map = new google.maps.Map(document.getElementById('map'), {
				   zoom: 10,
				   center: new google.maps.LatLng(37.307600, -121.868276),
				   mapTypeId: google.maps.MapTypeId.ROADMAP
				 });

				 var infowindow = new google.maps.InfoWindow();

				 var marker, i;

				 for (i = 0; i < locations.length; i++) {  
				   marker = new google.maps.Marker({
				     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				     map: map
				   });

				   google.maps.event.addListener(marker, 'click', (function(marker, i) {
				     return function() {
				       infowindow.setContent(locations[i][0]);
				       infowindow.open(map, marker);
				     }
				   })(marker, i));
				 }
					window.onload = function () {
				 if (! localStorage.justOnce) {
				     localStorage.setItem("justOnce", "true");
				     window.location.reload();
				 }
				}
	
	});
}]);

//view and edit profile
adminapp.controller('ProfileCtrl', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from ProfileCtrl");
	
	$http.get('/userprofile/'+$rootScope.login_user).success(function(response) {
		console.log("I got the data I requested");

		$scope.user = response;
		
	});
	
	
}]);

//create new sensor
adminapp.controller('CreateSensorCtrl', ['$scope', '$http', '$rootScope', '$window', function($scope, $http, $rootScope, $window) {
    console.log("Hello from CreateSensorCtrl");
	
  //createSensor() 
  $scope.createSensor = function() {
		  console.log($scope.sensor);
		  $scope.sensor.adminname = $rootScope.login_user;
		  
		  if($scope.sensor.type == "Bus Sensor" || $scope.sensor.type == "Bus Stop Sensor"){
			$scope.sensor.cost= "0.20";
		  }
		  else{
			$scope.sensor.cost= "0.30";  
		  }
		  $http.post('/createphysicalsensorlist', $scope.sensor).success(function(response) {
			console.log("done");
			console.log(response);
			$window.alert("Sensor added successfully .. !!")
			$scope.sensor="";
		  });
	};
}]);

//view sensor
adminapp.controller('ViewAdminSensorCtrl', ['$scope', '$http','$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from ViewSensorCtrl");
	
		$http.get('/physicalsensorlist_admin/'+$rootScope.login_user).success(function(response) {
		console.log("I got the data I requested");
		
		$scope.sensorlist = response;
		
		});

}]);

