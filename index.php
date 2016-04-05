<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container" id="content">
    <div class="row">
        <div class="col-md-12" style="text-align: center;">
            <h1>Address spliter</h1>
            <a class="btn btn-primary" href="javascript:get_address();" style="margin-top: 10px;">Generate new address</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div id="p_alert"></div>
            <table class="table table-bordered" id="addresses">
                <thead>
                <tr>
                    <th>Address</th><th>Action</th>
                </tr>
                </thead>
                <tbody id="addresses_body">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="md_resend_adresses"  class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"><!-- -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Resend adresses</h4>
            </div>
            <div class="modal-body" id="md_resend_adresses_content">
                <div class="m_alert"></div>
                <table class="table table-condensed">
                    <thead><tr><th>Address</th><th>Share</th><th>Actions</th></tr></thead>
                    <tbody id="md_resend_adresses_body"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div class="form-inline">
                <input type="text" placeholder="Address" class="form-control pull-left" id="resend_address" style="width: 382px">
                <input type="number" placeholder="%" min="0" max="100"  class="form-control" id="resend_share" style="width: 80px">
                <a class="btn btn-success" id="add_resend_address" href="">Create new</a>
                </div>
            </div>
        </div><!-- -->
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/dataTables.min.js"></script>
<script>
    $(document).ready(function(){
        get_addresses();
    });
    function m_alert(type, text){
        $('.modal.in .m_alert').html('<div class="alert alert-dismissible alert-'+type+'" role="alert">'+text+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>');
    }
    function p_alert(type, text){
        $('#p_alert').html('<div class="alert alert-dismissible alert-'+type+'" role="alert">'+text+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>');
    }
    $(window).on('shown.bs.modal', function() {
        $('.modal.in .m_alert').html('');
    });
    function get_address(){
        $.ajax({
            'url': "functions.php?get_address",
            'dataType': "json",
            'success': function(data) {
                if (data['error']=='no') {
                    get_addresses();
                    p_alert('success','<b>Success! </b>Adress has been generated');
                }
                if (data['error']=='yes') p_alert('danger', + data['message']);
            }
        });
    }
    function get_addresses(){
        $.ajax({
            'url': "functions.php?get_addresses",
            'dataType': "json",
            'success': function(data) {
                $('#addresses_body').html($(data['data']));
                if (data['error']=='yes') p_alert('danger', + data['message']);
            }
        });
    }
    function md_resend_adresses(address_id){
        $.ajax({
            'url': "functions.php?get_resend_addresses&address_id=" + address_id,
            'dataType': "json",
            'success': function(data) {
                $('#md_resend_adresses_body').html($(data['data']));
                if (data['error']=='yes') m_alert('danger', data['message']);
            }
        });
        $('#add_resend_address').attr('href', 'javascript:add_resend_address('+address_id+');');
        if (!$('#myModal').is(':visible')) {
            $('#md_resend_adresses').modal();
        }
    }
    function delete_resend_addresses(address_id, id){
        $.ajax({
            'url': "functions.php?delete_resend_addresses&id=" + id,
            'dataType': "json",
            'success': function(data) {
                if (data['error']=='no') {
                    md_resend_adresses(address_id);
                    m_alert('success','<b>Success! </b>Adress has been deleted');
                }
                if (data['error']=='yes') m_alert('danger', data['message']);
            }
        });
    }
    function add_resend_address(address_id){
        $.ajax({
            'url': "functions.php?add_resend_address&address_id="+address_id+"&resend_address=" + $('#resend_address').val() + "&share=" + $('#resend_share').val(),
            'dataType': "json",
            'success': function(data) {
                if (data['error']=='no') {
                    md_resend_adresses(address_id);
                    m_alert('success','<b>Success! </b>Adress has been created');
                }
                if (data['error']=='yes') m_alert('danger', data['message']);
            }
        });
    }
    function generateAddress(){
        $.ajax({
            'url': "functions.php",
            'dataType': "json",
            'success': function(data) {
                if (data['error']=='yes') p_alert('danger', data['message']);
            }
        });
    }
</script>
</body>
</html>