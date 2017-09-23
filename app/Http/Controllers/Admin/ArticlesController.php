<?php

namespace App\Http\Controllers\Admin;


use App\Article;
use App\Category;
use App\Repositories\ArticlesRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Controllers\Controller;
use Gate;
use Config;




class ArticlesController extends AdminController
{


    public function __construct(ArticlesRepository $articlesRep)
    {
        parent::__construct();

        if (Gate::denies('VIEW_ADMIN_ARTICLES')){
            abort(403);
        }
        $this->articlesRep = $articlesRep;

        $this->template = env('THEME').'.admin.articles';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = 'Менеджер статей';

        $articles = $this->getArticles();
        $this->content = view(env('THEME'). '.admin.articlesContent')->with('articles', $articles)->render();

        return $this->renderOutput();

    }

    public function getArticles()
    {
        return $this->articlesRep->get('*', false,false,  Config::get('settings.paginateAdmin'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('save', new Article)){
            abort(403);
        }

        $this->title = 'Добавить новый материал';

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();

        $lists = [];

        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $lists[$category->title] = [];
            }
            else{
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->content = view(env('THEME'). '.admin.articlesCreateContent')->with('categories', $lists)->render();

        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $res = $this->articlesRep->addArticle($request);

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

        $article = Article::where('alias', $alias)->first();

        if (Gate::denies('edit', new Article)){
            abort(403);
        }

        $article->img = json_decode($article->img);

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();

        $lists = [];

        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $lists[$category->title] = [];
            }
            else{
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->title = 'Редактирование материала -' . $article->title;

        $this->content = view(env('THEME'). '.admin.articlesCreateContent')->with(['categories' =>  $lists, 'article' => $article])->render();

        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $alias)
    {
        $article = Article::where('alias', $alias)->first();
        $res = $this->articlesRep->updateArticle($request, $article);
        dd($res);
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
        $article = Article::where('alias', $alias)->first();
        $res = $this->articlesRep->deleteArticle($article);

        if (is_array($res) && !empty($res['error'])){
            return back()->with($res);
        }
        return redirect('/admin')->with($res);
    }
}
