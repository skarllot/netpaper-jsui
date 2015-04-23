var app = angular.module('netpaper', [ 'ngRoute', 'ngResource', 'ui.bootstrap' ]);

app.config([ '$routeProvider', '$controllerProvider', '$httpProvider', function($routeProvider, $controllerProvider, $httpProvider) {
    // Lazy loading
    app.registerCtrl = $controllerProvider.register;
    // ------------
    $httpProvider.defaults.useXDomain = true;

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

app.run(['$location', '$rootScope', 'installService', 'languageService', function($location, $rootScope, installService, languageService) {
    $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
        $rootScope.title = current.$$route.title;
    });
    
    installService.GetInstallStatus().success(function(data) {
        if (!data) {
            $location.url('/install');
        }
    });
}]);