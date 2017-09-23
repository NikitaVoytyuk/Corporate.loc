<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PortfolioRepository;
use Config;

class PortfolioController extends SiteController
{
    public function __construct(PortfolioRepository $portfolioRep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));

        $this->portfolioRep = $portfolioRep;

        $this->template = env('THEME') . '.portfolios';
    }

    public function index()
    {
        $this->keywords = 'Портфолио';
        $this->title = 'Портфолио';
        $this->metaDesc = 'Портфолио';

        $portfolios = $this->getPortfolios();


        $content = view(env('THEME').'.portfoliosContent')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    public function getPortfolios($take = false){
        $portfolios = $this->portfolioRep->get('*',false,$take,  Config::get('settings.paginatePortfolios'));

        if ($portfolios){
            $portfolios->load('filter');
        }
        return $portfolios;
    }

    public function show($alias){


        $portfolio = $this->portfolioRep->one($alias);

        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->metaDesc = $portfolio->metaDesc;
        $portfolios = $this->getPortfolios(config('settings.otherPortfolios'), false);
        $content = view(env('THEME').'.portfolioContent')->with(['portfolio' =>$portfolio, 'portfolios' => $portfolios])->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();
    }
}
