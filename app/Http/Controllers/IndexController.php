<?php

namespace App\Http\Controllers;

use App\Article;
use App\Repositories\ArticlesRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\SlidersRepository;
use Illuminate\Http\Request;
use Config;
use DB;

class IndexController extends SiteController
{

    public function __construct(SlidersRepository $sliderRep, PortfolioRepository $portfolioRep, ArticlesRepository $articleRep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));

        $this->sliderRep = $sliderRep;
        $this->portfolioRep = $portfolioRep;
        $this->articleRep = $articleRep;
        $this->bar = 'right';
        $this->template = env('THEME') . '.index';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $portfolio = $this->getPortfolio();
        $content = view( env('THEME').'.content')->with('portfolios', $portfolio)->render();
        $this->vars = array_add($this->vars, 'content', $content);
       // dd($portfolio);

        $sliderItems = $this->getSliders();

        $sliders = view(env('THEME'). '.slider')->with('sliders', $sliderItems)->render();
        $this->vars = array_add($this->vars, 'sliders', $sliders);

        $this->keywords = 'Home Page';
        $this->metaDesc = 'Home Page';
        $this->title = 'Home Page';


        $articles = $this->getArticles();
        $this->contentRightBar = view(env('THEME'). '.indexBar')->with('articles', $articles)->render();

        return $this->renderOutput();
    }

    public function getSliders(){
        $sliders = $this->sliderRep->get();

        if($sliders->isEmpty()){
            return false;
        }
        $sliders->transform(function ($item, $key){
            $item->img = Config::get('settings.sliderPath').'/'.$item->img;
            return $item;
        });
        return $sliders;
    }
    protected function getArticles(){
        $articles = $this->articleRep->get(['title', 'created_at', 'img', 'alias'],true, Config::get('settings.homeArticlesCount'));
        return $articles;
    }

    protected function getPortfolio(){
        $portfolio = $this->portfolioRep->get('*',true, Config::get('settings.homePortCount'));
        return $portfolio;
    }
}
