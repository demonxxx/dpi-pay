@extends('templates.admin')
@section('content')
@include('partials.flash')
<link href="{{ asset("theme/css/plugins/jsTree/style.css") }}" rel="stylesheet">
<script src="{{ asset("theme/js/plugins/jsTree/jstree.js") }}"></script>
<div id="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách đơn vị hành chính</h5>
                    <div class="ibox-tools">
                        <button class="btn btn-primary" onclick="addCity()"><i class="fa fa-plus"></i> Thêm thành phố</button>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="administrative_units">
                        <ul>
                            @foreach($cities as $city)
                            <li id="js_node_{{$city->id}}">
                                <span class="city_name">{{$city->name}}</span>
                                <span style="margin-left: 5px; color: blue;" onclick="addUnit({{$city->id}},'{{$city->name}}',{{$city->level}})">Thêm</span>&nbsp;
                                <span style="margin-left: 5px; color: blue;" onclick="editUnit({{$city->id}},'{{$city->name}}')">Sửa</span>&nbsp;
                                <span style="margin-left: 5px; color: blue;" onclick="deleteUnit({{$city->id}})">Xóa</span>
                                <ul>
                                    @foreach($city->districts as $district)
                                    <li id="js_node_{{$district->id}}">
                                        <span class="district_name">{{$district->name}}</span>
                                        <span style="margin-left: 5px; color: blue;" onclick="addUnit({{$district->id}},'{{$district->name}}',{{$district->level}})">Thêm</span>&nbsp;
                                        <span style="margin-left: 5px; color: blue;" onclick="editUnit({{$district->id}},'{{$district->name}}')">Sửa</span>&nbsp;
                                        <span style="margin-left: 5px; color: blue;" onclick="deleteUnit({{$district->id}})">Xóa</span>
                                        <ul>
                                            @foreach($district->wards as $ward)
                                            <li id="js_node_{{$ward->id}}">
                                                <span class="ward_name">{{$ward->name}}</span>
                                                <span style="margin-left: 5px; color: blue;" onclick="editUnit({{$ward->id}},'{{$ward->name}}')">Sửa</span>&nbsp;
                                                <span style="margin-left: 5px; color: blue;" onclick="deleteUnit({{$ward->id}})">Xóa</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteUnit(id) {
    swal({
    title: "Bạn chắc chắn chứ?",
            text: "Bạn sẽ không thể phục hồi dữ liệu!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText:"Hủy",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Chắc chắn!",
            closeOnConfirm: false
    }, function () {
    $.ajax({
    url: base_url + "/admin/settings/administrative_units/" + id + "/delete",
            type: 'GET',
            success: function (result) {
            if (parseInt(result) == AJAX_SUCCESS) {
            swal({
            title: "Đã xóa!",
                    text: "Đơn vị hành chính đã được xóa.",
                    type: "success",
            }, function () {
            $("#js_node_" + id).remove();
            });
            } else {
            swal("Lỗi", "Xóa không thành công, kiểm tra có đơn vị hành chính trực thuộc không?", "error");
            }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            swal("Lỗi", "Xóa không thành công!", "error");
            }
    });
    });
    }

    function editUnit(unit_id, name){
    bootbox.dialog({
    message: "<input id='edit_unit_input' class='fomr-control' style='width:100%;' value='" + name + "'>",
            title: "Cập nhật tên đơn vị hành chính",
            buttons: {
            success: {
            label: "Hủy",
                    className: "btn-success",
                    callback: function() {
                    }
            },
                    main: {
                    label: "Cập nhật",
                            className: "btn-primary",
                            callback: function() {
                            if (name.toLowerCase() === document.getElementById('edit_unit_input').value.toLowerCase()) {
                            $.notify("Không có thay đổi", "success");
                            } else if (document.getElementById('edit_unit_input').value == "") {
                            $("#edit_unit_input").notify("Không được để trống", "error");
                            return false;
                            } else {
                            $.ajax({
                            url: base_url + "/admin/settings/administrative_units/edit",
                                    type: 'post',
                                    data:{id:unit_id, edit_name:document.getElementById('edit_unit_input').value},
                                    success: function (result) {
                                    if (parseInt(result) == AJAX_SUCCESS) {
                                    swal({
                                    title: "Thành Công!",
                                            text: "Đơn vị hành chính đã được cập nhật.",
                                            type: "success",
                                    }, function () {
                                    $.get(base_url + "/admin/settings/administrative_units_template", function(data, status){
                                    $(".ibox-content").html(data);
                                    $('#administrative_units').jstree();
                                    expand_unit(unit_id);
                                    });
                                    });
                                    } else {
                                    swal("Lỗi", "Cập nhật không thành công, kiểm tra có đơn vị hành chính cùng cấp trùng tên không?", "error");
                                    }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    swal("Lỗi", "Cập nhật không thành công!", "error");
                                    }
                            });
                            }
                            }
                    }
            }
    });
    }

    function addUnit(unit_id, unit_name, unit_level) {
    var bootboxTitle = (unit_level == UNIT_CITY) ? "Thêm Quận/ Huyện cho thành phố ": "Thêm Phường/ Xã cho Quận/ Huyện: ";
    bootboxTitle += unit_name;
    bootbox.dialog({
    message: "<input id='add_unit_input' class='fomr-control' style='width:100%;'>",
            title: bootboxTitle,
            buttons: {
            success: {
            label: "Hủy",
                    className: "btn-success",
                    callback: function() {
                    }
            },
                    main: {
                    label: "Thêm",
                            className: "btn-primary",
                            callback: function() {
                            if (document.getElementById('add_unit_input').value == "") {
                            $("#add_unit_input").notify("Không được để trống", "error");
                            return false;
                            } else {
                            $.ajax({
                            url: base_url + "/admin/settings/administrative_units/add",
                                    type: 'post',
                                    dataType: 'json',
                                    data:{parent_id:unit_id, unit_name:document.getElementById('add_unit_input').value},
                                    success: function (result) {
                                    if (parseInt(result.status) == AJAX_SUCCESS) {
                                    swal({
                                    title: "Thành Công!",
                                            text: "Đơn vị hành chính đã được thêm.",
                                            type: "success",
                                    }, function () {
                                    $.get(base_url + "/admin/settings/administrative_units_template", function(data, status){
                                    $(".ibox-content").html(data);
                                    $('#administrative_units').jstree();
                                    expand_unit(result.inserted_id);
                                    });
                                    });
                                    } else {
                                    swal("Lỗi", "Thêm không thành công, kiểm tra có đơn vị hành chính cùng cấp trùng tên không?", "error");
                                    }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    swal("Lỗi", "Thêm không thành công!", "error");
                                    }
                            });
                            }
                            }
                    }
            }
    });
    }

    function addCity() {
    bootbox.dialog({
    message: "<input id='add_city_input' class='fomr-control' style='width:100%;'>",
            title: "Thêm thành phố",
            buttons: {
            success: {
            label: "Hủy",
                    className: "btn-success",
                    callback: function() {
                    }
            },
                    main: {
                    label: "Thêm",
                            className: "btn-primary",
                            callback: function() {
                            if (document.getElementById('add_city_input').value == "") {
                            $("#add_city_input").notify("Không được để trống", "error");
                            return false;
                            } else {
                            $.ajax({
                            url: base_url + "/admin/settings/administrative_units/add_city",
                                    type: 'post',
                                    data:{city_name:document.getElementById('add_city_input').value},
                                    success: function (result) {
                                    if (parseInt(result) == AJAX_SUCCESS) {
                                    swal({
                                    title: "Thành Công!",
                                            text: "Thành phố đã được thêm.",
                                            type: "success",
                                    }, function () {
                                    $.get(base_url + "/admin/settings/administrative_units_template", function(data, status){
                                    $(".ibox-content").html(data);
                                    $('#administrative_units').jstree();
                                    });
                                    });
                                    } else {
                                    swal("Lỗi", "Thêm không thành công, kiểm tra có đơn vị hành chính cùng cấp trùng tên không?", "error");
                                    }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    swal("Lỗi", "Thêm không thành công!", "error");
                                    }
                            });
                            }
                            }
                    }
            }
    });
    }
    function expand_unit(unit_id) {
    $('#administrative_units').jstree("select_node", '#js_node_' + unit_id, true);
    return true;
    }

    $(function () {
    $('#administrative_units').jstree();
    });
</script>
@endsection