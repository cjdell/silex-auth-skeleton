<?php

namespace App\Model\Repository;

use Doctrine\ORM\NoResultException,
    Doctrine\ORM\EntityRepository,
    App\Model\Entity\Article;

/**
 * Class ArticleRepository
 *
 * @package App\Model\Repository
 */
class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    /**
     * Load all Articles
     *
     * @param $id
     *
     * @return array
     */
    public function loadAllArticles()
    {
        return $this->findAll();
    }

    /**
     * Load Article with given ID
     *
     * @param $id
     *
     * @return Article
     */
    public function loadArticleById($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

    public function createArticle($fields)
    {
        $article = new Article();

        $article->setFields($fields);

        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush($article);

        return $article;
    }

    public function updateArticle($id, $fields)
    {
        $article = $this->findOneBy(array('id' => $id));

        $article->setFields($fields);

        $this->getEntityManager()->flush($article);

        return $article;
    }

    public function deleteArticle($id)
    {
        $article = $this->findOneBy(array('id' => $id));

        $this->getEntityManager()->remove($article);
        $this->getEntityManager()->flush($article);
    }
}
