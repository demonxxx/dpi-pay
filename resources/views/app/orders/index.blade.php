@extends('app.orders.order')
@section('order')
    <link href="{{ asset("theme/css/plugins/dataTables/datatables.min.css") }}" rel="stylesheet">
    <script src="{{ asset("theme/js/plugins/datatables/dataTables.min.js") }}"></script>
    <script src="{{ asset("theme/js/plugins/datatables/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("theme/js/plugins/datatables/extensions/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("theme/js/plugins/datatables/extensions/Pagination/input.js") }}"></script>

    <script src="{{ asset("js/datatable.ajax.js") }}"></script>
    <script src="{{ asset("js/constants.js") }}"></script>

<!-- tile -->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Danh sách đơn hàng</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#">Config option 1</a>
                        </li>
                        <li><a href="#">Config option 2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-custom" id="orders-list" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center" width="4%">Mã ĐH</th>
                                <th class="text-center">Tên đơn hàng</th>
                                <th class="text-center">Tên người nhận</th>
                                <th class="text-center">SĐT người nhận</th>
                                <th class="text-center">Phường/xã Từ</th>
                                <th class="text-center">Quận/huyện Từ</th>
                                <th class="text-center">Phường/xã Đến</th>
                                <th class="text-center">Quận/huyện Đến</th>
                                <th class="text-center">Chức năng</th>
                            </tr>
                            <tr class="table-header-search">
                                <th class="text-center" width="4%">
                                    <input class="text-center" name="code" value="" placeholder="Mã ĐH" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="name" value="" placeholder="Tên đơn hàng" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="recipient_name" value="" placeholder="Tên người nhận" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="recipient_phone" value="" placeholder="SĐT người nhận" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="ward_from" value="" placeholder="Phường/xã Từ" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="district_from" value="" placeholder="Quận/huyện Từ" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="ward_to" value="" placeholder="Phường/xã Đến" />
                                </th>
                                <th class="text-center">
                                    <input class="text-center" name="district_to" value="" placeholder="Quận/huyện Đến" />
                                </th>
                                <th class="text-center clear-filter"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script >
    $(document).ready(function () {
        var common_render = {
            "render": function (data, type, row) {
                return render_common(data);
            },
            "targets": [0, 1, 2, 3, 4, 5, 6, 7]
        };

        var function_render = {
            "render": function (data, type, row) {
                return render_function(data);
            },
            "targets": [8]
        };

        function render_common(data) {
            return "<div class='text-center'>" + data + "</div>";
        }

        function render_function(data) {
            var edit_url = base_url + "/order/" + data + "/edit";
            return "<div class='text-center'>" +
                    "<a class='btn btn-primary' disabled href='" + edit_url + "' style='width: 70px;'>Sửa</a>" +
                    "<a class='btn btn-danger' disabled style='width: 70px; margin-left: 10px;'>Xóa</a>" +
                    "</div>";
        }

        var config = [];
        var renders = [];
        renders.push(common_render);
        renders.push(function_render);
        config['colums'] = ["code", "name", "recipient_name", "recipient_phone", "ward_from", "district_from", "ward_to", "district_to", "id"];
        config['url'] = "/order/load_list";
        config['id'] = "orders-list";
        config['data_array'] = renders;
        config['clear_filter'] = true;
        config['sort_off'] = [8];
        config['hidden_global_seach'] = true;
        setAjaxDataTable(config);
    });

</script>
@endsection


