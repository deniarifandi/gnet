<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Laporan Tagihan Wi-Fi') }}
    </h2>
  </x-slot>



  <x-slot name="slot">



    <div style="width: 90%; margin-left: 5%; margin-right: 5%;" align="center">


      @php
        try{
          $gettahun = $_GET['tahun'];
          $getpage = $_GET['page'];
          $getsearch = $_GET['search'];

        }catch(Exception $e){
          header("Location: ".URL::to('/')."/dashboard?tahun=".date("Y")."&page=0&search="); 
          exit();
        }

      @endphp

      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-6 ">
             Laporan Tagihan Wi-Fi
           </div>
           <div class="col-6">
            {{-- <a href="{{ url('openTambahPelanggan') }}" class="btn btn-sm btn-info" style="float: right;">Tambah Pelanggan</a> --}}
            <button type="button" style="float: right;" class="btn btn-info btn-sm " data-toggle="modal" data-target="#myModal">
              Tambah Pelanggan
            </button>
          </div>

        </div>


      </div>
      <div class="card-body" >
                <div class="container1">
                  <div class="row">
                    <div class="col-5" >
                      <div class="input-group mb-3 bg-danger">
                        <div class="input-group-prepend bg-info">
                          <span class="input-group-text bg-info text-white">Cari Nama Customer</span>
                        </div>

                        <input type="text" id="fieldCariNama" class="form-control">
                        <div class="input-group-append">
                          <button class="btn btn-success" type="button" onclick="searchNama()">Cari</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-5">
                      
                    </div>
                    <div class="col-2">
                      <select class="form-control form-control-lg bg-danger text-white" name="optTahun" id="optTahun" style="float: right;" onchange="changeTahun()">
                      <option id="tahun2020" value="2020"><h1>2020</h1></option>
                      <option id="tahun2021" value="2021">2021</option>
                      <option id="tahun2022" value="2022">2022</option>
                      <option id="tahun2023" value="2023">2023</option>
                      <option id="tahun2024" value="2024">2024</option>
                    </select>
                    </div>
                  </div>
                </div>

        <div class="table-responsive">

          


          <table class="table table-striped table-bordered" style="overflow: auto;">
            <thead>
              <tr>
                <th colspan="1" rowspan="2" >ID Pelanggan</th>
                <th colspan="1" rowspan="2" >Nama</th>
                <th colspan="1" rowspan="2" >Telepon</th>

                <th colspan="12" align="center">
                  <div class="dropdown">
                    Pembayaran Bulan
                    
                  </div>
                </th>
              </tr>
              <tr>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Agus</th>
                <th>Sept</th>
                <th>Okt</th>
                <th>Nop</th>
                <th>Des</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($customers as $customer)
              <tr>
                <td>{{ $customer['customer_id'] }}</td>
                <td>{{ $customer['nama'] }}</td>
                <td>{{ $customer['telepon'] }}</td>
                {{-- <td>{{ $customer['tahun'] }}</td> --}}

                @for($i = 1; $i<=12 ; $i++)
                <td>
                  @if($customer['bulan'.$i] == "Belum Bayar")
                  <button id="{{$customer['customer_id']}}_{{ $i }}" type="button" disabled="" style="float: right;" data-toggle="modal" data-target="#myModalbayar" onclick="getDetailPembayaran('{{ $customer['customer_id'] }}','{{ $customer['nama'] }}','{{ $i }}',{{ $customer['tahun'] }}); cekradio()" value="0" class="btn_status_bayar btn btn-danger btn-sm"> Belum Bayar
                  @else
                  <button id="{{$customer['customer_id']}}_{{ $i }}" type="button" disabled="" style="float: right;" data-toggle="modal" data-target="#myModalbayar" onclick="getDetailPembayaran('{{ $customer['customer_id'] }}','{{ $customer['nama'] }}','{{ $i }}',{{ $customer['tahun'] }}); cekradio()" value="1" class="btn_status_bayar btn btn-success btn-sm"> Sudah Bayar
                  @endif
                  </button>
                </td>
                @endfor

              </tr>
              @endforeach
            </tbody>
          </table>
           <ul class="pagination pagination-sm" style="float: right;">
                {{-- pagecount : {{ $pagecount }} --}}
                <li id="previous_button"   class="page-item"><a class="page-link" 
                  href="{{ URL::to('/') }}/dashboard?tahun={{ $_GET['tahun'] }}&page={{ $getpage-1 ?? '0' }}&search={{ $getsearch }}">Previous</a></li>
                @for($i = 0; $i<= $pagecount; $i++)


                  @if($i < $_GET['page'] && $i > $_GET['page']-9)
                  <li id="page{{$i}}" class="page-item"><a class="page-link" 
                  href="{{ URL::to('/') }}/dashboard?tahun={{ $_GET['tahun'] }}&page={{ $i }}&search={{ $getsearch }}">{{ $i+1}}</a></li>
                  @endif



                  @if($i == $_GET['page'])
                 <li id="page{{$i}}" class="page-item active"><a class="page-link" 
                  href="{{ URL::to('/') }}/dashboard?tahun={{ $_GET['tahun'] }}&page={{ $i }}&search={{ $getsearch }}">{{ $i+1}}</a></li>
                  @endif

                   @if($i > $_GET['page'] && $i < $_GET['page']+9)
                  <li id="page{{$i}}" class="page-item"><a class="page-link" 
                  href="{{ URL::to('/') }}/dashboard?tahun={{ $_GET['tahun'] }}&page={{ $i }}&search={{ $getsearch }}">{{ $i+1}}</a></li>
                  @endif

                @endfor
                <li id="next_button"   class="page-item"><a id="next_button" class="page-link" 
                  href="{{ URL::to('/') }}/dashboard?tahun={{ $_GET['tahun'] }}&page={{ $getpage+1 }}&search={{ $getsearch }}">Next</a></li>
              </ul>   

        </div> {{-- table responsive --}}
      </div>
    </div>

  </div>

  <!-- The Modal tambah pelanggan -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Tambah Pelanggan</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
         <form action="{{ url('funcTambahPelanggan') }}">
          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama">
          </div>
          <div class="form-group">
           <label for="Telepon">Telepon</label>
           <input type="text" class="form-control" id="telepon" name="telepon">
         </div>

         <div class="form-group">
           <label for="Alamat">Alamat</label>
           <input type="text" class="form-control" id="alamat" name="alamat">
         </div>

       </div>
       <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

      </form>
    </div>

    <!-- Modal footer -->


  </div>
</div>
</div>

{{-- end modal tambah pelanggan --}}


<!-- The Modal pembayaran -->
<div class="modal fade" id="myModalbayar">
  <div class="modal-dialog" >
    <div class="modal-content" id="modal_overlay">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Pembayaran</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="modal-body1">


        <form id="modalbayar">
          <div class="form-group">
            <div class="row">
              <div class="col-4">
                <label for="customerid">ID Pelanggan</label>
                <input type="text" class="form-control" id="customerid" name="customerid" readonly="">    
              </div> 
              <div class="col-8">
                <label for="bayarnama">Nama</label>
                <input type="text" class="form-control" id="bayarnama" name="bayarnama" readonly="">    
              </div>  
            </div>
          </div>

          <div class="form-group">

            <div class="row">
              <div class="col-6">
                <label for="bayarbulan">Bulan</label>
                <input type="text" class="form-control" id="bayarbulan" name="bayarbulan" readonly="">  
              </div>

              <div class="col-6">
                <label for="bayartahun">Tahun</label>
                <input type="text" class="form-control" id="bayartahun" name="bayartahun" readonly="">  
              </div>  
            </div>
          </div>

          <div class="row">
            <div class="col-12" align="center">

              <div class="custom-control custom-radio custom-control-inline" style="margin-top: 15px">
                <input type="radio" class="custom-control-input" id="radio_belum_bayar" name="example" onchange="changeBayar(0)">
                <label class="custom-control-label" for="radio_belum_bayar" ><h4>Belum Bayar</h4></label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="radio_sudah_bayar" name="example" onchange="changeBayar(1)">
                <label class="custom-control-label" for="radio_sudah_bayar" value="postBayar" ><h4>Sudah Bayar</h4></label>
              </div>  

            </div>
          </div>

            <div class="row">

                <div class="col-md-12" align="center">

                    <button type="button" id="printinv_button" class="btn btn-success" style="margin-top: 25px"  onclick="print_invoice()" disabled="disable">Print Invoice.</button>
                    
                </div>
                
            </div>

          {{-- <input type="text" class="form-control" id="idbayar" name="idbayar" readonly="" >   --}}
        </form>
      </div>




      <div class="modal-body" id="modal-body2" style="display: none;">

        <div class="row">
          <div class="col-12" align="center">
            <h4>Mohon Tunggu, Memproses Pembayaran....</h4>    
          </div>
          <div class="col-12" align="center">
            <div class="spinner-border text-warning"></div>
          </div>
        </div>

      </div>

      <!-- Modal footer -->


    </div>
  </div>
</div>

              <script type="text/javascript"> //check if page fully loaded

                window.addEventListener('load', function () { // check if page fully loaded
                  // alert("It's loaded!")
                  var x = document.getElementsByClassName("btn_status_bayar");
                  // console.log(x);
                  for (var i = 0; i < x.length; i++) {
                    x[i].disabled = false;
                  }
                  checkPage();
                  document.getElementById("tahun"+{{  $_GET['tahun'] }}).selected = "selected";
                })

              </script>

              <script type="text/javascript">

                function getDetailPembayaran(customer_id, nama, bulan, tahun){

                  document.getElementById("customerid").value = customer_id;
                  document.getElementById("bayarnama").value = nama;
                  document.getElementById("bayarbulan").value = bulan;
                  document.getElementById("bayartahun").value = tahun;
                }

                function changeBayar(value){

                  if (value == 0) {
                    console.log("proses bayar");
                    document.getElementById("modal-body1").style.display = "none";
                    document.getElementById("modal-body2").style.display = "";
                    postTidakBayar();

                  }else if (value == 1) {
                    console.log("proses bayar");
                    document.getElementById("modal-body1").style.display = "none";
                    document.getElementById("modal-body2").style.display = "";
                    postBayar();
                  } 

                }

                function postBayar (){

                  var str = $( "#modalbayar" ).serializeArray();
                  // console.log(str);
                  var cust_id = str[0].value;
                  var bulan = str[2].value;
                  $.ajaxSetup({
                    url: "{{ url('postbayar') }}",
                    type: "post",
                    data:{ 
                      "customer_id":str[0].value,
                      "bulan":str[2].value,
                      "tahun":str[3].value,
                      "nominal":100000
                    },
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result){

                        document.getElementById("modal-body1").style.display = "";
                        document.getElementById("modal-body2").style.display = "none";
                        document.getElementById(cust_id+"_"+bulan).classList.add("btn-success");
                        document.getElementById(cust_id+"_"+bulan).classList.remove("btn-danger");
                        document.getElementById(cust_id+"_"+bulan).innerHTML = "Sudah Bayar";
                        document.getElementById(cust_id+"_"+bulan).value = 1;
                        document.getElementById("printinv_button").disabled = false;
                      }
                    });
                  $.ajax();

                }

                function postTidakBayar(){

                  var str = $( "#modalbayar" ).serializeArray();
                  // console.log(str);
                  var cust_id = str[0].value;
                  var bulan = str[2].value;
                  $.ajaxSetup({
                    url: "{{ url('posttidakbayar') }}",
                    type: "post",
                    data:{ 
                          "customer_id":str[0].value,
                          "bulan":str[2].value,
                          "tahun":str[3].value,
                        },
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result){
                            document.getElementById("modal-body1").style.display = "";
                            document.getElementById("modal-body2").style.display = "none";
                            document.getElementById(cust_id+"_"+bulan).classList.add("btn-danger");
                            document.getElementById(cust_id+"_"+bulan).classList.remove("btn-success");
                            document.getElementById(cust_id+"_"+bulan).innerHTML = "Belum Bayar";
                            document.getElementById(cust_id+"_"+bulan).value = 0;
                            document.getElementById("printinv_button").disabled = true;
                        }
                      });
                  $.ajax();

                }

              </script>


              <script type="text/javascript">

                var searchField = document.getElementById("fieldCariNama");

                 searchField.addEventListener("keyup", function(event) {
                    // Number 13 is the "Enter" key on the keyboard
                    if (event.keyCode === 13) {
                      // Cancel the default action, if needed
                      event.preventDefault();
                      // Trigger the button element with a click
                      // document.getElementById("myBtn").click();
                      searchNama();

                    }
                  });

                function reloadPage(){
                  location.reload();
                }

                function cekradio(){
                  var str = $( "#modalbayar" ).serializeArray();
                  console.log(str);
                  var cust_id = str[0].value;
                  var bulan = str[2].value;
                  if (document.getElementById(cust_id+"_"+bulan).value == 0) {
                    document.getElementById("radio_belum_bayar").checked = true;  
                    document.getElementById("printinv_button").disabled = true;
                  }else if(document.getElementById(cust_id+"_"+bulan).value == 1) {
                    document.getElementById("radio_sudah_bayar").checked = true;  
                    document.getElementById("printinv_button").disabled = false;
                  }
                  
                }

                function print_invoice(){

                     var str = $( "#modalbayar" ).serializeArray();
                     console.log(str);
                    window.open('{{ URL::to('/') }}/print_invoice?nama='+str[1].value+'&nomor='+str[0].value+'&id='+str[0].value+'&bulan='+str[2].value+'&tahun='+str[3].value+'&bayar=no_invoice');
                }

                function checkPage(){
                  if ( <?php echo $getpage ?> == 0) {
                    console.log('disabled prev');
                    document.getElementById("previous_button").classList.add("disabled");  
                  }else if( <?php echo $getpage ?> == {{ $pagecount }}){
                    console.log('disabled next');
                    document.getElementById("next_button").classList.add("disabled");  
                  }

                  document.getElementById("page<?php  echo $getpage ?>").classList.add("active");  
                }

                function searchNama(){
                  console.log('testing');
                 var searchValue = document.getElementById("fieldCariNama").value;
                  window.location.replace('{{ URL::to('/') }}/dashboard?tahun={{  $_GET['tahun'] }}&page=0&search='+searchValue);

                }

                function changeTahun(){
                  var e = document.getElementById("optTahun");
                  var strUser = e.options[e.selectedIndex].value;
                  window.location.replace('{{ URL::to('/') }}/dashboard?tahun='+strUser+'&page=0&search={{  $_GET['search'] }}');
                }

              </script>




            </x-slot>

          </x-app-layout>
