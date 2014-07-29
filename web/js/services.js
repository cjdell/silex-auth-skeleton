app.factory('AuthService', function($http) {
  return {
    isAuthenticated: function() {
      return !!$http.defaults.headers.common['API-Key'];
    },
    signIn: function(username, password, callback) {
      $http({ 
        method: 'POST', 
        url: '/auth/get_api_key',
        headers: {
          'Content-Type': 'application/json'
        },
        data: {
          username: username,
          password: password
        }
      })
      .success(function(data, status, headers, config) {
        $http.defaults.headers.common['API-Key'] = data.api_key;

        callback();
      })
      .error(function(data, status, headers, config) {
        alert(JSON.stringify(data));
      });
    },
    signOut: function(callback) {
      delete $http.defaults.headers.common['API-Key'];

      if (callback) callback();
    },
    getApiToken: function() {
      return apiToken;
    }
  }
});

app.factory('Article', function($resource) {
  var article = $resource('/api/articles/:id', { id: '@id' }, {
    'update': { method: 'PUT' }
  });

  // We can set default properties like this
  // article.prototype.title = 'I am the default title';

  return article;
});
