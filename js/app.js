var example = angular.module("example", ['ui.router']);

example.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('index', {
	    url: '/index',
	    templateUrl: 'index.html',
	    controller: 'LoginController'
	})
        .state('index', {
	    url: '/index',
	    templateUrl: 'index.html',
	    controller: 'SecureController'
	});
    $urlRouterProvider.otherwise('/index');
});

example.controller("LoginController", function($scope) {

    $scope.login = function() {

        // Change the following to your application's parameters
        var client_id = "227GZJ";
        var redirect_uri = "http%3A%2F%2Fceclnx01.cec.miamioh.edu%2Fusers%2Fhughese%2Fcec205%2F%2Fstreak%2Foauth_callback.html";
        var scope = "activity%20profile";

	window.location.href = "https://www.fitbit.com/oauth2/authorize?client_id=" + client_id + "&response_type=token" + "&scope=" + scope + "&redirect_uri=" + redirect_uri;

    }

});

example.controller("SecureController", function($scope) {

    $scope.accessToken = JSON.parse(window.localStorage.getItem("fitbit")).oauth.access_token;
    $scope.userId = JSON.parse(window.localStorage.getItem("fitbit")).oauth.account_userid;

    // Make calls with the accesstoken either here or with inline js in ../secure.html

    var xhttp;
    if (window.XMLHttpRequest){
       xhttp = new XMLHttpRequest();
    }

    var url = "https://api.fitbit.com/1/user/-/profile.json";

    xhttp.open("GET", url, true);
    xhttp.setRequestHeader("Authorization", "Bearer " + $scope.accessToken);
    xhttp.send();

    xhttp.onreadystatechange = function() {
       if (xhttp.readyState == 4 && xhttp.status == 200) {
          $scope.profile = JSON.parse(xhttp.responseText).user.fullName;

          // Use angular to get the div to change
          var myElement = angular.element( document.querySelector('#username'));
          myElement.text($scope.profile);
       }
    };


});
