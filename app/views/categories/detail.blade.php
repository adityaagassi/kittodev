                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-tag"></i> Detail Category </h2>
                </div>
                <div class="contentpanel">
                    @if(isset($category))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-dark panel-alt">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ $category->name }}</h3>
                                    @if(count($category->products) < 2)
                                    <p>found {{ count($category->products) }} item</p>
                                    @else
                                    <p>found {{ count($category->products) }} items</p>
                                    @endif
                                </div>
                                <div class="panel-body">
                                    @if(count($category->products) > 0)
                                    <div class="table-responsive">
                                        <table class="table" id="table2">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Item name</th>
                                                    <th>Category</th>
                                                    <th><center>Quantity</center></th>
                                                    @if(Session::has('level_id') && Session::get('level_id') < 3)
                                                    <th>Buy price</th>
                                                    <th>Sell price</th>
                                                    <th><center>Configuration</center></th>
                                                    @else
                                                    <th>Price</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $index = 1; ?>
                                                @foreach ($category->products as $product)
                                                @if($index % 2 == 1)
                                                <tr class="odd">
                                                @else
                                                <tr class="even">
                                                @endif
                                                    <td><a href="{{ url("products/$product->id") }}">{{ $product->id }}</a></td>
                                                    <td><a href="{{ url("products/$product->id") }}">{{ $product->name }}</a></td>
                                                    <td>{{ $product->category->name }}</td>
                                                    <td class="center"><center>{{ $product->quantity }}</center></td>
                                                    @if(Session::has('level_id') && Session::get('level_id') < 3)
                                                    <td>{{ $product->buy_price }}</td>
                                                    <td>{{ $product->sell_price }}</td>
                                                    <td class="center">
                                                        <center>
                                                            <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("products/$product->id/delete") }}', '{{ $product->name }}');">
                                                                <span class="fa fa-times"></span>
                                                            </a>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <a href="{{ url("products/$product->id/edit") }}">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                        </center>
                                                    </td>
                                                    @else
                                                    <td>{{ $product->sell_price }}</td>
                                                    @endif
                                                </tr>
                                                <?php $index += 1; ?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                        No product with this category
                                    @endif
                                </div>
                            </div><!-- panel -->
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                Are you sure delete?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <a id="modalDeleteButton" href="#" type="button" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                @stop

                @section('scripts')
                <script>
                    jQuery(document).ready(function() {

                        $(".mainpanel").css("height", "");
                        
                        // Spinner
                        var quantity = jQuery('#quantity').spinner();
                        quantity.spinner('value', 0);
                        // Chosen
                        jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
                        // Basic Form
                        jQuery("#basicForm").validate({
                            highlight: function(element) {
                                jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                            },
                            success: function(element) {
                                jQuery(element).closest('.form-group').removeClass('has-error');
                            }
                        });
                    });
                    function deleteConfirmation(url, name) {
                        jQuery('.modal-body').text("Are you sure want to delete product with name '" + name + "'?");
                        jQuery('#modalDeleteButton').attr("href", url);
                    }
                </script>
                @stop