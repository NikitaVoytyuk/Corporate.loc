<?php

namespace App\Http\Controllers\Admin;


use App\Category;
use App\Filter;
use App\Menu as Menus;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;
use App\Http\Requests\MenusRequest;
use Gate;
use Menu;


class MenusController extends AdminController
{

    protected $menusRep;

    public function __construct(MenusRepository $menusRep, ArticlesRepository $articlesRep, PortfolioRepository $portfolioRep)
    {
        parent::__construct();
        if (Gate::denies('VIEW_ADMIN_MENU')){
            abort(403);
        }

        $this->menusRep = $menusRep;
        $this->articlesRep = $articlesRep;
        $this->portfolioRep = $portfolioRep;

        $this->template = env('THEME').'.admin.menus';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = $this->getMenus();
        $this->content = view(env('THEME'). '.admin.menusContent')->with('menus', $menu)->render();

        return $this->renderOutput();
    }


    public function getMenus(){
        $menu = $this->menusRep->get();
        if ($menu->isEmpty()){
            return false;
        }

        return Menu::make('forMenuPart',function ($m) use ($menu){
            foreach ($menu as $item) {
                if ($item->parent == 0){
                    $m->add($item->title,$item->path)->id($item->id);
                }
                else{
                    if ($m->find($item->parent)){
                        $m->find($item->parent)->add($item->title,$item->path)->id($item->id);
                    }
                }
            }
        });
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = 'Новый пункт меню';
        $tmp = $this->getMenus()->roots();
        $menus = $tmp->reduce(function ($returnMenus, $menu) {
            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        }, [0 => 'Родительский пункт меню']);

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();

        $list = [];
        $list = array_add($list, '0', 'Не используется');
        $list = array_add($list, 'parent', 'Раздел блога');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        $articles = $this->articlesRep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce(function ($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);

        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilters, $filter) {
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        }, ['parent' => 'Раздел портфолио']);
        $portfolios = $this->portfolioRep->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio) {
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        }, []);

        $this->content = view(env('THEME').'.admin.menusCreateContent')->with(['menus'=>$menus,'categories'=>$list,'articles'=>$articles,'filters' => $filters,'portfolios' => $portfolios])->render();

        return $this->renderOutput();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenusRequest $request)
    {
        $res = $this->menusRep->addMenu($request);

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
    public function edit($id)
    {


        $menu = Menus::where('id', $id)->first();
        $this->title = 'Редактирование сслыки меню -'. $menu->title;
        $type = false;
        $option = false;

        $route = app('router')->getRoutes()->match(app('request')->create($menu->path));
        $aliasRoute = $route->getName();
        $params = $route->parameters();

        if ($aliasRoute == 'articles.index' || $aliasRoute == 'articlesCat'){
            $type = 'blogLink';
            $option = isset($params['cat_alias']) ? $params['cat_alias'] : '';
        }
        elseif ($aliasRoute == 'articles.show'){
            $type = 'blogLink';
            $option = isset($params['alias']) ? $params['alias'] : '';
        }
        elseif ($aliasRoute == 'portfolios.index'){
            $type = 'portfolioLink';
            $option = 'parent';
        }
        elseif ($aliasRoute == 'portfolios.show'){
            $type = 'portfolioLink';
            $option = isset($params['alias']) ? $params['alias'] : '';
        }
        else{
            $type = 'customLink';
        }


        $tmp = $this->getMenus()->roots();

        $menus = $tmp->reduce(function ($returnMenus, $menu) {
            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        }, [0 => 'Родительский пункт меню']);

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();

        $list = [];
        $list = array_add($list, '0', 'Не используется');
        $list = array_add($list, 'parent', 'Раздел блога');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        $articles = $this->articlesRep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce(function ($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);

        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilters, $filter) {
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        }, ['parent' => 'Раздел портфолио']);
        $portfolios = $this->portfolioRep->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio) {
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        }, []);

        $this->content = view(env('THEME').'.admin.menusCreateContent')->with(['menu' => $menu,'option' => $option,'type' => $type,'menus'=>$menus,'categories'=>$list,'articles'=>$articles,'filters' => $filters,'portfolios' => $portfolios])->render();

        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = Menus::where('id', $id)->first();
        $result = $this->menusRep->updateMenu($request,$menu);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menus::where('id', $id)->first();
        $result = $this->menusRep->deleteMenu($menu);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}
