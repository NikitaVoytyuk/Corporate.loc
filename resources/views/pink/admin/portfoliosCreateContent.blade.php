<div id="content-page" class="content group">
    <div class="hentry group">

        {{ Form::open(['url' => (isset($portfolio->id)) ? route('admin.portfolios.update',['portfolios'=>$portfolio->alias]) : route('admin.portfolios.store'),'class'=>'contact-form','method'=>'POST','enctype'=>'multipart/form-data']) }}

        <ul>
            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">Название:</span>
                    <br />
                    <span class="sublabel">Заголовок материала</span><br />
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{ Form::text('title',htmlspecialchars(isset($portfolio->title) ? $portfolio->title  : old('title')), ['placeholder'=>'Введите название страницы']) }}
                </div>
            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">Ключевые слова:</span>
                    <br />
                    <span class="sublabel">Заголовок материала</span><br />
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{ Form::text('keywords', htmlspecialchars(isset($portfolio->keywords) ? $portfolio->keywords  : old('keywords')), ['placeholder'=>'Введите название страницы']) }}
                </div>
            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">Мета описание:</span>
                    <br />
                    <span class="sublabel">Заголовок материала</span><br />
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{ Form::text('meta_desc', htmlspecialchars(isset($portfolio->meta_desc) ? $portfolio->meta_desc  : old('meta_desc')), ['placeholder'=>'Введите название страницы']) }}
                </div>
            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">Псевдоним:</span>
                    <br />
                    <span class="sublabel">введите псевдоним</span><br />
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{ Form::text('alias', isset($portfolio->alias) ? ""  : old('alias'), ['placeholder'=>'Введите псевдоним страницы']) }}
                </div>
            </li>

            {{--<li class="textarea-field">
                <label for="message-contact-us">
                    <span class="label">Краткое описание:</span>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                    {!! Form::textarea('desc', isset($portfolio->desc) ? $portfolio->desc  : old('desc'), ['id'=>'editor','class' => 'form-control','placeholder'=>'Введите текст страницы']) !!}
                </div>
                <div class="msg-error"></div>
            </li>--}}

            <li class="textarea-field">
                <label for="message-contact-us">
                    <span class="label">Описание:</span>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                    {{ Form::textarea('text', isset($portfolio->text) ? $portfolio->text  : old('text'), ['id'=>'editor2','class' => 'form-control','placeholder'=>'Введите текст страницы']) }}
                </div>
                <div class="msg-error"></div>
            </li>

            @if(isset($portfolio->img->path))
                <li class="textarea-field">

                    <label>
                        <span class="label">Изображения материала:</span>
                    </label>

                    {{ Html::image(asset(env('THEME')).'/images/projects/'.$portfolio->img->path,'',['style'=>'width:400px']) }}
                    {!! Form::hidden('old_image',$portfolio->img->path) !!}

                </li>
            @endif


            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">Изображение:</span>
                    <br />
                    <span class="sublabel">Изображение материала</span><br />
                </label>
                <div class="input-prepend">
                    {!! Form::file('image', ['class' => 'filestyle','data-buttonText'=>'Выберите изображение','data-buttonName'=>"btn-primary",'data-placeholder'=>"Файла нет"]) !!}
                </div>

            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">Категория:</span>
                    <br />
                    <span class="sublabel">Категория материала</span><br />
                </label>
                <div class="input-prepend">
                    {!! Form::select('filter_alias', $filters, isset($portfolio->filter_alias) ? $portfolio->filter_alias  : '') !!}
                </div>
            </li>
            @if(isset($portfolio->id))
                <input type="hidden" name="_method" value="PUT">

            @endif

            <li class="submit-button">
                {!! Form::button('Сохранить', ['class' => 'btn btn-the-salmon-dance-3','type'=>'submit']) !!}
            </li>

        </ul>

        {!! Form::close() !!}

        <script>
            CKEDITOR.replace( 'editor2' );
        </script>
    </div>
</div>