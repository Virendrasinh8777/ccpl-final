@extends('adm.layout.admin-index')
@section('title','{{$pageTitle}} Links')

@section('toast')
  @include('adm.widget.toast')
@endsection

@section('custom-js')
<script>
$( document ).ready(function() {
  $(".del-modal").click(function(){
    var delete_form = $(this).attr('data-form');
    var delete_id = $(this).attr('data-id');
    var data_title = $(this).attr('data-title');
    var data_slug = $(this).attr('data-pageslug');
    
    $('.delete-form').attr('action', delete_form);

    $('.delete-title').html(data_title);
    $('.delete-id').val(delete_id);
    $('.delete-slug').val(data_slug);
    
  });  
});

$( ".row_position" ).sortable({
      stop: function() {
			var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            console.log(selectedData);
            updateOrder(selectedData);
        }
  });

  function updateOrder(data) {
  $.ajax({
      url:"{{url('api')}}/admin/item/update-item-priority",
      type:'post',
      data:{position:data, table: 'url_list'},
      success:function(result){
        console.log(result);
      }
  })
}

function updateStatus($id) {
  $.ajax({
      url:"{{route('status.update')}}",
      type:'post',
      data:{id:$id, table: 'url_list'},
      success:function(result){
        location.reload();
      }
  })
}



$(".footer-url").addClass( "menu-is-opening menu-open");
$(".footer-url a").addClass( "active-menu");

</script>
@endsection


@section('content')


<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">

      
    <div class="row">
      
      <div class="col-sm-6">
            <ol class="breadcrumb ">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">ADD Footer {{$pageTitle}} Link</li>
            </ol>
          </div>

        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <a class="btn btn-dark btn-sm ml-1" onclick="goBack()"> ❮ Back</a>
              
          </ol>
        </div>
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1>ADD Footer {{$pageTitle}} Link</h1>
          </div>
        </div>
    </div>

      </div>
    </section>


    <section class="content">
      <div class="container-fluid">
      
        <div class="row">

          <div class="col-md-5 card card-info">
              <div class="card-header">
                      <h3 class="card-title text-capitalize">Add {{$pageTitle}} Link</h3>
                </div>
                

                <form method="post" enctype="multipart/form-data"  class="form-horizontal" 
                action="{{route('commonLink.store')}}">

                  @csrf

                  <div class="card-body p-2 pt-4">
                  <label for="name">Display Name</label>
                  <input type="text" name="name" class="form-control">
                    
                  <input type="hidden" name="type" value="{{$type}}">
                  <input type="hidden" name="table" value="{{$table}}">
                  
                  <label for="item_id">{{$pageTitle}}</label>
                    <select class="form-control" name="item_id" required>
                        <option value="">Select {{$pageTitle}}</option>
                        

                        @foreach($tableDatas as $tableData)
                        
                            @if(isset($tableData->name))
                                <option value="{{$tableData->id}}">{{$tableData->name}}</option>
                            @else
                                <option value="{{$tableData->id}}">{{$tableData->title}}</option>
                            @endif

                        @endforeach
                    </select>

                  <div class="form-check mt-4">
                    <input type="checkbox" class="form-check-input  pull-right" name="status" 
                        id="exampleCheck1"
                      checked
                        />
                        
                      <h5> <span class="badge badge-success">Active</span></h5>
                      </div>

                  </div>
                  <div class="card-footer text-right">
                    <button type="submit" class="btn btn-info text-capitalize">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp;&nbsp; Save {{$pageTitle}} Link</button>
                  </div>
                </form>

              </div>
              
           <div class="col-md-7 card card-info">
              <div class="card-header">
                      <h3 class="card-title text-capitalize">{{$pageTitle}} Link Lists</h3>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th width="300">Display Name</th>
                        <th  width="300">Name / Title</th>
                        <th>Status</th>
                        <th width="100">Action</th>
                      </tr>
                    </thead>
                    <tbody class="row_position">
                      @foreach($linkDatas as $i => $linkData)
                        <tr id="{{$linkData->id}}">
                          <td>{{$linkData->item_no}}</td>
                          <td>{{$linkData->name}}</td>
                          
                          @if(isset(getTableData($table, $linkData->item_id)->name))
                            <td  width="100">{{getTableData($table, $linkData->item_id)->name}}</td>
                        
                        @elseif(isset(getTableData($table, $linkData->item_id)->title))
                            <td width="100">{{getTableData($table, $linkData->item_id)->title}}</td>
                        @else
                          <td></td>
                        @endif

                          <td>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input  pull-right" name="status" 
                        id="exampleCheck1"
                        
                          onClick="updateStatus({{$linkData->id}})"
                          @if($linkData->status == 1)checked
                          @endif 
                          @if(old('status'))checked
                          @endif
                          />
                          
                        @if($linkData->status == 0)
                        <h5 for="status"> <span class="badge badge-danger">Inactive</span></h5>@else<h5> <span class="badge badge-success">Active</span></h5>@endif</td>
                    </div>	
                    
                        
                        </td>
                          <td>
                          <a href="{{route('commonLink.create',$pageSlug)}}?type=edit&id={{$linkData->id}}" class="btn btn-xs btn-info float-left mr-2" title="Edit {{$pageTitle}} Link"><i class="far fa-edit"></i></a>
                          

                            <button class="btn btn-xs btn-danger del-modal float-left" title="Delete data" data-id="{{$linkData->id}}"
                             data-form="{{route('blockControl.delete')}}" data-pageslug="{{$pageSlug}}" data-title="{{ $linkData->name}}"  data-toggle="modal" data-target="#modal-default"><i class="fas fa-trash-alt"></i>
                            </button>                      
                        </td>
                        </tr>
                      @endforeach

                    </tbody>
                  </table>
                  
                </div>
              </div>
           </div>
           
        </div>


      </div>
    </section>
  </div>
  
  <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-capitalize">Delete {{$pageTitle}} Links</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <label class="text-capitalize">{{$pageTitle}} Name</label>
            <h5 class="modal-title delete-title">Delete {{$pageTitle}} Link</h5>
            </div>
            <div class="modal-footer justify-content-between d-block ">
              
            <form class="delete-form float-right" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" class="delete-id" name="id" >
                    <input type="hidden" class="table" name="table" value="url_list" >
                    <input type="hidden" class="delete-slug" name="data_slug" value="" >
                    
              <button type="button" class="btn btn-default mr-4" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger float-right" title="Delete Record"><i class="fas fa-trash-alt"></i> Delete</button>
              

            </form>
            </div>
          </div>
        </div>
      </div>

  @endsection

  