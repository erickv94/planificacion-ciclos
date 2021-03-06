@extends('layouts.dashboard')

@section('titulo')
Planificación de ciclos | Gestión Locales
@endsection

@section('css.current')
<style>
.accion-form {
display:inline;
}
</style>
@endsection


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('materias.index')}}">Listado materias</a></li>
@endsection

@section('info')
<h1><i class="fa fa-book"></i> Gestión de materias</h1>
<p> Sección para la gestión de materias de la Facultad de Química y Farmacia UES </p>
@endsection

@section('contenido')
<div class="tile">
        <div class="tile-title-w-btn">

              <div class="btn-group">
                  <a class="btn btn-primary" href="{{route('materias.create')}}"><i class="fa fa-lg fa-plus"></i> Crear</a>
                 
              </div>
              </div>    
              <form class="row" method="GET" action="{{route('materias.index')}}">
                          <div class="form-group col-sm-3">
                <input class="form-control" type="text" placeholder="Buscar por nombre" name='nombre' value="{{request('nombre')}}">
              </div>
              <div class="form-group col-sm-3">
                <input class="form-control" type="text" placeholder="Buscar por codigo" name="codigo" value="{{request('codigo')}}">
              </div>
              <div class="form-group col-sm-3">
                <select class="form-control" id="select" name='ciclo'>
                  <option value >Buscar por ciclo</option>
                  <option value="Ambos" {{request('ciclo')=='Ambos'?'selected':''}}>Ambos</option>
                  <option value="Impar" {{request('ciclo')=='Impar'?'selected':''}}>Impar</option>
                  <option value="Par" {{request('ciclo')=='Par'?'selected':''}}>Par</option>
                </select>
              </div>
              <div class="form-group col-sm-3">
                <select class="form-control" id="select" name='nivel'>
                  <option value >Buscar por año</option>
                  <option value="1" {{request('nivel')=='1'?'selected':''}}>Primero</option>
                  <option value="2" {{request('nivel')=='2'?'selected':''}}>Segundo</option>
                  <option value="3" {{request('nivel')=='3'?'selected':''}}>Tercero</option>
                  <option value="4" {{request('nivel')=='4'?'selected':''}}>Cuarto</option>
                  <option value="5" {{request('nivel')=='5'?'selected':''}}>Quinto</option>
                </select>
              </div>
              <div class="form-group col-sm-3 ">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                      <a href="{{route('materias.index')}}" class="btn btn-primary" ><i class="fa fa-list"></i> Todos</a>
                      
                  </div>
            </form>
   <div class="table-responsive">
    <table class="table" style="text-align: center"> 
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Codigo</th>
          <th>Ciclo</th>
          <th>Nivel</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($materias as $materia)
          <tr>
          <td>{{$materia->nombre}}</td>
          <td>{!!$materia->codigo??'<i class="fa fa-info-circle text-primary" aria-hidden="true"></i> No Registrado
            '!!}
          </td>
          <td>{{$materia->ciclo}}</td>
          <td>
          @switch($materia->nivel)
              @case(1)
              Primer año
                  @break
              @case(2)
              Segundo año
                  @break
              @case(3)
              Tercer año
                  @break
              @case(4)
              Cuarto año
                @break
              @case(5)
              Quinto año
              @break              
          @endswitch</td>
          <td>
          @can('materias.show')
          <a href="{{route('materias.show',$materia->id)}}" class="btn btn-outline-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Mostrar</a>
          @endcan

          @can('materias.edit')
          <a href="{{route('materias.edit',$materia->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
          @endcan

          @can('materias.inhabilitar')
          <form class='accion-form' action="{{route('materias.inhabilitar',$materia->id)}}" method="POST">
          @csrf
            @method('patch')
            @if ($materia->habilitado)
              <button type="submit" class="btn btn-outline-warning btn-sm"><i class="fa fa-lock" aria-hidden="true"></i> Inhabilitar</button>  
            @else
              <button type="submit" class="btn btn-outline-success btn-sm"><i class="fa fa-unlock" aria-hidden="true"></i> Habilitar</button>               
            @endif
          </form>
          @endcan

          @can('materias.delete')
            <form id="delete-{{$materia->id}}" class='accion-form' action="{{ route('materias.destroy', $materia->id)}}" method="post">
              @csrf
              @method('DELETE')
              <button class="btn btn-outline-danger btn-sm" type="button" onclick="confirmar('{{$materia->nombre}}',{{$materia->id}})"><i class="fa fa-trash" aria-hidden="true"></i>  Eliminar</button>
            </form>
          @endcan
          </td>
        </tr>
      @endforeach

      </tbody>
    </table>
    {!! $materias->links() !!}
  </div>
</div>
@endsection

@section('js.plugins')
<script src="/js/plugins/sweetalert.min.js">
</script>
@endsection

@section('js.current')
<script type="text/javascript">

function confirmar(nombre,id){
    swal({
      title: "Estas seguro de eliminar "+nombre+"?",
      text: "Una vez borrado no podras recuperar el registro",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Si",
      cancelButtonText: "No, cancelar",
      closeOnConfirm: false,
      closeOnCancel: false
    }, function(isConfirm) {
      if (isConfirm) {
        swal("Borrado!", "Materia ha sido borrado con exito.", "success");
        document.getElementById('delete-'+id).submit();
      } else {
        swal("Cancelado!", "No se ha elimando nada:)", "error");
      }
    });
  }
</script>
@endsection