var app = angular.module('silex-auth-skeleton', ['ui.router', 'ngResource']);

app.run(function ($rootScope) {
  // Invoked when the count of a particular record type is discovered
  $rootScope.$on("itemCountDiscovered", function(e, type, count) {
    $rootScope.itemCounts = $rootScope.itemCounts || {};

    $rootScope.itemCounts[type] = count;
  });
});

app.config(function($stateProvider, $urlRouterProvider, $httpProvider, $interpolateProvider) {
  // $interpolateProvider.startSymbol('{[{').endSymbol('}]}');

  $urlRouterProvider.otherwise('/dashboard');

  $stateProvider
    .state('dashboard', {
      url: '/dashboard',
      templateUrl: 'client-views/dashboard/index.html'
    })

    .state('articles', {
      url: '/articles',
      templateUrl: 'client-views/articles/index.html',
      controller: 'ArticlesController',
      abstract: true
    })
    .state('articles.view', {
      url: '/{id:[0-9]+}',
      templateUrl: 'client-views/articles/view.html',
      controller: 'ArticleController'
    })
    .state('articles.new', {
      url: '/new',
      templateUrl: 'client-views/articles/view.html',
      controller: 'ArticleController'
    })

    .state('another', {
      url: '/another',
      template: '<h3>Another record could go here</h3>'
    });
});
