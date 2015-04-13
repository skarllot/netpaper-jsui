app.controller('navbarController', [ '$scope', '$location', function($scope, $location) {
    $scope.isView = function(path, isPrefix) {
        isPrefix = isPrefix || false;
        if (!isPrefix) {
            return path === $location.path();
        } else {
            return $location.path().indexOf(path) === 0;
        }
    };
    $scope.navCollapsed = true;
    $scope.toggleNav = function() {
        if (window.innerWidth < 768) {
            $scope.navCollapsed = !$scope.navCollapsed;
        }
    };
}]);
