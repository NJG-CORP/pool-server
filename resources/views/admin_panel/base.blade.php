<html lang="en" class="translated-ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf-backend">
    <meta name="csrf-token" content="1e0qvY3aD7kHBjWjVuoJmzgqTXbUXeIb6ITRX2p-HyewikfrwqNV2HJQXcA8oVDPbX48Ab9tsknZy4kvIQ1rEQ==">
    <title>Admin Panel Pool Server</title>
    
<link href="/assets/a1fa7950/css/bootstrap.css" rel="stylesheet">
<link href="/css/site.css" rel="stylesheet"><link type="text/css" rel="stylesheet" charset="UTF-8" href="https://translate.googleapis.com/translate_static/css/translateelement.css"></head>
<body cz-shortcut-listen="true">

<div class="wrap">
    
    {{-- navbar included --}}
    
    @include('admin_panel.nav')

    <div class="container-fluid">
                        
    <div class="row">
        <div class="col-md-12">
            <div id="UserTable" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000"><div id="w0" class="grid-view"><div class="summary"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Showing </font></font><b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">1-10</font></font></b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> of </font></font><b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">26 items</font></font></b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> .</font></font></div>
<table class="table table-striped table-bordered"><thead>
<tr><th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Username</font></font></th><th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Age</font></font></th><th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Email</font></font></th><th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Phone number</font></font></th><th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Trainer</font></font></th><th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Changed</font></font></th><th class="action-column"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Control</font></font></th></tr>
</thead>
<tbody>
<tr data-key="0"><td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Denis Sidorov</font></font></td><td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">222</font></font></td><td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">njoytf70d0t@gmail.com</font></font></td><td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">+7 (899) 952-93-12</font></font></td><td><span class="glyphicon glyphicon-remove" style="color:darkred"></span></td><td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Nov 6 </font><font style="vertical-align: inherit;">2018 20:10:35</font></font></td><td><button type="button" class="btn btn-primary" onclick="$.pjax({url: '/user/get-user-form?id=2', push: false, container: '#UpdateUserForm'});$('#UpdateUserModal').modal('show');" data-pjax="0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Edit user</font></font></button> <button type="button" class="btn btn-primary" data-target="#DeleteUserModal" data-toggle="modal" onclick="$(&quot;#deleteuserform-id&quot;).val(2)"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Delete user</font></font></button></td></tr>



</tbody></table>


            </div>

        </div>        
    </div>

</div>

<div id="DeleteUserModal" class="fade modal" role="dialog" tabindex="-1">
<div class="modal-dialog ">
<div class="modal-content">
<div id="myModalLabel" class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></button>
<h4><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Deleting user</font></font></h4>
</div>
<div class="modal-body">
<div id="DeleteUserForm" data-pjax-container="" data-pjax-timeout="1000"><form id="w1" action="/user/delete-user" method="post" data-pjax="">
<input type="hidden" name="_csrf-backend" value="1e0qvY3aD7kHBjWjVuoJmzgqTXbUXeIb6ITRX2p-HyewikfrwqNV2HJQXcA8oVDPbX48Ab9tsknZy4kvIQ1rEQ=="><div class="form-group field-deleteuserform-id required">

<input type="hidden" id="deleteuserform-id" class="form-control" name="DeleteUserForm[id]">

<p class="help-block help-block-error"></p>
</div><p>Вы действительно хотите удалить пользователя?</p>
<div class="form-group">
    <button type="submit" class="btn btn-success" name="login-button">Удалить</button></div>

</form></div>
</div>

</div>
</div>
</div>
<div id="UpdateUserModal" class="fade modal" role="dialog" tabindex="-1">
<div class="modal-dialog ">
<div class="modal-content">
<div id="myModalLabel" class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h4>Изменение пользователя</h4>
</div>
<div class="modal-body">
<div id="UpdateUserForm" data-pjax-container="" data-pjax-timeout="1000"></div>
</div>

</div>
</div>
</div>    </div>
</div>
<script src="/assets/8b808add/jquery.js"></script>
<script src="/assets/1441e7fb/yii.js"></script>
<script src="/assets/1441e7fb/yii.gridView.js"></script>
<script src="/assets/192d359d/jquery.pjax.js"></script>
<script src="/assets/1441e7fb/yii.validation.js"></script>
<script src="/assets/1441e7fb/yii.activeForm.js"></script>
<script src="/assets/a1fa7950/js/bootstrap.js"></script>
<script src="/js/menu.js"></script>
<script>jQuery(function ($) {
jQuery('#w0').yiiGridView({"filterUrl":"\/user\/","filterSelector":"#w0-filters input, #w0-filters select"});
jQuery(document).pjax("#UserTable a", {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#UserTable"});
jQuery(document).on("submit", "#UserTable form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#UserTable"});});
$("document").ready(function(){
        $("#DeleteUserForm").on("submit",function(){
            $("#DeleteUserModal").modal("hide");
            setTimeout(function(){
                $.pjax({url: window.location.href, push: false, container: "#UserTable"});
            },1000)
        }); 
    });

jQuery('#w1').yiiActiveForm([{"id":"deleteuserform-id","name":"id","container":".field-deleteuserform-id","input":"#deleteuserform-id","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {value = yii.validation.trim($form, attribute, []);yii.validation.required(value, messages, {"message":"Необходимо заполнить «Id»."});}}], []);
jQuery(document).pjax("#DeleteUserForm a", {"push":false,"replace":false,"timeout":1000,"scrollTo":false,"container":"#DeleteUserForm"});
jQuery(document).on("submit", "#DeleteUserForm form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":false,"replace":false,"timeout":1000,"scrollTo":false,"container":"#DeleteUserForm"});});
jQuery('#DeleteUserModal').modal({"show":false});
jQuery(document).pjax("#UpdateUserForm a", {"push":false,"replace":false,"timeout":1000,"scrollTo":false,"container":"#UpdateUserForm"});
jQuery(document).on("submit", "#UpdateUserForm form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":false,"replace":false,"timeout":1000,"scrollTo":false,"container":"#UpdateUserForm"});});
jQuery('#UpdateUserModal').modal({"show":false});
});</script>

<div id="goog-gt-tt" class="skiptranslate" dir="ltr"><div style="padding: 8px;"><div><div class="logo"><img src="https://www.gstatic.com/images/branding/product/1x/translate_24dp.png" width="20" height="20" alt="Google Translate"></div></div></div><div class="top" style="padding: 8px; float: left; width: 100%;"><h1 class="title gray">Original text</h1></div><div class="middle" style="padding: 8px;"><div class="original-text"></div></div><div class="bottom" style="padding: 8px;"><div class="activity-links"><span class="activity-link">Contribute a better translation</span><span class="activity-link"></span></div><div class="started-activity-container"><hr style="color: #CCC; background-color: #CCC; height: 1px; border: none;"><div class="activity-root"></div></div></div><div class="status-message" style="display: none;"></div></div><div class="goog-te-spinner-pos"><div class="goog-te-spinner-animation"><svg xmlns="http://www.w3.org/2000/svg" class="goog-te-spinner" width="96px" height="96px" viewBox="0 0 66 66"><circle class="goog-te-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div></div></body></html>