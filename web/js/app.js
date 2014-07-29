var app = angular.module('silex-auth-skeleton', ['ui.router', 'ngResource']);

app.run(function ($rootScope, $state, AuthService) {
  // Invoked when the count of a particular record type is discovered
  $rootScope.$on("itemCountDiscovered", function(e, type, count) {
    $rootScope.itemCounts = $rootScope.itemCounts || {};

    $rootScope.itemCounts[type] = count;
  });

  $rootScope.$on("$stateChangeStart", function(event, toState, toParams, fromState, fromParams) {
    // Check user is authenticated...
    if (toState.authenticate && !AuthService.isAuthenticated()) {
      
      // Log the state we were attepting to access
      toParams.attemptedStateParams = encodeURI(btoa(JSON.stringify(toParams)));
      toParams.attemptedStateName = toState.name;

      // Go to the signin state (login view)
      $state.go("sign-in", toParams);

      // Don't do the thing we were going to do
      event.preventDefault(); 
    }
  });

  // App-wide accessible method for signing out
  $rootScope.signOut = function() {
    AuthService.signOut(function() {
      $state.go('dashboard');
    });
  };
});

app.config(function($stateProvider, $urlRouterProvider, $httpProvider, $interpolateProvider) {
  // $interpolateProvider.startSymbol('{[{').endSymbol('}]}');

  $urlRouterProvider.otherwise('/dashboard');

  $stateProvider
    .state('dashboard', {
      url: '/dashboard',
      templateUrl: 'client-views/dashboard/index.html'
    })

    .state('sign-in', {
      url: '/sign-in/:attemptedStateName/:attemptedStateParams',
      templateUrl: 'client-views/auth/sign-in.html',
      controller: 'SignInController'
    })

    .state('articles', {
      url: '/articles',
      templateUrl: 'client-views/articles/index.html',
      controller: 'ArticlesController',
      abstract: true,
      authenticate: true
    })
    .state('articles.view', {
      url: '/{id:[0-9]+}',
      templateUrl: 'client-views/articles/view.html',
      controller: 'ArticleController',
      authenticate: true
    })
    .state('articles.new', {
      url: '/new',
      templateUrl: 'client-views/articles/view.html',
      controller: 'ArticleController',
      authenticate: true
    })

    .state('another', {
      url: '/another',
      template: '<h3>Another record could go here</h3>'
    });
});
