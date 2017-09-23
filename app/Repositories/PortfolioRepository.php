<?php
namespace App\Repositories;

use App\Portfolio;
use Gate;
use Image;
use Config;

class PortfolioRepository extends Repository {

    public function __construct(Portfolio $portfolio)
    {
        $this->model = $portfolio;
    }


    public function one($alias, $attr = []){
        $portfolio = parent::one($alias, $attr);

        if ($portfolio && $portfolio->img){
            $portfolio->img = json_decode($portfolio->img);
        }
        return $portfolio;
    }

    public function addPortfolio($request){
        if (Gate::denies('save', $this->model)){
            abort(403);
        }
        $data = $request->except('_token', 'image');

        if (empty($data)){
            return ['error' => 'Нет данных'];
        }

        if (empty($data['alias'])){
            $data['alias'] = $this->transliterate($data['title']);
        }

        if ($this->one($data['alias'], false)){
            $request->merge(array('alias' => $data['alias']));
            $request->flash();

            return ['error' => 'Данный alias уже используется'];
        }

        if ($request->hasFile('image')){
            $image = $request->file('image');
            if ($image->isValid()){
                $str = str_random(8);
                $object = new \stdClass;
                $object->mini = $str. '_mini.jpg';
                $object->max = $str. '_max.jpg';
                $object->path = $str.'.jpg';

                $img = Image::make($image);

                $img->fit(Config::get('settings.image')['width'],
                    Config::get('settings.image')['height'])->save(public_path().'/'.env('THEME').'/images/projects/'.$object->path);

                $img->fit(Config::get('settings.portfoliosImg')['max']['width'],
                    Config::get('settings.portfoliosImg')['max']['height'])->save(public_path().'/'.env('THEME').'/images/projects/'.$object->max);

                $img->fit(Config::get('settings.portfoliosImg')['mini']['width'],
                    Config::get('settings.portfoliosImg')['mini']['height'])->save(public_path().'/'.env('THEME').'/images/projects/'.$object->mini);

                $data['img'] = json_encode($object);

                $this->model->fill($data);
                if ($request->user()->portfolios()->save($this->model)){
                    return ['status' => 'Материал добавлен'];
                }
            }
        }

    }

    public function updatePortfolio($request, $portfolios)
    {
        if (Gate::denies('edit', $this->model)) {
            abort(403);
        }
        $data = $request->except('_token', 'image');

        if (empty($data)) {
            return ['error' => 'Нет данных'];
        }

        if (empty($data['alias'])) {
            $data['alias'] = $this->transliterate($data['title']);
        }

        $res = $this->one($data['alias'], false);
        //dd($data);
        if (isset($res->id) && ($res->id !== $portfolios->id)) {
            $request->merge(array('alias' => $data['alias']));
            $request->flash();
            return ['error' => 'Данный alias уже используется'];
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                $str = str_random(8);
                $object = new \stdClass;
                $object->mini = $str . '_mini.jpg';
                $object->max = $str . '_max.jpg';
                $object->path = $str . '.jpg';

                $img = Image::make($image);

                $img->fit(Config::get('settings.image')['width'],
                    Config::get('settings.image')['height'])->save(public_path() . '/' . env('THEME') . '/images/projects/' . $object->path);

                $img->fit(Config::get('settings.portfoliosImg')['max']['width'],
                    Config::get('settings.portfoliosImg')['max']['height'])->save(public_path() . '/' . env('THEME') . '/images/projects/' . $object->max);

                $img->fit(Config::get('settings.portfoliosImg')['mini']['width'],
                    Config::get('settings.portfoliosImg')['mini']['height'])->save(public_path() . '/' . env('THEME') . '/images/projects/' . $object->mini);

                $data['img'] = json_encode($object);


                }
            }
        $this->model->fill($data);
        if ($portfolios->update($data)) {
            return ['status' => 'Товар обновлен'];
        }
    }

    public function deletePortfolio($article){
        if (Gate::denies('destroy', $article)){
            abort(403);
        }

        if ($article->delete()){
            return ['status' => 'Товар удален'];
        }
    }

}