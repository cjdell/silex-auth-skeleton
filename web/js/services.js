app.factory('Article', function($resource) {
  var article = $resource('/articles/:id', { id: '@id' }, {
    'update': { method: 'PUT' }
  });

  // We can set default properties like this
  // article.prototype.title = 'I am the default title';

  return article;
});
