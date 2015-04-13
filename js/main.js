var app = angular.module('netpaper', [ 'ngRoute', 'ngResource', 'ui.bootstrap' ]);

app.config([ '$routeProvider', '$controllerProvider', function($routeProvider, $controllerProvider) {
    // Lazy loading
    app.registerCtrl = $controllerProvider.register;
    // ------------

    $routeProvider
    .when('/', {
        title: 'Home',
        templateUrl: 'views/home.html'
    })
    .when('/install', {
        title: 'Install',
        templateUrl: 'views/install.html'
    })
    .otherwise({
        redirectTo: '/'
    });
}]);

app.run(['$location', '$rootScope', 'installService', function($location, $rootScope, installService) {
    $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
        $rootScope.title = current.$$route.title;
    });
    
    installService.GetInstallStatus().success(function(data) {
        if (!data) {
            $location.url('/install');
        }
    });
}]);