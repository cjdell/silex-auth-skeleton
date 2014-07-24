<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\Request,
    App\Model\Entity\Article,
    App\Model\Repository\ArticleRepositoryInterface;

class ArticleController
{
    public function indexAction(ArticleRepositoryInterface $ar, Request $req)
    {
        $articles = $ar->loadAllArticles();

        return new JsonResponse($articles);
    }

    public function createAction(ArticleRepositoryInterface $ar, Request $req)
    {
        $article = $ar->createArticle($req->data);

        return new JsonResponse($article);
    }

    public function retrieveAction(ArticleRepositoryInterface $ar, Request $req, $id)
    {
        $article = $ar->loadArticleById($id);

        return new JsonResponse($article);
    }

    public function updateAction(ArticleRepositoryInterface $ar, Request $req, $id)
    {
        $article = $ar->updateArticle($id, $req->data);
        
        return new JsonResponse($article);
    }

    public function deleteAction(ArticleRepositoryInterface $ar, Request $req, $id)
    {
        $ar->deleteArticle($id);

        return new JsonResponse([ 'message' => 'success' ]);
    }
}