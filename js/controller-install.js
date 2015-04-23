app.registerCtrl('installController', [ '$scope', 'languageService', 'installService', function($scope, languageService, installService) {
    $scope.user = user;
    
    languageService.GetLanguages().success(function(data) {
        $scope.languages = data;
        $scope.user.language = data[0];
    });
    
    $scope.createUser = function() {
        $scope.user.isAdmin = true;
        installService.CreateFirstUser($scope.user);
    };
}]);