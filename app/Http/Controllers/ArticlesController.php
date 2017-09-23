<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Portfolio;
use App\Repositories\CommentsRepository;
use Illuminate\Http\Request;
use App\Repositories\ArticlesRepository;
use App\Repositories\PortfolioRepository;
use App\Category;
use Config;

class ArticlesController extends SiteController
{
    public function __construct(PortfolioRepository $portfolioRep, ArticlesRepository $articleRep, CommentsRepository $commentRep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));

        $this->portfolioRep = $portfolioRep;
        $this->articleRep = $articleRep;
        $this->commentRep=$commentRep;
        $this->bar = 'right';
        $this->template = env('THEME') . '.articles';
    }

    public function index($cat_alias = false)
    {
        $articles = $this->getArticles($cat_alias);

        $content = view(env('THEME').'.articlesContent')->with('articles', $articles)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $comments = $this->getComments(config('settings.recentComments'));
        $portfolios = $this->getPortfolios(config('settings.recentPortfolios'));;
        $this->contentRightBar = view(env('THEME'). '.articlesBar')->with(['comments' => $comments, 'portfolios'=>$portfolios ]);
        return $this->renderOutput();
    }

    public function getArticles($alias = false){
        $where = false;
        if ($alias){
            $id = Category::select('id')->where('alias', $alias)->first()->id;
            $where = ['category_id', $id];
        }

        $articles = $this->articleRep->get(['id','title', 'alias', 'created_at', 'img', 'desc', 'user_id', 'category_id','keywords', 'metaDesc'],false,
            false, Config::get('settings.paginate'), $where);

        if ($articles){
            $articles->load('user', 'category', 'comments');
        }
        return $articles;
    }

    public function getComments($take){

        $comments = $this->commentRep->get(['text', 'name', 'email', 'site', 'article_id', 'user_id'],true, $take);
        if ($comments){
            $comments->load('article', 'user');
        }
        return $comments;
    }

    public function getPortfolios($take){
        $portfolios = $this->portfolioRep->get(['title', 'text', 'alias', 'customer', 'img', 'filter_alias'], true, $take);
        return $portfolios;
    }

    public function show($alias = false){
    $article= $this->articleRep->one($alias, ['comments' => true]);
    if ($article){
        $article->img = json_decode($article->img);
    }
    if (isset($article->id)){
        $this->title = $article->title;
        $this->keywords = $article->keywords;
        $this->metaDesc = $article->metaDesc;
    }

    $content = view(env('THEME').'.articleContent')->with('article', $article)->render();
    $this->vars = array_add($this->vars, 'content', $content);

    $comments = $this->getComments(config('settings.recentComments'));
    $portfolios = $this->getPortfolios(config('settings.recentPortfolios'));;
    $this->contentRightBar = view(env('THEME'). '.articlesBar')->with(['comments' => $comments, 'portfolios'=>$portfolios ]);


    return $this->renderOutput();
}
}
