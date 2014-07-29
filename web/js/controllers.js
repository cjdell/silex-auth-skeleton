app.controller('SignInController', function($scope, $state, $stateParams, AuthService) {
  console.log($stateParams);

  $scope.signIn = function() {
    AuthService.signIn($scope.user.username, $scope.user.password, signedIn);
  }

  function signedIn() {
    $state.go($stateParams.attemptedStateName, JSON.parse(atob(decodeURI($stateParams.attemptedStateParams))));
  }
});

app.controller('ArticlesController', function($scope, $state, $stateParams, Article) {
  function init() {
    $scope.refresh();
  }

  // These functions have been exposed to the view via the scope
  $scope.refresh = function() {
    $scope.articles = Article.query({}, loaded, fail);  
  };

  // Callbacks are declared privately
  function loaded() {
    console.table($scope.articles, ['id', 'title', 'tags', 'content']);

    $scope.$emit('itemCountDiscovered', 'Article', $scope.articles.length);
  }

  function fail(err) {
    console.error(err);
  }

  $scope.$on('item-saved', $scope.refresh);

  $scope.$on('item-deleted', $scope.refresh);

  // All functions defined, initialise the controller
  init();
});

app.controller('ArticleController', function($scope, $state, $stateParams, Article) {
  function init() {
    // If there is an ID, load the record from the API, otherwise, start with a new instance
    if ($stateParams.id !== undefined) {
      $scope.load($stateParams.id);
    }
    else {
      $scope.article = new Article(); loaded();
    }
  }

  // These functions have been exposed to the view via the scope
  $scope.new = function() {
    $state.go('articles.new');
  };

  $scope.load = function(id) {
    $scope.article = Article.get({ id: id }, loaded, fail);
  };

  $scope.save = function() {
    if (validate()) {
      if ($stateParams.id !== undefined) {
        $scope.article.$update(saved);
      }
      else {
        $scope.article.$save(saved);
      }
    }
  };

  $scope.delete = function() {
    $scope.article.$delete(deleted);
  };

  function validate() {
    // TODO: Do client side validation here...
    return true;
  }

  // Callbacks are declared privately
  function loaded() {
    console.log('Loaded Article:', $scope.article);

    $scope.ready = true;
  }

  function fail(err) {
    console.error(err);
  }

  function saved() {
    console.log('Saved Article:', $scope.article);

    // Broadcast saved event
    $scope.$emit('item-saved', $scope.article);

    // Need to go to a new state if saving new record
    if ($stateParams.id === undefined) {
      $state.go('articles.view', { id: $scope.article.id });
    }
  }

  function deleted() {
    console.log('Deleted Article:', $scope.article);

    // Broadcast deleted event
    $scope.$emit('item-deleted', $scope.article);

    // Show only the list
    $state.go('articles');
  }

  // All functions defined, initialise the controller
  init();
});
