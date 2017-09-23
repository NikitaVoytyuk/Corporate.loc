<?php

namespace App\Http\Controllers\Admin;

use App\Filter;
use App\Portfolio;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;
use App\Http\Requests\PortfolioRequest;
use App\Http\Controllers\Controller;
use Gate;
use Config;

class PortfoliosController extends AdminController
{
    public function __construct(PortfolioRepository $portfolioRep)
    {
        parent::__construct();

        if (Gate::denies('VIEW_ADMIN_ARTICLES')){
            abort(403);
        }
        $this->portfolioRep = $portfolioRep;

        $this->template = env('THEME').'.admin.portfolios';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = 'Менеджер товаров';

        $portfolios = $this->getPortfolios();
        $this->content = view(env('THEME'). '.admin.portfoliosContent')->with('portfolios', $portfolios)->render();
        return $this->renderOutput();
    }

    public function getPortfolios()
    {
        return $this->portfolioRep->get('*',false, false, Config::get('settings.paginateAdmin'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('save', new Portfolio)){
            abort(403);
        }

        $this->title = 'Добавить новый товар';

        $filters = Filter::select(['id', 'title', 'alias'])->get();

        $lists = [];
        foreach ($filters as $filter) {
            if ($filter->alias == $filter->alias){
                $lists[$filter->alias] = $filter->alias;
            }
            else{
                $lists[$filters->where('alias', $filter->alias)->first()->alias][$filter->alias] = $filter->alias;
            }
        }

        $this->content = view(env('THEME'). '.admin.portfoliosCreateContent')->with('filters', $lists)->render();

        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PortfolioRequest $request)
    {
        $res = $this->portfolioRep->addPortfolio($request);

        //dd($request);
        if (is_array($res) && !empty($res['error'])){
            return back()->with($res);
        }
        return redirect('/admin')->with($res);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($alias)
    {
        $portfolio = Portfolio::where('alias', $alias)->first();

        if (Gate::denies('edit', new Portfolio)){
            abort(403);
        }

        $portfolio->img = json_decode($portfolio->img);

        $filters = Filter::select(['alias'])->get();

        $lists = [];
        foreach ($filters as $filter) {
            if ($filter->alias == $filter->alias){
                $lists[$filter->alias] = $filter->alias;
            }
            else{
                $lists[$filters->where('alias', $filter->alias)->first()->alias][$filter->alias] = $filter->alias;
            }
        }
        $this->title = 'Редактирование материала -' . $portfolio->title;

        $this->content = view(env('THEME'). '.admin.portfoliosCreateContent')->with(['filters' =>  $lists, 'portfolio' => $portfolio])->render();

        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PortfolioRequest $request, $alias)
    {
        $portfolio = Portfolio::where('alias', $alias)->first();
        $res = $this->portfolioRep->updatePortfolio($request, $portfolio);
        if (is_array($res) && !empty($res['error'])){
            return back()->with($res);
        }
        return redirect('/admin')->with($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($alias)
    {
        $portfolio = Portfolio::where('alias', $alias)->first();
        $res = $this->portfolioRep->deletePortfolio($portfolio);
        if (is_array($res) && !empty($res['error'])){
            return back()->with($res);
        }
        return redirect('/admin')->with($res);
    }
}
