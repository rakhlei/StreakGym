var example = angular.module("example", ['ui.router']);

example.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('login', {
	    url: '/login',
	    templateUrl: 'web/login.html',
	    controller: 'LoginController'
	})
        .state('secure', {
	    url: '/secure',
	    templateUrl: 'web/secure.php',
	    controller: 'SecureController'
	});
    $urlRouterProvider.otherwise('/login');
});

example.controller("LoginController", function($scope) {

    $scope.login = function() {

        // ALFB folder
        var client_id = "227NKJ";
        var redirect_uri = "http%3A%2F%2Fceclnx01.cec.miamioh.edu%2Fusers%2Fasburywg%2Falfb%2Foauth_callback.html";
        var scope = "activity%20profile";

	window.location.href = "https://www.fitbit.com/oauth2/authorize?client_id=" + client_id + "&response_type=token" + "&scope=" + scope + "&redirect_uri=" + redirect_uri+"&prompt=login consent";

    }

});

example.controller("SecureController", function($scope, $http) {

    $scope.accessToken = JSON.parse(window.localStorage.getItem("fitbit")).oauth.access_token;
    $scope.userId = JSON.parse(window.localStorage.getItem("fitbit")).oauth.account_userid;
	
	// ACTIVITY GET CALLS
    var xhttp2;
    if (window.XMLHttpRequest){
       xhttp2 = new XMLHttpRequest();
    }
	
	// one month step log starting today
    var url2 = "https://api.fitbit.com/1/user/-/activities/steps/date/today/1m.json";

    xhttp2.open("GET", url2, true);
    xhttp2.setRequestHeader("Authorization", "Bearer " + $scope.accessToken);
    xhttp2.send();

    xhttp2.onreadystatechange = function() {
       if (xhttp2.readyState == 4 && xhttp2.status == 200) {

			var logdata = xhttp2.responseText;
		    $scope.newData = logdata.replace("activities-steps","activities");
			$scope.log();
       }
    };
	
	// PROFILE GET CALLS
    var xhttp3;
    if (window.XMLHttpRequest){
       xhttp3 = new XMLHttpRequest();
    }

    var url = "https://api.fitbit.com/1/user/-/profile.json";

    xhttp3.open("GET", url, true);
    xhttp3.setRequestHeader("Authorization", "Bearer " + $scope.accessToken);
    xhttp3.send();

    xhttp3.onreadystatechange = function() {
       if (xhttp3.readyState == 4 && xhttp3.status == 200) {
		   
		// Fitbit's Unique ID
		$scope.id = JSON.parse(xhttp3.responseText).user.encodedId;
		var myElement = angular.element( document.querySelector('#fbid'));
		myElement.text($scope.id);
		$scope.currentUser($scope.id);
		
		// do this in current.php instead.
	//	$scope.idc = "fbid";
	//	$scope.users($scope.id, $scope.id, $scope.idc);
	   
		// Full name
		$scope.name = JSON.parse(xhttp3.responseText).user.fullName;
        var myElement = angular.element( document.querySelector('#username'));
        myElement.text($scope.name);
		
		$scope.resc = "fullname";
		$scope.users($scope.id, $scope.name, $scope.resc);
		   
		// Profile Avatar
		$scope.avatar = JSON.parse(xhttp3.responseText).user.avatar;
        var myElement = angular.element( document.querySelector('#avatar'));
        myElement.text($scope.avatar);

		// Height
		$scope.height = JSON.parse(xhttp3.responseText).user.height;
        var myElement = angular.element( document.querySelector('#height'));
        myElement.text($scope.height);
		
		$scope.hc = "height";
		$scope.users($scope.id, $scope.height, $scope.hc);
			
		// DOB
		$scope.dateOfBirth = JSON.parse(xhttp3.responseText).user.dateOfBirth;
		var myElement = angular.element( document.querySelector('#dateOfBirth'));
        myElement.text($scope.dateOfBirth);

		$scope.birthc = "birth";
		$scope.users($scope.id, $scope.dateOfBirth, $scope.birthc);
			
		// About Me
		$scope.aboutMe = JSON.parse(xhttp3.responseText).user.aboutMe;
        var myElement = angular.element( document.querySelector('#aboutMe1'));
        myElement.text($scope.aboutMe);

		$scope.abtme = "aboutme";
		$scope.users($scope.id, $scope.aboutMe, $scope.abtme);

       }
    };
	
	$scope.errors = [];
    $scope.msgs = [];
	
	// Updates 'log' table with the current user's 1m step log
    $scope.log = function(){
		$scope.errors.splice(0, $scope.errors.length); // remove all error messages
		$scope.msgs.splice(0, $scope.msgs.length);
		$http.post('log.php', {'response': $scope.newData}
		).success(function(data, status, headers, config) {
			if (data.msg != '')
				{
					$scope.msgs.push(data.msg);
					}
					else
                        {
                            $scope.errors.push(data.error);
                        }
                    }).error(function(data, status) { // called asynchronously if an error occurs
                        $scope.errors.push(status);
                    });
   }
	

	// Updates the 'users' table with value (x1) into column (x2)
     $scope.users = function($id, $value, $col){
		$scope.errors.splice(0, $scope.errors.length);
        $scope.msgs.splice(0, $scope.msgs.length);
		$http.post('users.php', {'id': $id, 'response': $value, 'column':$col}
            ).success(function(data, status, headers, config) {
                if (data.msg != '')
                {
					$scope.msgs.push(data.msg);
                }
                else
                {
                    $scope.errors.push(data.error);
				}
                }).error(function(data, status) { 
                    $scope.errors.push(status);
                });
   }
   
   // Update the 'current' table with the current user on the site
	$scope.currentUser = function($id){
		$scope.errors.splice(0, $scope.errors.length);
		$scope.msgs.splice(0, $scope.msgs.length);
        $http.post('current.php', {'id': $id}
            ).success(function(data, status, headers, config) {
                if (data.msg != '')
                {
					$scope.msgs.push(data.msg);
				}
                else
                {
                    $scope.errors.push(data.error);
               }
               }).error(function(data, status) {
                        $scope.errors.push(status);
               });
	}
   
   });


