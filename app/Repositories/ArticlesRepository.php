<?php

namespace App\Repositories;

use App\Article;
use Gate;
use Image;
use Config;
class ArticlesRepository extends Repository {

    public function __construct(Article $articles)
    {
        $this->model = $articles;
    }

    public function one($alias, $attr=[]){
        $article = parent::one($alias, $attr);

        if ($article && !empty($attr)){
            $article->load('comments');
            $article->comments->load('user');
        }

        return $article;
    }

    public function addArticle($request){
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
                Config::get('settings.image')['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$object->path);

                $img->fit(Config::get('settings.articlesImg')['max']['width'],
                    Config::get('settings.articlesImg')['max']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$object->max);

                $img->fit(Config::get('settings.articlesImg')['mini']['width'],
                    Config::get('settings.articlesImg')['mini']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$object->mini);

                $data['img'] = json_encode($object);

                $this->model->fill($data);

                if ($request->user()->articles()->save($this->model)){
                    return ['status' => 'Материал добавлен'];

                }
            }
        }

    }

    public function updateArticle($request, $article)
    {
        if (Gate::denies('edit', $this->model)) {
            abort(403);
        }
        $data = $request->except('_token', 'image', '_method');

        if (empty($data)) {
            return ['error' => 'Нет данных'];
        }

        if (empty($data['alias'])) {
            $data['alias'] = $this->transliterate($data['title']);
        }

        $res = $this->one($data['alias'], false);

        if (isset($res->id) && ($res->id !== $article->id)) {
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
                    Config::get('settings.image')['height'])->save(public_path() . '/' . env('THEME') . '/images/articles/' . $object->path);

                $img->fit(Config::get('settings.articlesImg')['max']['width'],
                    Config::get('settings.articlesImg')['max']['height'])->save(public_path() . '/' . env('THEME') . '/images/articles/' . $object->max);

                $img->fit(Config::get('settings.articlesImg')['mini']['width'],
                    Config::get('settings.articlesImg')['mini']['height'])->save(public_path() . '/' . env('THEME') . '/images/articles/' . $object->mini);

                $data['img'] = json_encode($object);

            }
        }

        $this->model->fill($data);
        //dd($article);
        if ($article->update($data)) {
            return ['status' => 'Материал обновлен'];
        }
    }

    public function deleteArticle($article){
        if (Gate::denies('destroy', $article)){
            abort(403);
        }

        $article->comments()->delete();
        if ($article->delete()){
            return ['status' => 'Материал удален'];
        }
    }
}