<div class="col-md-5 d-flex flex-row justify-content-end">
    {!! Form::open(['url' => url()->current(), 'method' => 'get', 'class' => 'per-page-form form-row justify-content-between align-items-end']) !!}
        @if (count($notes) > 0)
            <div class="form-group w-30 ml-3 mb-0">
                <label for="grid-notes">
                    @if ($filter['grid_notes'])
                        <i class="fa fa-list" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-th-large" aria-hidden="true"></i>
                    @endif
                </label>
                {!! Form::checkbox('grid_notes', '1', ($filter['grid_notes'] ? 'checked' : ''), ['class' => 'grid-notes', 'id' => 'grid-notes', 'hidden']) !!}
            </div>
            <div class="form-group w-30 ml-3">
                {!! Form::label('per-page-select', __('common.per_page')) !!}
                {!! Form::select('per_page', ['5' => '5', '10' => '10', '15' => '15', '20' => '20'], $filter['per_page'], ['class' => 'form-control per-page-select']) !!}
            </div>
        @endif
        @if (isset($filter['shared']))
            <div class="form-group w-30 ml-3">
                {!! Form::label('range-share', __('common.shared')) !!}
                {!! Form::select('shared', ['0' => __('common.all'), '1' => __('common.shared')], $filter['shared'], ['class' => 'form-control range-shared']) !!}
            </div>
        @endif
    {!! Form::close() !!}
</div>
