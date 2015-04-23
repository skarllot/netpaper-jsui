app.service('installService', [ '$http', function($http) {
    this.GetInstallStatus = function() {
        return $http.get(API_ADDRESS + '/v1/install')
        .error(function(data) {
            console.error(data);
        });
    };
    this.CreateFirstUser = function(user) {
        /*$http({
            url: API_ADDRESS + '/v1/install',
            method: 'POST',
            data: user,
            //withCredentials: true,
            headers: { 'Content-Type': 'application/json; charset=utf-8' }
        })*/
        $http.post(API_ADDRESS + '/v1/install', user)
        .success(function(data) {
            console.info(data);
        })
        .error(function(data) {
            console.error(data);
        });
    };
}]);