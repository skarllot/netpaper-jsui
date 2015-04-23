app.service('languageService', [ '$http', function($http) {
    this.GetLanguages = function() {
        return $http.get(API_ADDRESS + '/v1/languages')
        .error(function(err) {
            console.log(err);
        });
    };
}]);