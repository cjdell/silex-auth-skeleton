<?php

namespace App\Model\Repository;

interface ArticleRepositoryInterface
{
    public function loadAllArticles();

    public function loadArticleById($id);

    public function createArticle($fields);

    public function updateArticle($id, $fields);

    public function deleteArticle($id);
}