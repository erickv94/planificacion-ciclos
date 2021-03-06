  @extends('layouts.dashboard')

  @section('titulo')
  Planificación de ciclos | Inicio
  @endsection

  @section('css.current')
  <style>
  .accion-form {
  display:inline;
  }
  </style>
  @endsection


  @section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{route('docentes.index')}}">Listado docentes</a></li>
  @endsection

  @section('info')
  <h1><i class="fa fa-users"></i> Gestión de docentes</h1>
  <p> Sección para la gestión de materias de la Facultad de Química y Farmacia UES </p>
  @endsection

  @section('contenido')
  <div class="tile">
          <div class="tile-title-w-btn">

                <div class="btn-group">
                    <a class="btn btn-primary" href="{{route('docentes.create')}}"><i class="fa fa-lg fa-plus"></i> Crear</a>
                </div>
                </div>    
                <form class="row" method="GET" action="{{route('docentes.index')}}">
                            <div class="form-group col-sm-3">
                  <input class="form-control" type="text" placeholder="Buscar por nombre" name='name' value='{{request('name')}}'>
                </div>
                <div class="form-group col-sm-3">
                <input class="form-control" type="text" placeholder="Buscar por email" name="email" value="{{request('email')}}">
                </div>
                <div class="form-group col-sm-3">
                  <select class="form-control col-md-10" id="select"  name='materia'>
                    <option></option>
                    @foreach ($materias as $materia)
                  <option value="{{$materia->codigo}}" {{ request('materia')==$materia->codigo?'selected':''}}>
                      {{$materia->codigo.' - '.$materia->nombre}}
                    </option>
                    @endforeach                  
                </select>
                
                </div>
                <div class="form-group col-sm-3">
                        <label >
                          <input class="form-control" type="checkbox" name='coordinador'>Coordinadores
                        </label>
                </div>
                <div class="form-group col-sm-3 ">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                        <a href="{{route('docentes.index')}}" class="btn btn-primary" ><i class="fa fa-list"></i> Todos</a>
                        
                    </div>
              </form>
     <div class="table-responsive">
      <table class="table" style="text-align: center"> 
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Coordinador</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>

        @foreach ($docentes as $docente)
            <tr>
            <td>{{$docente->user->name}}</td>
            <td>{{$docente->user->email}}</td>
            <td>{!!($docente->esCoordinador) ?'<i class="fa fa-check text-success" aria-hidden="true"></i>':'<i class="fa fa-close text-danger" aria-hidden="true"></i>' !!}</td>
            <td>
            @can('docentes.show')  
            <a href="{{route('docentes.show',$docente->id)}}" class="btn btn-outline-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Mostrar</a>
            @endcan

            @can('docentes.edit')
            <a href="{{route('docentes.edit',$docente->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
            @endcan

            @can('docentes.inhabilitar')
            <form class='accion-form' action="{{route('docentes.inhabilitar',$docente->id)}}" method="POST">
            @csrf
              @method('patch')
              @if ($docente->user->habilitado)
                <button type="submit" class="btn btn-outline-warning btn-sm"><i class="fa fa-lock" aria-hidden="true"></i> Inhabilitar</button>  
              @else
                <button type="submit" class="btn btn-outline-success btn-sm"><i class="fa fa-unlock" aria-hidden="true"></i> Habilitar</button>  
              @endif
              </form>
            @endcan

            @can('docentes.delete')
              <form id="delete-{{$docente->id}}" class='accion-form' action="{{ route('docentes.destroy', $docente->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm" type="button" onclick="confirmar('{{$docente->user->name}}',{{$docente->id}})"><i class="fa fa-trash" aria-hidden="true"></i>  Eliminar</button>
              </form>
            @endcan
            </td>
          </tr>
        @endforeach

        

        </tbody>
      </table>
      {!! $docentes->links() !!}
    </div>
  </div>
@endsection

@section('js.plugins')
<script src="/js/plugins/sweetalert.min.js">
</script>
<script src="/js/plugins/select2.min.js">
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
        swal("Borrado!", "Docente ha sido borrado con exito.", "success");
        document.getElementById('delete-'+id).submit();
      } else {
        swal("Cancelado!", "No se ha elimando nada:)", "error");
      }
    });
  }
$('#select').select2({
            placeholder: "Seleccionar materia",
        
        });


</script>
@endsection