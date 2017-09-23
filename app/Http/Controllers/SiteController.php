<?php

namespace App\Http\Controllers;

use App\Repositories\MenusRepository;
use Illuminate\Http\Request;
use Menu;


class SiteController extends Controller
{
    //
    protected $portfolioRep;
    protected $sliderRep;
    protected $articleRep;
    protected $menuRep;
    protected $commentRep;

    protected $keywords;
    protected $metaDesc;
    protected $title;

    protected $template;

    protected $vars = [];

    protected $contentRightBar = false;
    protected $contentLeftBar = false;
    protected $bar = 'no';

    public function __construct(MenusRepository $menuRep)
    {
        $this->menuRep = $menuRep;

    }

    protected function renderOutput(){

        $menu = $this->getMenu();
        $navigation = view(env('THEME') . '.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        if ($this->contentRightBar){
            $rightBar = view(env('THEME'). '.rightBar')->with('contentRightBar', $this->contentRightBar)->render();
            $this->vars = array_add($this->vars, 'rightBar', $rightBar);
        }

        if ($this->contentLeftBar){
            $leftBar = view(env('THEME'). '.leftBar')->with('contentLeftBar', $this->contentLeftBar)->render();
            $this->vars = array_add($this->vars, 'leftBar', $leftBar);
        }


        $this->vars = array_add($this->vars, 'bar', $this->bar);

        $this->vars = array_add($this->vars, 'keywords', $this->keywords);
        $this->vars = array_add($this->vars, 'metaDesc', $this->metaDesc);
        $this->vars = array_add($this->vars, 'title', $this->title);


        $footer = view(env('THEME') .'.footer');
        $this->vars = array_add($this->vars, 'footer', $footer);

        return view($this->template)->with($this->vars);
    }

    public function getMenu(){
        $menu = $this->menuRep->get();

        $menuBuilder = Menu::make('MyNav', function ($m) use ($menu){
            foreach ($menu as $item) {
                if ($item->parent == 0){
                    $m->add($item->title, $item->path)->id($item->id);
                }
                else{
                    if ($m->find($item->parent)){
                            $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }
            }
        });
        return $menuBuilder;
    }
}
