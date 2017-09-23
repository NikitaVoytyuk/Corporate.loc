<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Menu;

class AdminController extends Controller
{
    protected $portfolioRep;
    protected $articlesRep;
    protected $user;
    protected $template;
    protected $content = false;
    protected $title;
    protected $vars;

    public function __construct()
    {
        $this->user = Auth::user();

        if (!$this->user){
            abort(403);
        }
    }

    public function renderOutput(){
        $this->vars = array_add($this->vars, 'title', $this->title);

        $menu = $this->getMenu();

        $navigation = view(env('THEME'). '.admin.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        if ($this->content){
            $this->vars = array_add($this->vars, 'content', $this->content);
        }

        $footer = view(env('THEME'). '.admin.footer')->render();
        $this->vars = array_add($this->vars, 'footer', $footer);

        return view($this->template)->with($this->vars);
    }

    public function getMenu(){
        return Menu::make('adminMenu', function ($menu){
            $menu->add('Статьи', ['route' => 'admin.articles.index']);
            $menu->add('Портфолио', ['route' => 'admin.portfolios.index']);
            $menu->add('Меню', ['route' => 'admin.menus.index']);
            $menu->add('Привилегии', ['route' => 'admin.permissions.index']);
        });
    }
}
