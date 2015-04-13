app.service('installService', [ '$http', '$q', function($http, $q) {
    this.GetInstallStatus = function() {
        return $http.get('http://localhost:8080/v1/install')
        .error(function(err) {
            console.log(err);
        });
    };
}]);