var app = angular.module('app', ['ngRoute']);


app.config(function($routeProvider){
  $routeProvider

  .when('/',{
    templateUrl : 'pages/dashboard.html',
    controller : 'DashboardController'
  })

  .when('/billing',{
    templateUrl : 'pages/billing.html',
    controller : 'BillingController'
  })

  .when('/addsensor',{
    templateUrl : 'pages/addsensor.html',
    controller : 'AddSensorController'
  })
  
  .when('/viewsensor',{
    templateUrl : 'pages/viewsensor.html',
    controller : 'ViewSensorController'
  })
  
  .when('/map',{
    templateUrl : 'pages/map.html',
    controller : 'MapController'
  })
  
  .when('/profile',{
    templateUrl : 'pages/profile.html',
    controller : 'ProfileController'
  })
  
  .when('/sensordata',{
    templateUrl : 'pages/sensordata.html',
    controller : 'SensorDataController'
  })
  
  .otherwise({redirectTo: '/'});
});

app.factory('Scopes', function ($rootScope) {
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
app.controller('DashboardController', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from DashboardController");
	
	//session checking
	$http.get('/sessioncheck').success(function(response) {
    console.log("I got the data I requested");
    console.log(response);
	
	if(response.toString() == 'not exist'){
		$rootScope.login_user="";
	}
	else{
		$rootScope.login_user=response.toString();
	}

  });

}]);

//billing
app.controller('BillingController', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from BillingController");
	
	$http.get('/sensorlist/'+ $rootScope.login_user).success(function(response) {
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

//add sensor
app.controller('AddSensorController', ['$scope', '$http', '$window', '$rootScope', function($scope, $http,  $window, $rootScope) {
    console.log("Hello from AddSensorController");
  
  //addSensor() 
  $scope.addSensor = function() {
  console.log($scope.sensor);
  $scope.sensor.state = "Active";
  $scope.sensor.bill = "0.00";
  $scope.sensor.downtime = "";
  $scope.sensor.username = $rootScope.login_user;
  
  if($scope.sensor.type == "Bus Sensor" || $scope.sensor.type == "Bus Stop Sensor"){
	$scope.sensor.cost= "0.20";
  }
  else{
	$scope.sensor.cost= "0.30";  
  }
  $http.post('/sensorlist', $scope.sensor).success(function(response) {
	console.log("done");
    console.log(response);
	$window.alert("Sensor added successfully .. !!")
	$scope.sensor="";
  });
};

}]);

//view sensor
app.controller('ViewSensorController', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from ViewSensorController");
	
	var refresh = function() {
		$http.get('/sensorlist/' + $rootScope.login_user).success(function(response) {
		console.log("I got the data I requested");
		
		for(i=0;i<response.length;i++)
		{
			if(response[i].state == "Active"){
				response[i].button_state = "Deactive";
				console.log("de");
			}
			else{
				response[i].button_state = "Active";
				console.log("ac");
			}
		}
		$scope.sensorlist = response;
		
		});
	}
	
	refresh();

	//delete sensor -> remove button
	$scope.remove = function(id) {
		console.log(id);
		$http.delete('/sensorlist/' + id).success(function(response) {
			refresh();
		});
	};
	
	// active/deactive button
	$scope.updateState = function(id,state,sensor) {
		var temp_state = "";
		console.log(id);
		console.log(state);
		if(state == "Active"){
			temp_state = "Active";
		}
		else{
			temp_state = "Deactive";
		}
		
		//update uptime and downtime
		$http.put('/sensorlisttime/' + id + '/' +temp_state, sensor).success(function(response) {
		console.log(response);
		});
		
		//update status of the sensor
		$http.put('/sensorlist/' + id + '/' +temp_state).success(function(response) {
		console.log(response);
		refresh();
		});
		
		//calculate billing
		if(temp_state == "Deactive"){
			$http.put('/sensorbilling/' + id, sensor).success(function(response) {
			console.log(response);
			});
		}
	};


}]);

//maps
app.controller('MapController', ['$scope', '$http', function($scope, $http) {
    console.log("Hello from MapController");
	

}]);

//view and edit profile
app.controller('ProfileController', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from ProfileController");
	
	$http.get('/userprofile/'+$rootScope.login_user).success(function(response) {
		console.log("I got the data I requested");

		$scope.user = response;
		
	});
	
}]);


//fetch sensor data
app.controller('SensorDataController', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
    console.log("Hello from SensorDataController");
	
	$http.get('/sensorlist/' + $rootScope.login_user).success(function(response) {
		console.log("I got the data I requested");
		
		$scope.sensorlist = response;
		
		});
		
		$scope.getData = function(group,type) {
			$http.get('/getsensordata/'+group+'/'+type).success(function(response) {
			console.log("I got the data I requested");
 
			response.state = " State : " + response.state;
			if(response.speed == undefined){
				response.speed = "-"
			}
			response.speed = "Speed : " + response.speed;
			if(response.traffic == undefined){
				response.traffic = "-"
			}
			response.traffic = "Traffic : "+ response.traffic;
			if(response.delay == undefined){
				response.delay = "-"
			}
			response.delay = "Delay : "+ response.delay;
			if(response.last_bus_num == undefined){
				response.last_bus_num = "-"
			}
			response.last_bus_num = "Previous Bus Number : " + response.last_bus_num;
			if(response.next_bus_num == undefined){
				response.next_bus_num = "-"
			}
			response.next_bus_num = "Next Bus Number : " + response.next_bus_num;
			response.latitude = "Latitude : " + response.latitude;
			response.longitude = "Longitude : " + response.longitude;
			
			$scope.sensor = response;
		
	});
		
		};
		

}]);