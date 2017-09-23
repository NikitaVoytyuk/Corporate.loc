@if($portfolios)
    <div id="content-page" class="content group">
        <div class="hentry group">
            <h2>Добавленные статьи</h2>
            <div class="short-table white">
                <table style="width: 100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="align-left">ID</th>
                        <th>Заголовок</th>
                        <th>Текст</th>
                        <th>Изображение</th>
                        <th>Категория</th>
                        <th>Псевдоним</th>
                        <th>Дествие</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($portfolios as $portfolio)
                        <tr>
                            <td class="align-left">{{$portfolio->id}}</td>
                            <td class="align-left">{!! Html::link(route('admin.portfolios.edit',['portfolios'=>$portfolio->alias]),$portfolio->title) !!}</td>
                            <td class="align-left">{{str_limit($portfolio->text,200)}}</td>
                            <td>
                                @if(isset($portfolio->img->mini))
                                    {!! Html::image(asset(env('THEME')).'/images/projects/'.$portfolio->img->mini) !!}
                                @endif
                            </td>
                            <td>{{$portfolio->filter_alias}}</td>
                            <td>{{$portfolio->alias}}</td>
                            <td>
                                {!! Form::open(['url' => route('admin.portfolios.destroy',['portfolios'=>$portfolio->alias]),'class'=>'form-horizontal','method'=>'POST']) !!}
                                {{ method_field('DELETE') }}
                                {!! Form::button('Удалить', ['class' => 'btn btn-french-5','type'=>'submit']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="general-pagination group">
                @if($portfolios->lastPage() > 1)
                    @if($portfolios->currentPage() !==1)
                        <a href="{{$portfolios->url(($portfolios->currentPage() - 1))}}">{{Lang::get('pagination.previous')}}</a>
                    @endif

                    @for($i = 1; $i <= $portfolios->lastPage(); $i++)
                        @if($portfolios->currentPage() ==$i)
                            <a class="selected disabled">{{$i}}</a>
                        @else
                            <a href="{{$portfolios->url($i)}}">{{$i}}</a>
                        @endif
                    @endfor
                    @if($portfolios->currentPage() !== $portfolios->lastPage())
                        <a href="{{$portfolios->url(($portfolios->currentPage() + 1))}}">{{Lang::get('pagination.next')}}</a>
                    @endif

                @endif
            </div>
            {!! Html::link(route('admin.portfolios.create'),'Добавить  товар',['class' => 'btn btn-the-salmon-dance-3']) !!}
        </div>
        <!-- START COMMENTS -->
        <div id="comments">
        </div>
        <!-- END COMMENTS -->
    </div>
@endif