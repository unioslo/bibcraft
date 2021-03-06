@extends('master')
@section('header')

	<h2>Samlinger : {{$collection->name}}  ({{count($collection->documents)}} dokumenter)</h2>

@endsection
@section('container')

<form class="form-inline" method="post" action="{{ URL::action('CollectionsController@postRemoveFromCollection', $collection->id) }}">

  <a href="{{ URL::action('DocumentsController@getCreate') }}?collection={{$collection->id}}" class="btn">
    <i class="icon-plus-sign"></i>
    Opprett nytt dokument
  </a>

  <div id="actionsForSelected" style="float:right;">
    <button type="submit" class="btn">Fjern merkede fra samlingen</button>
  </div>

  <ul style="list-style-type:none; clear:both;margin-top:20px;">
  @foreach ($collection->documents as $obj)

    <!-- ************************************************
    We should make this into a template -->
    <li style="margin:10px;padding: 10px; background:#ececec;">
      <input type="checkbox" style="float:left; display: block; height: 120px; width:30px;" name="check_{{ $obj->id }}" />
      <div style="float:left; width:120px; height: 120px; margin:3px 8px;">
        <img src="{{ $obj->cachedCover }}" style="max-height: 120px; max-width: 120px; display: block; margin: auto; box-shadow: 2px 2px 5px #888888;">
      </div>
      <strong>{{ $obj->title }} {{ $obj->subtitle }}</strong> ({{$obj->publisher }} {{$obj->year }})<br />
      Av: {{ $obj->authors }}<br />
      Lagt til: {{ $obj->created_at }}<br />
      Samlinger:
      @if (count($obj->collections) == 0)
        <em>Ingen</em>
      @else
        @foreach ($obj->collections as $collection)
          <a href="{{URL::action('CollectionsController@getShow', $collection->id)}}">{{$collection->name}}</a>
        @endforeach
      @endif
      <br />

      <a href="{{ URL::action('DocumentsController@getEdit', $obj->id) }}"><i class="icon-pencil"></i> rediger</a>
      <a href="{{ URL::action('DocumentsController@getDelete', $obj->id) }}"><i class="icon-trash"></i> slett</a>
      <div style="clear:both;"></div>
    </li>
    <!-- ************************************************
    End of the template -->

  @endforeach
  </ul>

</form>

@if (count($collection->documents) == 0)
	<em>Samlingen er tom</em>
@endif

@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function() {

      $('#actionsForSelected').hide();
      $('input[type="checkbox"]').on('change', function() {
        if ($('input[type="checkbox"]:checked').length != 0) {
          $('#actionsForSelected').show();
        } else {
          $('#actionsForSelected').hide();
        }
      })
    });
  </script>
@endsection
